<?php

namespace App\Services;

use App\Models\PaidVoteTransaction;
use App\Models\ReguVote;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PakasirService
{
    private const BASE_URL = 'https://app.pakasir.com/api';

    /**
     * Ambil kredensial Pakasir dari database settings (atau fallback env).
     */
    public function getCredentials(): array
    {
        $settings = Setting::whereIn('key', [
            'pakasir_project_slug',
            'pakasir_api_key',
            'pakasir_sandbox_mode',
        ])->pluck('value', 'key');

        return [
            'project' => trim((string) ($settings['pakasir_project_slug'] ?? env('PAKASIR_PROJECT_SLUG', ''))),
            'api_key' => trim((string) ($settings['pakasir_api_key'] ?? env('PAKASIR_API_KEY', ''))),
            'sandbox' => ($settings['pakasir_sandbox_mode'] ?? env('PAKASIR_SANDBOX', '0')) === '1',
        ];
    }

    public function isConfigured(): bool
    {
        $creds = $this->getCredentials();
        return !empty($creds['project']) && !empty($creds['api_key']);
    }

    public function isSandbox(): bool
    {
        $creds = $this->getCredentials();
        return $creds['sandbox'];
    }

    /**
     * Buat transaksi QRIS dinamis melalui API Pakasir.
     */
    public function createQrisTransaction(string $orderId, int $amount): array
    {
        $creds = $this->getCredentials();
        if (empty($creds['project']) || empty($creds['api_key'])) {
            return [
                'success' => false,
                'message' => 'Kredensial Payment Gateway Pakasir (Project Slug & API Key) belum dikonfigurasi oleh admin.',
            ];
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->post(self::BASE_URL . '/transactioncreate/qris', [
                    'project'  => $creds['project'],
                    'order_id' => $orderId,
                    'amount'   => $amount,
                    'api_key'  => $creds['api_key'],
                ]);

            $data = $response->json();

            if (!$response->successful() || empty($data['payment']['payment_number'])) {
                $errMsg = $data['message'] ?? $data['error'] ?? ('HTTP ' . $response->status());
                Log::warning('Pakasir createQrisTransaction failed', [
                    'order_id' => $orderId,
                    'amount'   => $amount,
                    'status'   => $response->status(),
                    'response' => $data ?? $response->body(),
                ]);

                return [
                    'success' => false,
                    'message' => 'Gagal membuat tagihan QRIS Pakasir: ' . $errMsg,
                ];
            }

            $payment = $data['payment'];

            return [
                'success'        => true,
                'payment_number' => (string) $payment['payment_number'],
                'fee'            => (float) ($payment['fee'] ?? 0),
                'total_payment'  => (float) ($payment['total_payment'] ?? $amount),
                'expired_at'     => $payment['expired_at'] ?? null,
                'raw'            => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Pakasir createQrisTransaction exception', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Tidak dapat terhubung ke server Pakasir saat ini: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cek status transaksi langsung ke API Pakasir (inquiry / double-verification).
     */
    public function getTransactionDetail(string $orderId, int $amount): array
    {
        $creds = $this->getCredentials();
        if (empty($creds['project']) || empty($creds['api_key'])) {
            return ['success' => false, 'status' => 'unknown'];
        }

        try {
            $response = Http::timeout(12)
                ->acceptJson()
                ->get(self::BASE_URL . '/transactiondetail', [
                    'project'  => $creds['project'],
                    'amount'   => $amount,
                    'order_id' => $orderId,
                    'api_key'  => $creds['api_key'],
                ]);

            $data = $response->json();
            if (!$response->successful() || empty($data['transaction'])) {
                return [
                    'success' => false,
                    'status'  => 'pending',
                    'raw'     => $data,
                ];
            }

            $tx = $data['transaction'];

            return [
                'success'      => true,
                'status'       => strtolower((string) ($tx['status'] ?? 'pending')),
                'amount'       => (int) ($tx['amount'] ?? 0),
                'completed_at' => $tx['completed_at'] ?? null,
                'raw'          => $data,
            ];
        } catch (\Throwable $e) {
            Log::error('Pakasir getTransactionDetail exception', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status'  => 'pending',
            ];
        }
    }

    /**
     * Simulasi pembayaran di mode Sandbox Pakasir (khusus pengujian jika sandbox aktif).
     */
    public function simulateSandboxPayment(string $orderId, int $amount): array
    {
        $creds = $this->getCredentials();
        if (!$creds['sandbox']) {
            return [
                'success' => false,
                'message' => 'Simulasi hanya tersedia ketika Mode Sandbox Pakasir diaktifkan di menu Admin.',
            ];
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->post(self::BASE_URL . '/paymentsimulation', [
                    'project'  => $creds['project'],
                    'order_id' => $orderId,
                    'amount'   => $amount,
                    'api_key'  => $creds['api_key'],
                ]);

            return [
                'success' => $response->successful(),
                'raw'     => $response->json(),
                'message' => $response->successful()
                    ? 'Simulasi pembayaran dikirim ke server Pakasir.'
                    : ('Gagal simulasi: ' . ($response->json('message') ?? $response->status())),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghubungi endpoint simulasi Pakasir: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Verifikasi atomik & masukkan suara ke regu HANYA jika pembayaran benar-benar lunas.
     * Menggunakan DB transaction + row lock agar idempoten dan bebas race condition (webhook vs polling).
     */
    public function verifyAndCreditTransaction(PaidVoteTransaction $transaction, array $gatewayData = []): bool
    {
        return DB::transaction(function () use ($transaction, $gatewayData) {
            /** @var PaidVoteTransaction|null $lockedTx */
            $lockedTx = PaidVoteTransaction::where('id', $transaction->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedTx || $lockedTx->isVerified()) {
                return false;
            }

            $now = now();
            $lockedTx->update([
                'status'           => PaidVoteTransaction::STATUS_VERIFIED,
                'verified_at'      => $now,
                'rejection_reason' => null,
                'gateway_response' => !empty($gatewayData) ? $gatewayData : $lockedTx->gateway_response,
            ]);

            // Pastikan tidak ada duplikasi suara untuk transaksi ini
            if ($lockedTx->votes()->count() === 0) {
                for ($i = 0; $i < $lockedTx->votes_count; $i++) {
                    ReguVote::create([
                        'regu_profile_id'          => $lockedTx->regu_profile_id,
                        'user_id'                  => $lockedTx->user_id,
                        'voter_identifier'         => $lockedTx->voter_identifier,
                        'ip_address'               => $lockedTx->ip_address,
                        'user_agent'               => 'Pakasir QRIS (' . $lockedTx->transaction_code . ')',
                        'voter_name'               => $lockedTx->voter_name,
                        'voted_at'                 => $now,
                        'vote_type'                => ReguVote::TYPE_PAID,
                        'paid_vote_transaction_id' => $lockedTx->id,
                    ]);
                }
            }

            return true;
        });
    }
}
