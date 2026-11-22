<?php

namespace App\Http\Controllers;

use App\Models\CerdasCermatSession;
use App\Models\MataLomba;
use App\Models\PaidVoteTransaction;
use App\Models\ReguProfile;
use App\Models\ReguVote;
use App\Models\Score;
use App\Models\Setting;
use App\Services\DanaQrisService;
use App\Services\QrCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LiveDisplayController extends Controller
{
    /**
     * Tampilan Layar Panggung Proyektor (Public Live Display) 16:9 & Mobile Voting.
     */
    public function index(Request $request): View
    {
        $voterId = $this->getVoterIdentifier($request);
        $data = $this->getLeaderboardData($voterId);

        return view('live-display', $data);
    }

    /**
     * Endpoint API JSON untuk polling / live update.
     */
    public function getData(Request $request): JsonResponse
    {
        $voterId = $this->getVoterIdentifier($request);
        $data = $this->getLeaderboardData($voterId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Hitung waktu awal periode voting gratis berdasarkan pengaturan admin (reset harian/interval/manual).
     */
    public function getFreeVotePeriodStart(): \Carbon\Carbon
    {
        $resetMode = Setting::where('key', 'free_vote_reset_mode')->value('value') ?? 'daily';
        $intervalHours = (int) (Setting::where('key', 'free_vote_reset_interval_hours')->value('value') ?? 24);
        $manualResetAt = Setting::where('key', 'free_vote_last_reset_at')->value('value');
        $manualResetTime = $manualResetAt ? \Carbon\Carbon::parse($manualResetAt) : null;

        if ($resetMode === 'daily') {
            // Reset setiap tengah malam (pukul 00:00:00 WITA)
            $periodStart = now()->startOfDay();
        } elseif ($resetMode === 'interval') {
            $periodStart = now()->subHours($intervalHours);
        } else {
            // 'once' (hanya 1x selama masa lomba)
            $periodStart = \Carbon\Carbon::createFromTimestamp(0);
        }

        // Jika admin melakukan reset manual lebih baru dari batas periode, gunakan waktu reset manual
        if ($manualResetTime && $manualResetTime->greaterThan($periodStart)) {
            $periodStart = $manualResetTime;
        }

        return $periodStart;
    }

    /**
     * Ambil data vote gratis terakhir untuk voter dalam periode saat ini.
     */
    public function getRecentFreeVote(string $voterId, ?int $userId = null): ?ReguVote
    {
        $periodStart = $this->getFreeVotePeriodStart();
        $manualResetAt = Setting::where('key', 'free_vote_last_reset_at')->value('value');
        $manualResetTime = $manualResetAt ? \Carbon\Carbon::parse($manualResetAt) : null;

        $query = ReguVote::where('vote_type', ReguVote::TYPE_FREE)
            ->where(function ($q) use ($voterId, $userId) {
                $q->where('voter_identifier', $voterId);
                if ($userId) {
                    $q->orWhere('user_id', $userId);
                }
            });

        // Jika reset manual dilakukan lebih baru dari batas awal periode harian/interval,
        // suara hanya diblokir jika voter sudah memberikan suara SETELAH reset manual tersebut
        if ($manualResetTime && $manualResetTime->greaterThanOrEqualTo($periodStart)) {
            $query->where('voted_at', '>', $manualResetTime);
        } else {
            $query->where('voted_at', '>=', $periodStart);
        }

        return $query->latest('voted_at')->first();
    }

    /**
     * Endpoint Vote Regu Terfavorit Gratis (1 suara per hari / per periode reset)
     */
    public function vote(Request $request, $reguId): JsonResponse
    {
        // Validasi nama voter
        $request->validate([
            'voter_name' => 'required|string|min:2|max:100',
        ]);

        $voterName = trim(preg_replace('/\s+/', ' ', $request->input('voter_name')));

        $voterId   = $this->getVoterIdentifier($request);
        $ip        = $request->ip();
        $userAgent = $request->header('User-Agent');
        $userId    = auth()->id();
        $now       = now();

        // ── Cek apakah penerimaan vote baru sedang ditutup oleh admin ────────
        $votingLockedSetting = Setting::where('key', 'voting_locked')->value('value') ?? '0';
        $votingEndAtSetting  = Setting::where('key', 'voting_end_at')->value('value');

        $isClosed = false;
        $closedReason = 'Voting regu terfavorit telah resmi ditutup oleh panitia.';
        if ($votingLockedSetting === '1') {
            $isClosed = true;
        } elseif (!empty($votingEndAtSetting)) {
            $endTime = \Carbon\Carbon::parse($votingEndAtSetting);
            if ($now->greaterThanOrEqualTo($endTime)) {
                $isClosed = true;
                $closedReason = 'Voting regu terfavorit telah berakhir pada ' . $endTime->translatedFormat('d F Y, H:i') . ' WITA.';
            }
        }

        if ($isClosed) {
            return response()->json([
                'success' => false,
                'message' => $closedReason,
            ], 403);
        }

        // ── Cek pembatasan voting gratis sesuai pengaturan admin ─────────────
        $recentFreeVote = $this->getRecentFreeVote($voterId, $userId);
        $paidVotingEnabled = (Setting::where('key', 'paid_voting_enabled')->value('value') ?? '1') === '1';

        if ($recentFreeVote) {
            $resetMode = Setting::where('key', 'free_vote_reset_mode')->value('value') ?? 'daily';
            $intervalHours = (int) (Setting::where('key', 'free_vote_reset_interval_hours')->value('value') ?? 24);

            if ($resetMode === 'daily') {
                $nextAllowedMsg = 'Voting gratis berikutnya dapat dilakukan besok mulai pukul 00:00 WITA.';
                $nextAllowed = now()->endOfDay()->addSecond();
            } elseif ($resetMode === 'interval') {
                $nextAllowed = $recentFreeVote->voted_at->copy()->addHours($intervalHours);
                $sisaWaktu = $now->diffInMinutes($nextAllowed, false);
                if ($sisaWaktu > 0) {
                    $jam = intdiv($sisaWaktu, 60);
                    $menit = $sisaWaktu % 60;
                    $sisaStr = $jam > 0 ? "{$jam} jam {$menit} menit" : "{$menit} menit";
                    $nextAllowedMsg = "Voting gratis berikutnya dapat dilakukan dalam {$sisaStr} lagi.";
                } else {
                    $nextAllowedMsg = "Voting gratis dapat dilakukan sekarang.";
                }
            } else {
                $nextAllowedMsg = 'Voting gratis hanya dapat digunakan 1 kali selama kegiatan.';
                $nextAllowed = null;
            }

            $votedRegu = ReguProfile::find($recentFreeVote->regu_profile_id);
            $msg = "Anda sudah menggunakan kuota suara gratis hari ini untuk Regu {$votedRegu?->nama_regu}. {$nextAllowedMsg}";
            if ($paidVotingEnabled) {
                $msg .= " Anda tetap dapat memberikan dukungan ekstra melalui opsi Voting Berbayar!";
            }

            return response()->json([
                'success'             => false,
                'has_voted_free'      => true,
                'message'             => $msg,
                'voted_regu_id'       => $recentFreeVote->regu_profile_id,
                'paid_voting_enabled' => $paidVotingEnabled,
                'next_allowed_at'     => $nextAllowed ? $nextAllowed->toIso8601String() : null,
            ], 422);
        }

        $regu = ($reguId instanceof ReguProfile) ? $reguId : ReguProfile::findOrFail($reguId);

        // Simpan suara gratis
        $newVote = ReguVote::create([
            'regu_profile_id'  => $regu->id,
            'user_id'          => $userId,
            'voter_identifier' => $voterId,
            'ip_address'       => $ip,
            'user_agent'       => $userAgent,
            'voter_name'       => $voterName,
            'voted_at'         => $now,
            'vote_type'        => ReguVote::TYPE_FREE,
        ]);

        $resetMode = Setting::where('key', 'free_vote_reset_mode')->value('value') ?? 'daily';
        $intervalHours = (int) (Setting::where('key', 'free_vote_reset_interval_hours')->value('value') ?? 24);
        $nextFreeVoteAt = $this->calculateNextFreeVoteAt($newVote, $resetMode, $intervalHours);

        $newVoteCount = ReguVote::where('regu_profile_id', $regu->id)->count();

        // Cek status kunci jumlah suara
        $countsLockedSetting = Setting::where('key', 'voting_counts_locked')->value('value') ?? '0';
        $countsUnlockAtSetting = Setting::where('key', 'voting_counts_unlock_at')->value('value');
        $isVoteCountLocked = false;
        if ($countsLockedSetting === '1') {
            if (!empty($countsUnlockAtSetting)) {
                $unlockTime = \Carbon\Carbon::parse($countsUnlockAtSetting);
                $isVoteCountLocked = $now->lessThan($unlockTime);
            } else {
                $isVoteCountLocked = true;
            }
        }

        $cookie = cookie('spencer_voter_id', $voterId, 60 * 24 * 30);

        return response()->json([
            'success'                        => true,
            'message'                        => "Terima kasih, {$voterName}! Suara gratis Anda berhasil tercatat untuk Regu {$regu->nama_regu}. 🎉",
            'regu_id'                        => $regu->id,
            'new_votes_count'                => $isVoteCountLocked ? '🔒' : $newVoteCount,
            'voted_regu_id'                  => $regu->id,
            'voter_name'                     => $voterName,
            'is_vote_count_locked'           => $isVoteCountLocked,
            'next_allowed_at'                => $nextFreeVoteAt ? $nextFreeVoteAt->toIso8601String() : null,
            'free_vote_reset_mode'           => $resetMode,
            'free_vote_reset_interval_hours' => $intervalHours,
        ])->withCookie($cookie);
    }

    /**
     * Hitung waktu kapan voter dapat melakukan vote gratis berikutnya sesuai pengaturan admin.
     */
    private function calculateNextFreeVoteAt(ReguVote $vote, string $resetMode, int $intervalHours = 24): ?\Carbon\Carbon
    {
        if ($resetMode === 'daily') {
            return now()->endOfDay()->addSecond();
        }
        if ($resetMode === 'interval') {
            return $vote->voted_at->copy()->addHours(max(1, $intervalHours));
        }

        return null; // 'once'
    }

    /**
     * Cek apakah jumlah suara saat ini sedang dikunci/dirahasiakan untuk publik.
     */
    private function isVoteCountCurrentlyLocked(): array
    {
        $countsLockedSetting   = Setting::where('key', 'voting_counts_locked')->value('value') ?? '0';
        $countsUnlockAtSetting = Setting::where('key', 'voting_counts_unlock_at')->value('value');
        $now = now();

        $isLocked = false;
        $unlockAtFormatted = null;

        if ($countsLockedSetting === '1') {
            if (!empty($countsUnlockAtSetting)) {
                $unlockTime = \Carbon\Carbon::parse($countsUnlockAtSetting);
                if ($now->lessThan($unlockTime)) {
                    $isLocked = true;
                    $unlockAtFormatted = $unlockTime->translatedFormat('d F Y, H:i') . ' WITA';
                }
            } else {
                $isLocked = true;
            }
        }

        return [
            'locked'    => $isLocked,
            'unlock_at' => $unlockAtFormatted,
        ];
    }

    /**
     * Endpoint Voting Berbayar DANA QRIS (Minimal 1 Vote = Rp 1, 0% Potongan)
     * Membuat tagihan berstatus PENDING. Suara TIDAK akan masuk sebelum pembayaran DANA
     * benar-benar sukses dan diterima (melalui Webhook Notifikasi DANA).
     */
    public function paidVote(Request $request, $reguId, DanaQrisService $danaService): JsonResponse
    {
        $paidVotingEnabled = (Setting::where('key', 'paid_voting_enabled')->value('value') ?? '1') === '1';
        if (!$paidVotingEnabled) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur voting berbayar DANA saat ini sedang dinonaktifkan oleh panitia.',
            ], 403);
        }

        // Cek penutupan voting
        $votingLockedSetting = Setting::where('key', 'voting_locked')->value('value') ?? '0';
        $votingEndAtSetting  = Setting::where('key', 'voting_end_at')->value('value');
        $now = now();

        if ($votingLockedSetting === '1' || (!empty($votingEndAtSetting) && $now->greaterThanOrEqualTo(\Carbon\Carbon::parse($votingEndAtSetting)))) {
            return response()->json([
                'success' => false,
                'message' => 'Voting regu terfavorit telah resmi ditutup oleh panitia.',
            ], 403);
        }

        if (!$danaService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'QRIS DANA belum diatur oleh admin. Silakan upload QRIS DANA di menu Admin Voting.',
            ], 422);
        }

        $minVotes  = (int) (Setting::where('key', 'paid_vote_min')->value('value') ?? 1);
        $unitPrice = max(1, (int) round((float) (Setting::where('key', 'paid_vote_price')->value('value') ?? 1)));

        $request->validate([
            'voter_name'  => 'required|string|min:2|max:100',
            'votes_count' => "required|integer|min:{$minVotes}|max:10000",
        ], [
            'votes_count.min' => "Minimal pembelian voting adalah {$minVotes} suara.",
        ]);

        $regu        = ($reguId instanceof ReguProfile) ? $reguId : ReguProfile::findOrFail($reguId);
        $voterName   = trim(preg_replace('/\s+/', ' ', $request->input('voter_name')));
        $votesCount  = (int) $request->input('votes_count');
        $totalAmount = $votesCount * $unitPrice; // Bisa mulai dari Rp 1!

        $danaCfg = $danaService->getSettings();
        $uniqueCode = 0;
        $totalPayment = $totalAmount;

        // Tambahkan kode unik opsional (1-99) hanya jika diaktifkan admin
        if ($danaCfg['unique_code_enabled']) {
            for ($attempt = 0; $attempt < 15; $attempt++) {
                $candidateCode = random_int(1, 99);
                $candidateTotal = $totalAmount + $candidateCode;
                $exists = PaidVoteTransaction::where('status', PaidVoteTransaction::STATUS_PENDING)
                    ->whereRaw('ROUND(COALESCE(gateway_total_payment, total_amount)) = ?', [$candidateTotal])
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expired_at')->orWhere('expired_at', '>', $now);
                    })
                    ->exists();
                if (!$exists) {
                    $uniqueCode = $candidateCode;
                    $totalPayment = $candidateTotal;
                    break;
                }
            }
        }

        $qrisDisplay = $danaService->getQrisImageForAmount($totalPayment);
        if (empty($qrisDisplay['image_url'])) {
            return response()->json([
                'success' => false,
                'message' => 'Gambar / kode QRIS DANA tidak tersedia.',
            ], 422);
        }

        $voterId = $this->getVoterIdentifier($request);
        $ip      = $request->ip();
        $userId  = auth()->id();
        $txCode  = 'DANA-' . date('YmdHis') . '-' . strtoupper(Str::random(4));
        $expiredAt = $now->copy()->addMinutes(15);

        // Simpan transaksi berstatus PENDING (0 suara masuk sebelum dana diterima DANA!)
        PaidVoteTransaction::create([
            'transaction_code'      => $txCode,
            'regu_profile_id'       => $regu->id,
            'user_id'               => $userId,
            'voter_name'            => $voterName,
            'voter_identifier'      => $voterId,
            'ip_address'            => $ip,
            'votes_count'           => $votesCount,
            'unit_price'            => $unitPrice,
            'total_amount'          => $totalAmount,
            'payment_method'        => 'DANA QRIS',
            'qris_payload'          => $qrisDisplay['payload'],
            'gateway_fee'           => $uniqueCode,
            'gateway_total_payment' => $totalPayment,
            'expired_at'            => $expiredAt,
            'status'                => PaidVoteTransaction::STATUS_PENDING,
        ]);

        $cookie = cookie('spencer_voter_id', $voterId, 60 * 24 * 30);

        return response()->json([
            'success'           => true,
            'status'            => PaidVoteTransaction::STATUS_PENDING,
            'message'           => 'Silakan scan QRIS menggunakan aplikasi DANA Anda. Suara otomatis masuk setelah dana diterima DANA.',
            'transaction_code'  => $txCode,
            'regu_id'           => $regu->id,
            'regu_name'         => $regu->nama_regu,
            'votes_count'       => $votesCount,
            'amount'            => $totalAmount,
            'unique_code'       => $uniqueCode,
            'total_payment'     => $totalPayment,
            'qris_image'        => $qrisDisplay['image_url'],
            'is_dynamic'        => $qrisDisplay['is_dynamic'],
            'dana_account_name' => $danaCfg['account_name'],
            'expired_at'        => $expiredAt->toIso8601String(),
            'is_sandbox'        => $danaCfg['sandbox_mode'],
        ])->withCookie($cookie);
    }

    /**
     * Cek status pembayaran DANA secara real-time (polling dari browser).
     * Suara HANYA terhitung jika webhook DANA (atau verifikasi admin) telah menandai transaksi sebagai VERIFIED.
     */
    public function checkPaidVoteStatus(string $transactionCode): JsonResponse
    {
        $tx = PaidVoteTransaction::with('reguProfile')
            ->where('transaction_code', $transactionCode)
            ->firstOrFail();

        if ($tx->isPending() && $tx->expired_at && now()->greaterThan($tx->expired_at)) {
            $tx->update([
                'status'           => PaidVoteTransaction::STATUS_REJECTED,
                'rejection_reason' => 'Waktu pembayaran QRIS DANA telah habis (kedaluwarsa).',
            ]);
            $tx->refresh();
        }

        $lockInfo     = $this->isVoteCountCurrentlyLocked();
        $newVoteCount = ReguVote::where('regu_profile_id', $tx->regu_profile_id)->count();
        $reguName     = $tx->reguProfile?->nama_regu ?? 'Regu Pilihan';

        if ($tx->isVerified()) {
            return response()->json([
                'success'              => true,
                'status'               => 'verified',
                'message'              => "Pembayaran DANA diterima! {$tx->votes_count} suara murni berhasil ditambahkan ke {$reguName}! 🎉",
                'transaction_code'     => $tx->transaction_code,
                'regu_id'              => $tx->regu_profile_id,
                'votes_added'          => $tx->votes_count,
                'new_votes_count'      => $lockInfo['locked'] ? '🔒' : $newVoteCount,
                'is_vote_count_locked' => $lockInfo['locked'],
            ]);
        }

        if ($tx->isRejected()) {
            return response()->json([
                'success' => false,
                'status'  => 'rejected',
                'message' => $tx->rejection_reason ?? 'Transaksi pembayaran DANA kedaluwarsa atau dibatalkan.',
            ]);
        }

        return response()->json([
            'success' => true,
            'status'  => 'pending',
            'message' => 'Menunggu konfirmasi dana masuk dari aplikasi DANA...',
        ]);
    }

    /**
     * Endpoint Webhook Notifikasi Otomatis dari Aplikasi DANA (via MacroDroid / Tasker / NotifForwarder).
     * Mengautentikasi secret key, membaca nominal Rupiah yang masuk, mencocokkan dengan transaksi PENDING,
     * dan langsung mengkreditkan suara ke regu secara atomik.
     */
    public function danaWebhook(Request $request, DanaQrisService $danaService): JsonResponse
    {
        $cfg = $danaService->getSettings();
        $providedSecret = (string) ($request->header('X-Webhook-Secret')
            ?? $request->input('secret')
            ?? $request->query('secret', ''));

        if ($providedSecret === '' || !hash_equals($cfg['webhook_secret'], $providedSecret)) {
            Log::warning('DANA webhook unauthorized attempt', ['ip' => $request->ip()]);
            return response()->json(['success' => false, 'message' => 'Secret webhook tidak valid.'], 403);
        }

        $rawText = (string) ($request->input('message')
            ?? $request->input('text')
            ?? $request->input('notification')
            ?? $request->input('body')
            ?? $request->input('title')
            ?? '');

        $amount = null;
        if ($request->filled('amount') && is_numeric($request->input('amount'))) {
            $amount = (int) round((float) $request->input('amount'));
        } else {
            $amount = DanaQrisService::parseAmountFromNotification($rawText);
        }

        Log::info('DANA webhook received', [
            'amount'   => $amount,
            'raw_text' => $rawText,
            'order_id' => $request->input('order_id'),
        ]);

        if (!$amount || $amount < 1) {
            return response()->json([
                'success' => true,
                'matched' => false,
                'message' => 'Notifikasi diabaikan (bukan notifikasi saldo masuk).',
            ]);
        }

        $orderCode = $request->filled('order_id') ? (string) $request->input('order_id') : null;
        $tx = $danaService->matchAndCreditByAmount($amount, [
            'received_at' => now()->toIso8601String(),
            'amount'      => $amount,
            'raw_text'    => $rawText,
            'source'      => 'dana_webhook',
        ], $orderCode);

        if (!$tx) {
            return response()->json([
                'success' => true,
                'matched' => false,
                'message' => "Dana masuk Rp {$amount} diterima, tetapi tidak ada transaksi DANA pending yang cocok.",
            ]);
        }

        return response()->json([
            'success'          => true,
            'matched'          => true,
            'transaction_code' => $tx->transaction_code,
            'votes_credited'   => $tx->votes_count,
            'message'          => "Pembayaran DANA Rp {$amount} cocok dengan {$tx->transaction_code}. {$tx->votes_count} suara berhasil dikreditkan.",
        ]);
    }

    /**
     * Simulasi terima notifikasi DANA (HANYA aktif ketika Mode Sandbox diaktifkan oleh Admin).
     */
    public function simulateDanaSandbox(string $transactionCode, DanaQrisService $danaService): JsonResponse
    {
        if (!$danaService->isSandbox()) {
            return response()->json([
                'success' => false,
                'message' => 'Simulasi dinonaktifkan pada mode produksi.',
            ], 403);
        }

        $tx = PaidVoteTransaction::where('transaction_code', $transactionCode)->firstOrFail();
        if ($tx->isPending()) {
            $expectedAmount = (int) round((float) ($tx->gateway_total_payment ?? $tx->total_amount));
            $simulatedNotif = "Kamu menerima pembayaran QRIS DANA sebesar Rp" . number_format($expectedAmount, 0, ',', '.');
            $parsedAmount = DanaQrisService::parseAmountFromNotification($simulatedNotif);

            if ($parsedAmount) {
                $danaService->matchAndCreditByAmount($parsedAmount, [
                    'source'   => 'sandbox_simulation',
                    'raw_text' => $simulatedNotif,
                ], $tx->transaction_code);
            }
        }

        return $this->checkPaidVoteStatus($transactionCode);
    }

    /**
     * Generate atau ambil identifier unik voter (gabungan cookie & fingerprint IP).
     */
    public function getVoterIdentifier(Request $request): string
    {
        $cookieToken = $request->cookie('spencer_voter_id');
        if ($cookieToken && strlen($cookieToken) >= 16) {
            return $cookieToken;
        }

        // Jika belum ada cookie, buat token unik
        $token = hash('sha256', Str::random(32) . '|' . $request->ip() . '|' . $request->header('User-Agent'));
        return $token;
    }

    /**
     * Hitung perolehan medali dan poin juara umum terpisah Putra & Putri serta data voting favorit.
     */
    public function getLeaderboardData(?string $voterId = null): array
    {
        $allMataLomba = MataLomba::where('is_scored', true)->orderBy('urutan')->get();
        $allRegus = ReguProfile::with(['user', 'votes'])->withCount('votes')->get();

        // Cari tahu apakah voter saat ini sudah vote gratis dalam periode saat ini
        $freeVoteResetMode = Setting::where('key', 'free_vote_reset_mode')->value('value') ?? 'daily';
        $freeVoteResetIntervalHours = (int) (Setting::where('key', 'free_vote_reset_interval_hours')->value('value') ?? 24);
        $hasVoted = false;
        $votedReguId = null;
        $nextFreeVoteAt = null;
        if ($voterId) {
            $userVote = $this->getRecentFreeVote($voterId, auth()->id());
            if ($userVote) {
                $hasVoted = true;
                $votedReguId = $userVote->regu_profile_id;
                $nextFreeVoteAt = $this->calculateNextFreeVoteAt($userVote, $freeVoteResetMode, $freeVoteResetIntervalHours);
            }
        }

        // ── Cek status kunci jumlah vote untuk publik ───────────────────────
        $countsLockedSetting   = Setting::where('key', 'voting_counts_locked')->value('value') ?? '0';
        $countsUnlockAtSetting = Setting::where('key', 'voting_counts_unlock_at')->value('value');
        $now = now();

        $isVoteCountLocked = false;
        $unlockAtFormatted = null;
        if ($countsLockedSetting === '1') {
            if (!empty($countsUnlockAtSetting)) {
                $unlockTime = \Carbon\Carbon::parse($countsUnlockAtSetting);
                if ($now->lessThan($unlockTime)) {
                    $isVoteCountLocked = true;
                    $unlockAtFormatted = $unlockTime->translatedFormat('d F Y, H:i') . ' WITA';
                }
            } else {
                $isVoteCountLocked = true;
            }
        }

        // ── Cek status penerimaan suara baru ─────────────────────────────────
        $votingLockedSetting = Setting::where('key', 'voting_locked')->value('value') ?? '0';
        $votingEndAtSetting  = Setting::where('key', 'voting_end_at')->value('value');
        $isVotingClosed      = false;
        $votingEndAtFormatted = null;
        if ($votingLockedSetting === '1') {
            $isVotingClosed = true;
        } elseif (!empty($votingEndAtSetting)) {
            $endTime = \Carbon\Carbon::parse($votingEndAtSetting);
            if ($now->greaterThanOrEqualTo($endTime)) {
                $isVotingClosed = true;
                $votingEndAtFormatted = $endTime->translatedFormat('d F Y, H:i') . ' WITA';
            }
        }

        // Data Pengaturan Voting Berbayar DANA QRIS & Reset
        $paidVotingEnabled = (Setting::where('key', 'paid_voting_enabled')->value('value') ?? '1') === '1';
        $paidVotePrice = max(1, (int) round((float) (Setting::where('key', 'paid_vote_price')->value('value') ?? 1)));
        $paidVoteMin = max(1, (int) (Setting::where('key', 'paid_vote_min')->value('value') ?? 1));
        $paidVoteInstructions = Setting::where('key', 'paid_vote_instructions')->value('value') ?: 'Scan QRIS menggunakan aplikasi DANA Anda. Suara otomatis terhitung begitu pembayaran berhasil diterima DANA.';

        // Data Regu untuk Voting Terfavorit
        $votingRegus = $allRegus->map(function ($regu) use ($votedReguId, $isVoteCountLocked) {
            $shareUrl = route('live-display', ['tab' => 'voting', 'vote_regu' => $regu->id]);
            $shareQr = cache()->remember(
                'regu_share_qr_' . $regu->id . '_' . md5($shareUrl),
                3600,
                fn() => QrCodeService::generateDataUri($shareUrl, 5)
            );

            return [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
                'nomor_kavling' => $regu->nomor_kavling ?? 'KAV-01',
                'jenis' => $regu->jenis,
                'foto_regu_path' => $regu->foto_regu_path,
                'foto_regu_caption' => $regu->foto_regu_caption,
                'votes_count' => $isVoteCountLocked ? '🔒' : ($regu->votes_count ?? 0),
                'is_my_vote' => ($votedReguId === $regu->id),
                'share_url' => $shareUrl,
                'share_qr' => $shareQr,
            ];
        })->sort(function ($a, $b) use ($isVoteCountLocked) {
            if ($isVoteCountLocked) {
                // Jangan bocorkan urutan pemenang saat terkunci, urutkan nomor regu
                return $a['nomor_regu'] <=> $b['nomor_regu'];
            }
            if ($a['votes_count'] !== $b['votes_count']) {
                return $b['votes_count'] <=> $a['votes_count'];
            }
            return $a['nomor_regu'] <=> $b['nomor_regu'];
        })->values()->all();

        $totalVotesCount = $isVoteCountLocked ? '🔒 Dirahasiakan' : ReguVote::count();

        $juaraUmumPutra = [];
        $juaraUmumPutri = [];

        foreach ($allRegus as $regu) {
            $entry = [
                'id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'nomor_regu' => $regu->nomor_regu,
                'jenis' => $regu->jenis,
                'sekolah' => $regu->sekolah ?? 'Pangkalan Terdaftar',
                'emas' => 0,
                'perak' => 0,
                'perunggu' => 0,
                'total_poin' => 0,
            ];

            if ($regu->jenis === 'putra') {
                $juaraUmumPutra[$regu->id] = $entry;
            } else {
                $juaraUmumPutri[$regu->id] = $entry;
            }
        }

        // Hitung medali per mata lomba
        foreach ($allMataLomba as $lomba) {
            $scores = Score::where('mata_lomba_id', $lomba->id)
                ->where('status', Score::STATUS_SUBMITTED)
                ->with('reguProfile')
                ->get();

            $scoresPutra = $scores->filter(fn($s) => $s->reguProfile && $s->reguProfile->jenis === 'putra')->sortByDesc('nilai')->values();
            $scoresPutri = $scores->filter(fn($s) => $s->reguProfile && $s->reguProfile->jenis === 'putri')->sortByDesc('nilai')->values();

            $this->assignMedals($scoresPutra, $juaraUmumPutra);
            $this->assignMedals($scoresPutri, $juaraUmumPutri);
        }

        $sortLeaderboard = function (&$list) {
            uasort($list, function ($a, $b) {
                if ($a['total_poin'] !== $b['total_poin']) return $b['total_poin'] <=> $a['total_poin'];
                if ($a['emas'] !== $b['emas']) return $b['emas'] <=> $a['emas'];
                if ($a['perak'] !== $b['perak']) return $b['perak'] <=> $a['perak'];
                return $b['perunggu'] <=> $a['perunggu'];
            });
        };

        $sortLeaderboard($juaraUmumPutra);
        $sortLeaderboard($juaraUmumPutri);

        // Gabung jika mode gabung aktif
        $isCombinedGender = Setting::where('key', 'combine_gender_leaderboard')->value('value') === '1';
        $juaraUmumAll = [];
        if ($isCombinedGender) {
            foreach (array_merge($juaraUmumPutra, $juaraUmumPutri) as $r) {
                $id = $r['id'];
                if (!isset($juaraUmumAll[$id])) {
                    $juaraUmumAll[$id] = $r;
                } else {
                    $juaraUmumAll[$id]['emas'] += $r['emas'];
                    $juaraUmumAll[$id]['perak'] += $r['perak'];
                    $juaraUmumAll[$id]['perunggu'] += $r['perunggu'];
                    $juaraUmumAll[$id]['total_poin'] += $r['total_poin'];
                }
            }
            $sortLeaderboard($juaraUmumAll);
        }

        // Leaderboard Cerdas Cermat + Coin Royale State
        $cyberQuizService = app(\App\Services\CyberQuizService::class);
        $coinRoyaleState = $cyberQuizService->getCoinRoyaleState(false);
        $quizRoundStatuses = $cyberQuizService->getRoundStatuses();

        $ccSessions = CerdasCermatSession::with('reguProfile')
            ->whereNotNull('regu_id')
            ->get();
        $quizLeaderboard = [];
        foreach ($ccSessions as $cs) {
            $b1 = (float) ($cs->score_round_1 ?? 0);
            $b2 = (float) ($cs->score_round_2 ?? 0);
            $b3 = (float) ($cs->score_round_3 ?? 0);
            $total = $b1 + $b2 + $b3;
            $quizLeaderboard[] = [
                'session_id' => $cs->id,
                'regu_id' => $cs->regu_id,
                'regu_name' => $cs->reguProfile?->nama_regu ?? 'Regu #' . $cs->regu_id,
                'nomor_regu' => $cs->reguProfile?->nomor_regu ?? '-',
                'jenis' => $cs->reguProfile?->jenis ?? 'campuran',
                'b1' => $b1,
                'b2' => $b2,
                'b3' => $b3,
                'coin_initial' => (int) ($cs->coin_initial ?? 0),
                'coin_balance' => (int) ($cs->coin_balance ?? 0),
                'is_finalist' => (bool) $cs->is_verified_round_3,
                'total' => $cs->is_verified_round_3 ? (int) ($cs->coin_balance ?? $total) : $total,
                'is_locked' => (bool) $cs->is_locked,
            ];
        }
        usort($quizLeaderboard, fn($a, $b) => $b['total'] <=> $a['total']);

        $isRevealed = Setting::where('key', 'reveal_juara_umum')->value('value') === '1';

        $danaService = app(DanaQrisService::class);
        $danaCfg     = $danaService->getSettings();

        return [
            'isCombinedGender'        => $isCombinedGender,
            'juaraUmumAll'            => array_values($juaraUmumAll),
            'juaraUmumPutra'          => array_values($juaraUmumPutra),
            'juaraUmumPutri'          => array_values($juaraUmumPutri),
            'allMataLomba'            => $allMataLomba,
            'quizLeaderboard'         => $quizLeaderboard,
            'coinRoyaleState'         => $coinRoyaleState,
            'quizRoundStatuses'       => $quizRoundStatuses,
            'isRevealed'              => $isRevealed,
            'votingRegus'             => $votingRegus,
            'hasVoted'                => $hasVoted,
            'votedReguId'             => $votedReguId,
            'totalVotesCount'         => $totalVotesCount,
            'is_vote_count_locked'    => $isVoteCountLocked,
            'unlock_at_formatted'     => $unlockAtFormatted,
            'is_voting_closed'        => $isVotingClosed,
            'voting_end_at_formatted' => $votingEndAtFormatted,
            'paid_voting_enabled'     => $paidVotingEnabled,
            'paid_vote_price'         => $paidVotePrice,
            'paid_vote_min'           => $paidVoteMin,
            'paid_vote_instructions'         => $paidVoteInstructions,
            'free_vote_reset_mode'           => $freeVoteResetMode,
            'free_vote_reset_interval_hours' => $freeVoteResetIntervalHours,
            'next_free_vote_at'              => $nextFreeVoteAt ? $nextFreeVoteAt->toIso8601String() : null,
            'dana_configured'                => $danaService->isConfigured(),
            'dana_sandbox'            => $danaCfg['sandbox_mode'],
            'dana_account_name'       => $danaCfg['account_name'],
            'dana_unique_code'        => $danaCfg['unique_code_enabled'],
        ];
    }

    private function assignMedals($sortedScores, &$juaraUmum)
    {
        $points = [1 => 5, 2 => 3, 3 => 1];
        $keys = [1 => 'emas', 2 => 'perak', 3 => 'perunggu'];

        foreach ($sortedScores as $index => $score) {
            $rank = $index + 1;
            if ($rank > 3) break;

            $reguId = $score->regu_profile_id;
            if (isset($juaraUmum[$reguId])) {
                $juaraUmum[$reguId][$keys[$rank]] += 1;
                $juaraUmum[$reguId]['total_poin'] += $points[$rank];
            }
        }
    }
}
