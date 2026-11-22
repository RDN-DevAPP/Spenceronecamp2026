<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaidVoteTransaction;
use App\Models\ReguProfile;
use App\Models\ReguVote;
use App\Models\Setting;
use App\Services\DanaQrisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminVotingController extends Controller
{
    /**
     * Tampilkan data voting, transaksi berbayar, ringkasan per regu, & pengaturan voting.
     */
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status'); // all, pending, verified, rejected
        $tab = $request->query('tab', 'transactions');

        // Transaksi Vote Berbayar
        $transactionsQuery = PaidVoteTransaction::with(['reguProfile', 'verifier'])->latest();
        if ($statusFilter && in_array($statusFilter, ['pending', 'verified', 'rejected'])) {
            $transactionsQuery->where('status', $statusFilter);
        }
        $transactions = $transactionsQuery->paginate(20, ['*'], 'tx_page');

        // Statistik Suara
        $totalVotes = ReguVote::count();
        $freeVotesCount = ReguVote::where('vote_type', ReguVote::TYPE_FREE)->count();
        $paidVotesCount = ReguVote::where('vote_type', ReguVote::TYPE_PAID)->count();

        // Statistik Keuangan Voting Berbayar
        $totalRevenue = PaidVoteTransaction::where('status', PaidVoteTransaction::STATUS_VERIFIED)->sum('total_amount');
        $pendingTransactionsCount = PaidVoteTransaction::where('status', PaidVoteTransaction::STATUS_PENDING)->count();

        // Ringkasan per Regu (dengan rincian gratis vs berbayar)
        $summary = ReguProfile::withCount([
            'votes as total_votes',
            'votes as free_votes' => fn($q) => $q->where('vote_type', ReguVote::TYPE_FREE),
            'votes as paid_votes' => fn($q) => $q->where('vote_type', ReguVote::TYPE_PAID),
        ])
        ->orderByDesc('total_votes')
        ->get();

        // Detail riwayat seluruh suara
        $votes = ReguVote::with(['reguProfile', 'paidVoteTransaction'])
            ->orderByDesc('voted_at')
            ->paginate(50, ['*'], 'vote_page');

        // Ambil pengaturan voting & slogan
        $settings = Setting::whereIn('key', [
            'voting_counts_locked',
            'voting_counts_unlock_at',
            'voting_locked',
            'voting_end_at',
            'app_slogan',
            'paid_voting_enabled',
            'paid_vote_price',
            'paid_vote_min',
            'paid_vote_instructions',
            'free_vote_reset_mode',
            'free_vote_reset_interval_hours',
            'free_vote_last_reset_at',
        ])->pluck('value', 'key');

        $isCountsLocked = ($settings['voting_counts_locked'] ?? '0') === '1';
        $unlockAt = $settings['voting_counts_unlock_at'] ?? null;
        $isVotingLocked = ($settings['voting_locked'] ?? '0') === '1';
        $votingEndAt = $settings['voting_end_at'] ?? null;
        $appSlogan = $settings['app_slogan'] ?? 'Tangkas di Alam, Adaptif di Zaman, Unggul Berkarakter';

        // Pengaturan Paid Voting (DANA QRIS — Bisa Mulai Rp 1)
        $paidVotingEnabled = ($settings['paid_voting_enabled'] ?? '1') === '1';
        $paidVotePrice = (float) ($settings['paid_vote_price'] ?? 1);
        $paidVoteMin = (int) ($settings['paid_vote_min'] ?? 1);
        $paidVoteInstructions = $settings['paid_vote_instructions'] ?? 'Scan QRIS menggunakan aplikasi DANA Anda. Suara otomatis terhitung begitu pembayaran berhasil diterima DANA.';

        $danaService = app(DanaQrisService::class);
        $danaCfg = $danaService->getSettings();
        $danaQrisPayload = $danaCfg['qris_payload'];
        $danaQrisImagePath = $danaCfg['qris_image_path'];
        $danaAccountName = $danaCfg['account_name'];
        $danaWebhookSecret = $danaCfg['webhook_secret'];
        $danaUniqueCodeEnabled = $danaCfg['unique_code_enabled'];
        $danaSandboxMode = $danaCfg['sandbox_mode'];
        $danaConfigured = $danaService->isConfigured();

        // Pengaturan Reset Free Vote
        $freeVoteResetMode = $settings['free_vote_reset_mode'] ?? 'daily';
        $freeVoteResetIntervalHours = (int) ($settings['free_vote_reset_interval_hours'] ?? 24);
        $freeVoteLastResetAt = $settings['free_vote_last_reset_at'] ?? null;

        // Evaluasi apakah saat ini jumlah suara sedang terkunci untuk publik
        $now = now();
        $isCurrentlyLocked = false;
        if ($isCountsLocked) {
            $isCurrentlyLocked = !empty($unlockAt) ? $now->lessThan(\Carbon\Carbon::parse($unlockAt)) : true;
        }

        // Evaluasi apakah penerimaan vote baru sedang ditutup
        $isCurrentlyClosed = false;
        if ($isVotingLocked) {
            $isCurrentlyClosed = true;
        } elseif (!empty($votingEndAt)) {
            $isCurrentlyClosed = $now->greaterThanOrEqualTo(\Carbon\Carbon::parse($votingEndAt));
        }

        return view('admin.voting.index', compact(
            'transactions',
            'summary',
            'votes',
            'totalVotes',
            'freeVotesCount',
            'paidVotesCount',
            'totalRevenue',
            'pendingTransactionsCount',
            'statusFilter',
            'tab',
            'isCountsLocked',
            'unlockAt',
            'isVotingLocked',
            'votingEndAt',
            'appSlogan',
            'paidVotingEnabled',
            'paidVotePrice',
            'paidVoteMin',
            'paidVoteInstructions',
            'danaQrisPayload',
            'danaQrisImagePath',
            'danaAccountName',
            'danaWebhookSecret',
            'danaUniqueCodeEnabled',
            'danaSandboxMode',
            'danaConfigured',
            'freeVoteResetMode',
            'freeVoteResetIntervalHours',
            'freeVoteLastResetAt',
            'isCurrentlyLocked',
            'isCurrentlyClosed'
        ));
    }

    /**
     * Simpan seluruh pengaturan kunci voting, reset voting gratis, dan DANA QRIS (min Rp 1).
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'voting_status' => 'nullable|in:open,closed',
            'voting_counts_locked' => 'nullable|in:0,1',
            'voting_counts_unlock_at' => 'nullable|date',
            'voting_locked' => 'nullable|in:0,1',
            'voting_end_at' => 'nullable|date',
            'app_slogan' => 'nullable|string|max:255',
            // Free vote reset
            'free_vote_reset_mode' => 'required|in:daily,interval,once',
            'free_vote_reset_interval_hours' => 'nullable|integer|min:1|max:720',
            // Paid vote (DANA QRIS — Minimal Rp 1)
            'paid_voting_enabled' => 'nullable|in:0,1',
            'paid_vote_price' => 'required|numeric|min:1|max:10000000',
            'paid_vote_min' => 'required|integer|min:1|max:1000',
            'paid_vote_instructions' => 'nullable|string|max:1000',
            'dana_qris_image' => 'nullable|file|image|max:5120',
            'dana_qris_payload' => 'nullable|string|max:2000',
            'dana_account_name' => 'nullable|string|max:120',
            'dana_webhook_secret' => 'nullable|string|max:120',
            'dana_unique_code_enabled' => 'nullable|in:0,1',
            'dana_sandbox_mode' => 'nullable|in:0,1',
        ]);

        // Status Voting: Dibuka (open) atau Ditutup (closed)
        $isVotingLocked = '0';
        if ($request->filled('voting_status')) {
            $isVotingLocked = $request->input('voting_status') === 'closed' ? '1' : '0';
        } else {
            $isVotingLocked = $request->has('voting_locked') || $request->input('voting_locked') === '1' ? '1' : '0';
        }
        Setting::updateOrCreate(['key' => 'voting_locked'], ['value' => $isVotingLocked]);
        Setting::updateOrCreate(['key' => 'voting_end_at'], ['value' => $request->filled('voting_end_at') ? $request->input('voting_end_at') : null]);

        // Kunci Suara Publik (Secret Freeze)
        $isCountsLocked = $request->has('voting_counts_locked') || $request->input('voting_counts_locked') === '1' ? '1' : '0';
        Setting::updateOrCreate(['key' => 'voting_counts_locked'], ['value' => $isCountsLocked]);
        Setting::updateOrCreate(['key' => 'voting_counts_unlock_at'], ['value' => $request->filled('voting_counts_unlock_at') ? $request->input('voting_counts_unlock_at') : null]);

        if ($request->filled('app_slogan')) {
            Setting::updateOrCreate(['key' => 'app_slogan'], ['value' => trim($request->input('app_slogan'))]);
        }

        // Pengaturan Reset Voting Gratis
        Setting::updateOrCreate(['key' => 'free_vote_reset_mode'], ['value' => $request->input('free_vote_reset_mode')]);
        Setting::updateOrCreate(['key' => 'free_vote_reset_interval_hours'], ['value' => $request->input('free_vote_reset_interval_hours', 24)]);

        // Pengaturan Voting Berbayar (DANA QRIS)
        $isPaidVotingEnabled = $request->has('paid_voting_enabled') || $request->input('paid_voting_enabled') === '1' ? '1' : '0';
        Setting::updateOrCreate(['key' => 'paid_voting_enabled'], ['value' => $isPaidVotingEnabled]);
        Setting::updateOrCreate(['key' => 'paid_vote_price'], ['value' => (int) round((float) $request->input('paid_vote_price'))]);
        Setting::updateOrCreate(['key' => 'paid_vote_min'], ['value' => (int) $request->input('paid_vote_min', 1)]);
        Setting::updateOrCreate(['key' => 'paid_vote_instructions'], ['value' => $request->input('paid_vote_instructions', '')]);

        // Pengaturan DANA QRIS & Webhook
        Setting::updateOrCreate(['key' => 'dana_account_name'], ['value' => trim((string) $request->input('dana_account_name', 'DANA Panitia'))]);
        if ($request->filled('dana_qris_payload')) {
            Setting::updateOrCreate(['key' => 'dana_qris_payload'], ['value' => trim((string) $request->input('dana_qris_payload'))]);
        } elseif ($request->has('dana_qris_payload') && trim((string) $request->input('dana_qris_payload')) === '') {
            Setting::updateOrCreate(['key' => 'dana_qris_payload'], ['value' => '']);
        }

        if ($request->filled('dana_webhook_secret')) {
            Setting::updateOrCreate(['key' => 'dana_webhook_secret'], ['value' => trim((string) $request->input('dana_webhook_secret'))]);
        }

        $uniqueCodeEnabled = $request->has('dana_unique_code_enabled') || $request->input('dana_unique_code_enabled') === '1' ? '1' : '0';
        Setting::updateOrCreate(['key' => 'dana_unique_code_enabled'], ['value' => $uniqueCodeEnabled]);

        $isSandbox = $request->has('dana_sandbox_mode') || $request->input('dana_sandbox_mode') === '1' ? '1' : '0';
        Setting::updateOrCreate(['key' => 'dana_sandbox_mode'], ['value' => $isSandbox]);

        // Upload Gambar QRIS DANA jika ada
        if ($request->hasFile('dana_qris_image')) {
            $oldQris = Setting::where('key', 'dana_qris_image_path')->value('value');
            if ($oldQris && Storage::disk('public')->exists($oldQris)) {
                Storage::disk('public')->delete($oldQris);
            }
            $qrisPath = $request->file('dana_qris_image')->store('voting/dana_qris', 'public');
            Setting::updateOrCreate(['key' => 'dana_qris_image_path'], ['value' => $qrisPath]);
        }

        return redirect()->route('admin.voting.index', ['tab' => 'settings'])->with('success', 'Pengaturan status voting, tarif (min Rp 1), dan QRIS DANA berhasil diperbarui!');
    }

    /**
     * Reset kuota voting gratis sekarang (semua voter dapat memberikan 1 vote gratis kembali).
     */
    public function resetFreeVotes(): RedirectResponse
    {
        Setting::updateOrCreate(
            ['key' => 'free_vote_last_reset_at'],
            ['value' => now()->toIso8601String()]
        );

        return back()->with('success', 'Cooldown voting gratis berhasil direset sekarang! Semua voter/perangkat kini dapat memberikan 1 suara gratis kembali tanpa mempengaruhi akumulasi perolehan suara sebelumnya.');
    }

    /**
     * Setujui transaksi vote berbayar (masukkan suara ke perolehan regu).
     */
    public function approveTransaction(PaidVoteTransaction $transaction): RedirectResponse
    {
        if ($transaction->isVerified()) {
            return back()->with('error', 'Transaksi ini sudah disetujui sebelumnya.');
        }

        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => PaidVoteTransaction::STATUS_VERIFIED,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'rejection_reason' => null,
            ]);

            // Bersihkan vote lama jika ada untuk transaksi ini
            $transaction->votes()->delete();

            // Masukkan N vote sesuai votes_count (100% murni tanpa potongan biaya)
            for ($i = 0; $i < $transaction->votes_count; $i++) {
                ReguVote::create([
                    'regu_profile_id' => $transaction->regu_profile_id,
                    'user_id' => $transaction->user_id,
                    'voter_identifier' => $transaction->voter_identifier,
                    'ip_address' => $transaction->ip_address,
                    'user_agent' => 'Paid Vote (' . $transaction->transaction_code . ')',
                    'voter_name' => $transaction->voter_name,
                    'voted_at' => now(),
                    'vote_type' => ReguVote::TYPE_PAID,
                    'paid_vote_transaction_id' => $transaction->id,
                ]);
            }
        });

        return redirect()->route('admin.voting.index', ['tab' => 'transactions'])->with('success', "Transaksi {$transaction->transaction_code} ({$transaction->votes_count} suara) berhasil disetujui dan ditambahkan ke {$transaction->reguProfile?->nama_regu}.");
    }

    /**
     * Periksa apakah transaksi pending sudah kedaluwarsa.
     */
    public function syncTransactionStatus(PaidVoteTransaction $transaction): RedirectResponse
    {
        if ($transaction->isVerified()) {
            return redirect()->route('admin.voting.index', ['tab' => 'transactions'])->with('success', "Transaksi {$transaction->transaction_code} sudah berstatus LUNAS.");
        }

        if ($transaction->isPending() && $transaction->expired_at && now()->greaterThan($transaction->expired_at)) {
            $transaction->update([
                'status' => PaidVoteTransaction::STATUS_REJECTED,
                'rejection_reason' => 'Waktu pembayaran QRIS DANA telah habis (kedaluwarsa).',
            ]);
            return redirect()->route('admin.voting.index', ['tab' => 'transactions'])->with('error', "Transaksi {$transaction->transaction_code} telah kedaluwarsa.");
        }

        return redirect()->route('admin.voting.index', ['tab' => 'transactions'])->with('error', "Transaksi {$transaction->transaction_code} masih menunggu notifikasi dana masuk dari DANA.");
    }

    /**
     * Tolak transaksi vote berbayar.
     */
    public function rejectTransaction(Request $request, PaidVoteTransaction $transaction): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($transaction, $request) {
            // Hapus suara yang sempat masuk jika sebelumnya berstatus verified
            $transaction->votes()->delete();

            $transaction->update([
                'status' => PaidVoteTransaction::STATUS_REJECTED,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'rejection_reason' => $request->input('rejection_reason', 'Bukti pembayaran tidak valid atau dana belum masuk.'),
            ]);
        });

        return back()->with('success', "Transaksi {$transaction->transaction_code} telah ditolak.");
    }

    /**
     * Hapus transaksi vote berbayar.
     */
    public function destroyTransaction(PaidVoteTransaction $transaction): RedirectResponse
    {
        if ($transaction->proof_path && Storage::disk('public')->exists($transaction->proof_path)) {
            Storage::disk('public')->delete($transaction->proof_path);
        }

        $transaction->delete(); // cascade delete on regu_votes

        return back()->with('success', "Transaksi {$transaction->transaction_code} berhasil dihapus.");
    }

    /**
     * Hapus satu data vote.
     */
    public function destroy(ReguVote $vote): RedirectResponse
    {
        $vote->delete();
        return back()->with('success', 'Data vote berhasil dihapus.');
    }

    /**
     * Hapus SEMUA data vote (reset voting total ke 0).
     */
    public function destroyAll(): RedirectResponse
    {
        DB::transaction(function () {
            ReguVote::truncate();
            PaidVoteTransaction::truncate();
        });

        return back()->with('success', 'Semua data vote dan transaksi berhasil dihapus dan voting telah direset dari nol.');
    }
}
