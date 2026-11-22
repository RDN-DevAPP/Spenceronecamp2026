<?php

namespace App\Services;

use App\Models\PaidVoteTransaction;
use App\Models\ReguVote;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DanaQrisService
{
    /**
     * Ambil seluruh konfigurasi DANA QRIS dari database settings.
     */
    public function getSettings(): array
    {
        $keys = [
            'dana_qris_payload',
            'dana_qris_image_path',
            'dana_account_name',
            'dana_webhook_secret',
            'dana_unique_code_enabled',
            'dana_sandbox_mode',
        ];

        $settings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        // Pastikan webhook secret selalu tersedia agar aman
        $secret = trim((string) ($settings['dana_webhook_secret'] ?? ''));
        if ($secret === '') {
            $secret = 'dana_' . Str::random(24);
            Setting::updateOrCreate(['key' => 'dana_webhook_secret'], ['value' => $secret]);
        }

        return [
            'qris_payload'        => trim((string) ($settings['dana_qris_payload'] ?? '')),
            'qris_image_path'     => $settings['dana_qris_image_path'] ?? null,
            'account_name'        => trim((string) ($settings['dana_account_name'] ?? 'DANA Panitia')),
            'webhook_secret'      => $secret,
            'unique_code_enabled' => ($settings['dana_unique_code_enabled'] ?? '0') === '1',
            'sandbox_mode'        => ($settings['dana_sandbox_mode'] ?? '0') === '1',
        ];
    }

    public function isConfigured(): bool
    {
        $cfg = $this->getSettings();
        return !empty($cfg['qris_payload']) || !empty($cfg['qris_image_path']);
    }

    public function isSandbox(): bool
    {
        return $this->getSettings()['sandbox_mode'];
    }

    /**
     * Hitung CRC-16/CCITT-FALSE sesuai spesifikasi EMVCo QRIS Indonesia.
     */
    public static function calculateCrc16(string $payloadWithoutCrcValue): string
    {
        $crc = 0xFFFF;
        $len = strlen($payloadWithoutCrcValue);

        for ($c = 0; $c < $len; $c++) {
            $crc ^= (ord($payloadWithoutCrcValue[$c]) << 8);
            for ($i = 0; $i < 8; $i++) {
                if (($crc & 0x8000) !== 0) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Parse top-level EMVCo TLV tags dari string QRIS.
     *
     * @return array<int, array{tag: string, value: string}>
     */
    public static function parseTlvTags(string $qris): array
    {
        $tags = [];
        $pos = 0;
        $len = strlen($qris);

        while ($pos + 4 <= $len) {
            $tag = substr($qris, $pos, 2);
            $valLenStr = substr($qris, $pos + 2, 2);
            if (!ctype_digit($valLenStr)) {
                break;
            }
            $valLen = (int) $valLenStr;
            $pos += 4;
            if ($pos + $valLen > $len) {
                break;
            }
            $value = substr($qris, $pos, $valLen);
            $pos += $valLen;

            $tags[] = [
                'tag'   => $tag,
                'value' => $value,
            ];
        }

        return $tags;
    }

    /**
     * Konversi string QRIS statis (DANA) menjadi QRIS dinamis dengan nominal persis ($amount Rupiah, min Rp 1).
     */
    public static function buildDynamicQris(string $staticQris, int $amount): string
    {
        $staticQris = trim($staticQris);
        if ($staticQris === '' || $amount < 1) {
            return $staticQris;
        }

        $tags = self::parseTlvTags($staticQris);
        if (empty($tags)) {
            return $staticQris;
        }

        $amountStr = (string) $amount;
        $rebuilt = '';
        $amountInserted = false;

        foreach ($tags as $item) {
            $tag = $item['tag'];
            $val = $item['value'];

            // Lewati CRC lama (tag 63) dan tag 54 lama
            if ($tag === '63' || $tag === '54') {
                continue;
            }

            // Tag 01: ubah '11' (statis) menjadi '12' (dinamis)
            if ($tag === '01') {
                $val = '12';
            }

            // Sisipkan Tag 54 tepat sebelum Tag 58 (Country Code) atau di akhir sebelum 63
            if ($tag === '58' && !$amountInserted) {
                $rebuilt .= '54' . str_pad((string) strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
                $amountInserted = true;
            }

            $rebuilt .= $tag . str_pad((string) strlen($val), 2, '0', STR_PAD_LEFT) . $val;
        }

        if (!$amountInserted) {
            $rebuilt .= '54' . str_pad((string) strlen($amountStr), 2, '0', STR_PAD_LEFT) . $amountStr;
        }

        $forCrc = $rebuilt . '6304';
        return $forCrc . self::calculateCrc16($forCrc);
    }

    /**
     * Hasilkan QR Image Data URI (atau URL gambar QRIS fallback) untuk nominal transaksi tertentu.
     */
    public function getQrisImageForAmount(int $amount): array
    {
        $cfg = $this->getSettings();

        if (!empty($cfg['qris_payload'])) {
            $dynamicPayload = self::buildDynamicQris($cfg['qris_payload'], $amount);
            $dataUri = QrCodeService::generateDataUri($dynamicPayload, 6);

            return [
                'is_dynamic' => true,
                'image_url'  => $dataUri,
                'payload'    => $dynamicPayload,
            ];
        }

        if (!empty($cfg['qris_image_path'])) {
            return [
                'is_dynamic' => false,
                'image_url'  => asset('storage/' . $cfg['qris_image_path']),
                'payload'    => null,
            ];
        }

        return [
            'is_dynamic' => false,
            'image_url'  => null,
            'payload'    => null,
        ];
    }

    /**
     * Ekstrak nominal Rupiah dari teks notifikasi HP aplikasi DANA / DANA Bisnis.
     * Contoh notifikasi DANA:
     * - "Kamu menerima saldo DANA Rp1.000 dari ..."
     * - "Berhasil menerima pembayaran QRIS sebesar Rp 1"
     * - "Pembayaran DANA QRIS Rp50,- berhasil diterima"
     */
    public static function parseAmountFromNotification(string $text): ?int
    {
        $normalized = trim($text);
        if ($normalized === '') {
            return null;
        }

        // Tolak notifikasi pengeluaran / transfer keluar agar tidak salah mengkreditkan vote
        $lower = mb_strtolower($normalized);
        $outgoingKeywords = ['berhasil kirim', 'transfer ke', 'kamu membayar', 'bayar ke', 'beli pulsa', 'tarik saldo'];
        $incomingKeywords = ['menerima', 'diterima', 'saldo masuk', 'pembayaran qris', 'uang masuk', 'berhasil masuk'];

        $looksOutgoing = false;
        foreach ($outgoingKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $looksOutgoing = true;
                break;
            }
        }
        $looksIncoming = false;
        foreach ($incomingKeywords as $kw) {
            if (str_contains($lower, $kw)) {
                $looksIncoming = true;
                break;
            }
        }
        if ($looksOutgoing && !$looksIncoming) {
            return null;
        }

        // Cari pola "Rp 1.000", "Rp1.000,00", "Rp1", "IDR 500"
        if (preg_match('/(?:rp|idr)\.?\s*([0-9][0-9.]*)(?:,[0-9]{1,2})?/i', $normalized, $matches)) {
            $digits = str_replace('.', '', $matches[1]);
            $val = (int) $digits;
            return $val > 0 ? $val : null;
        }

        // Fallback jika isi hanya angka murni
        if (preg_match('/^\s*([0-9]+)\s*$/', $normalized, $matches)) {
            $val = (int) $matches[1];
            return $val > 0 ? $val : null;
        }

        return null;
    }

    /**
     * Cocokkan nominal pembayaran yang benar-benar diterima DANA dengan transaksi PENDING,
     * lalu kreditkan suara secara atomik (hanya jika nominal cocok & belum expired).
     */
    public function matchAndCreditByAmount(int $receivedAmount, array $webhookPayload = [], ?string $orderCode = null): ?PaidVoteTransaction
    {
        if ($receivedAmount < 1) {
            return null;
        }

        return DB::transaction(function () use ($receivedAmount, $webhookPayload, $orderCode) {
            $query = PaidVoteTransaction::where('status', PaidVoteTransaction::STATUS_PENDING)
                ->where(function ($q) {
                    $q->whereNull('expired_at')->orWhere('expired_at', '>', now());
                });

            if (!empty($orderCode)) {
                $query->where('transaction_code', $orderCode);
            } else {
                $query->whereRaw('ROUND(COALESCE(gateway_total_payment, total_amount)) = ?', [$receivedAmount])
                    ->orderBy('created_at', 'asc');
            }

            /** @var PaidVoteTransaction|null $tx */
            $tx = $query->lockForUpdate()->first();

            if (!$tx) {
                Log::info('DANA webhook: no matching pending transaction found', [
                    'amount'     => $receivedAmount,
                    'order_code' => $orderCode,
                ]);
                return null;
            }

            $expectedAmount = (int) round((float) ($tx->gateway_total_payment ?? $tx->total_amount));
            if ($receivedAmount < $expectedAmount) {
                Log::warning('DANA webhook: received amount less than expected', [
                    'tx'       => $tx->transaction_code,
                    'expected' => $expectedAmount,
                    'received' => $receivedAmount,
                ]);
                return null;
            }

            $now = now();
            $tx->update([
                'status'           => PaidVoteTransaction::STATUS_VERIFIED,
                'verified_at'      => $now,
                'rejection_reason' => null,
                'gateway_response' => $webhookPayload,
            ]);

            if ($tx->votes()->count() === 0) {
                for ($i = 0; $i < $tx->votes_count; $i++) {
                    ReguVote::create([
                        'regu_profile_id'          => $tx->regu_profile_id,
                        'user_id'                  => $tx->user_id,
                        'voter_identifier'         => $tx->voter_identifier,
                        'ip_address'               => $tx->ip_address,
                        'user_agent'               => 'DANA QRIS (' . $tx->transaction_code . ')',
                        'voter_name'               => $tx->voter_name,
                        'voted_at'                 => $now,
                        'vote_type'                => ReguVote::TYPE_PAID,
                        'paid_vote_transaction_id' => $tx->id,
                    ]);
                }
            }

            return $tx;
        });
    }
}
