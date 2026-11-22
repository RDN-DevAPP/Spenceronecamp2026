<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ReguProfile;
use App\Models\Score;
use App\Models\ScoreDetail;
use App\Models\ScoringCriteria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JuriScoreController extends Controller
{
    /**
     * Simpan atau update nilai untuk satu regu pada satu mata lomba.
     * Nilai 0-100.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'regu_profile_id' => ['required', 'exists:regu_profiles,id'],
            'mata_lomba_id' => ['required', 'exists:mata_lomba,id'],
            'nilai' => ['nullable', 'numeric', 'min:0', 'max:100'], // Make nullable for criteria scoring base
            'catatan' => ['nullable', 'string', 'max:500'],
            'criteria' => ['nullable', 'array'],
            'criteria.*.criteria_id' => ['required_with:criteria', 'exists:scoring_criteria,id'],
            'criteria.*.nilai' => ['required_with:criteria', 'numeric', 'min:0'],
        ], [
            'nilai.min' => 'Nilai minimal 0.',
            'nilai.max' => 'Nilai maksimal 100.',
        ]);

        $juriId = Auth::id();
        $mataLomba = MataLomba::findOrFail($validated['mata_lomba_id']);
        $regu = ReguProfile::findOrFail($validated['regu_profile_id']);

        // Check if existing score is locked
        $existingScore = Score::where('regu_profile_id', $validated['regu_profile_id'])
            ->where('mata_lomba_id', $mataLomba->id)
            ->where('juri_id', $juriId)
            ->first();

        if ($existingScore && $existingScore->isLocked()) {
            return back()->with('error', 'Nilai telah dikunci dan tidak dapat diubah tanpa persetujuan Admin.');
        }

        // Check if upload is required and present
        $requiredPhoto = $regu->getCompetitionPhoto($mataLomba->nama);
        $visualSlugs = ['desain-poster-digital', 'masak-konvensional', 'tapak-kemah', 'upcycle-art'];
        
        if (in_array($mataLomba->slug, $visualSlugs) && empty($requiredPhoto)) {
            return back()->with('error', 'Regu ini belum mengunggah file/foto untuk mata lomba ' . $mataLomba->nama . '. Nilai tidak dapat disimpan.');
        }

        $action = $request->input('action', 'draft'); // 'draft' or 'finalize'

        DB::transaction(function () use ($validated, $juriId, $mataLomba, $existingScore, $action, $request) {
            // Calculate total if criteria exists
            $totalNilai = $validated['nilai'] ?? 0;

            if (isset($validated['criteria']) && !empty($validated['criteria'])) {
                $totalNilai = collect($validated['criteria'])->sum('nilai');
            }

            $oldScoreVal = $existingScore?->nilai;
            $oldStatusVal = $existingScore?->status ?? Score::STATUS_DRAFT;
            $newStatusVal = ($action === 'finalize') ? Score::STATUS_SUBMITTED : Score::STATUS_DRAFT;

            $score = Score::updateOrCreate(
                [
                    'regu_profile_id' => $validated['regu_profile_id'],
                    'mata_lomba_id' => $mataLomba->id,
                    'juri_id' => $juriId,
                ],
                [
                    'nilai' => $totalNilai,
                    'status' => $newStatusVal,
                    'submitted_at' => ($action === 'finalize') ? now() : ($existingScore?->submitted_at),
                    'catatan' => $validated['catatan'] ?? null,
                ]
            );

            if (isset($validated['criteria'])) {
                // Delete existing score details
                $score->scoreDetails()->delete();

                // Save score details
                foreach ($validated['criteria'] as $criteriaItem) {
                    ScoreDetail::create([
                        'score_id' => $score->id,
                        'scoring_criteria_id' => $criteriaItem['criteria_id'],
                        'nilai' => $criteriaItem['nilai'],
                    ]);
                }
            }

            // Log to audit trail
            \App\Services\ScoreAuditService::log(
                $score,
                $action === 'finalize' ? 'submitted' : 'draft_saved',
                $oldScoreVal,
                $totalNilai,
                $oldStatusVal,
                $newStatusVal,
                $action === 'finalize' ? 'Nilai difinalisasi dan dikunci oleh juri' : 'Nilai disimpan sebagai draf',
                $request
            );
        });

        $msg = ($action === 'finalize') 
            ? 'Nilai berhasil dikunci dan difinalisasi.' 
            : 'Nilai draf berhasil disimpan. Anda masih dapat menyuntingnya sebelum dikunci.';

        return to_route('juri.lomba.score', $mataLomba->slug)->with('success', $msg);
    }

    /**
     * Bulk simpan nilai untuk satu mata lomba (semua regu).
     * Mendukung criteria-based scoring dan simple scoring.
     */
    public function storeBulk(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mata_lomba_id' => ['required', 'exists:mata_lomba,id'],
            'scores' => ['required', 'array'],
            'scores.*.regu_profile_id' => ['required', 'exists:regu_profiles,id'],
            'scores.*.nilai' => ['nullable', 'numeric', 'min:0'],
            'scores.*.catatan' => ['nullable', 'string', 'max:500'],
            'scores.*.criteria' => ['nullable', 'array'],
            'scores.*.criteria.*.criteria_id' => ['required_with:scores.*.criteria', 'exists:scoring_criteria,id'],
            'scores.*.criteria.*.nilai' => ['required_with:scores.*.criteria', 'numeric', 'min:0'],
        ]);

        $juriId = Auth::id();
        $mataLombaId = $validated['mata_lomba_id'];

        // Check if this lomba has criteria
        $hasCriteria = ScoringCriteria::where('mata_lomba_id', $mataLombaId)->exists();
        $mataLomba = MataLomba::findOrFail($mataLombaId);

        // Visual check logic
        $visualSlugs = ['desain-poster-digital', 'masak-konvensional', 'tapak-kemah', 'upcycle-art'];
        $isVisual = in_array($mataLomba->slug, $visualSlugs);

        DB::transaction(function () use ($validated, $juriId, $mataLombaId, $hasCriteria, $isVisual, $mataLomba) {
            foreach ($validated['scores'] as $item) {
                // Skip if no nilai and no criteria
                if (empty($item['nilai']) && empty($item['criteria'])) {
                    continue;
                }

                // Nilai yang sudah dikunci/diverifikasi tidak boleh diubah tanpa persetujuan Admin
                $lockedScore = Score::where('regu_profile_id', $item['regu_profile_id'])
                    ->where('mata_lomba_id', $mataLombaId)
                    ->where('juri_id', $juriId)
                    ->first();
                if ($lockedScore && $lockedScore->isLocked()) {
                    continue;
                }

                // If visual, check for photo
                if ($isVisual) {
                    $regu = ReguProfile::find($item['regu_profile_id']);
                    if ($regu && empty($regu->getCompetitionPhoto($mataLomba->nama))) {
                        continue; // Skip regu without photo in bulk scoring
                    }
                }

                if ($hasCriteria && !empty($item['criteria'])) {
                    // Criteria-based scoring
                    // Calculate total from criteria
                    $totalNilai = collect($item['criteria'])->sum('nilai');

                    $score = Score::updateOrCreate(
                        [
                            'regu_profile_id' => $item['regu_profile_id'],
                            'mata_lomba_id' => $mataLombaId,
                            'juri_id' => $juriId,
                        ],
                        [
                            'nilai' => $totalNilai,
                            'catatan' => $item['catatan'] ?? null,
                        ]
                    );

                    // Delete existing score details
                    $score->scoreDetails()->delete();

                    // Save score details
                    foreach ($item['criteria'] as $criteriaItem) {
                        ScoreDetail::create([
                            'score_id' => $score->id,
                            'scoring_criteria_id' => $criteriaItem['criteria_id'],
                            'nilai' => $criteriaItem['nilai'],
                        ]);
                    }
                } else {
                    // Simple scoring (no criteria)
                    Score::updateOrCreate(
                        [
                            'regu_profile_id' => $item['regu_profile_id'],
                            'mata_lomba_id' => $mataLombaId,
                            'juri_id' => $juriId,
                        ],
                        [
                            'nilai' => $item['nilai'],
                            'catatan' => $item['catatan'] ?? null,
                        ]
                    );
                }
            }
        });

        return back()->with('success', 'Nilai berhasil disimpan.');
    }

    /**
     * Setujui penghapusan nilai yang diajukan oleh Admin.
     */
    public function approveDelete(Request $request, Score $score): RedirectResponse
    {
        // Pastikan hanya Juri pemilik nilai yang bisa menyetujui
        if ($score->juri_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus nilai ini.');
        }

        if (!$score->delete_requested) {
            return back()->with('error', 'Nilai ini tidak sedang diajukan untuk dihapus.');
        }

        $score->delete();

        return back()->with('success', 'Penghapusan nilai berhasil disetujui dan nilai telah musnah.');
    }

    /**
     * Tolak penghapusan nilai yang diajukan oleh Admin.
     */
    public function rejectDelete(Request $request, Score $score): RedirectResponse
    {
        // Pastikan hanya Juri pemilik nilai yang bisa menolak
        if ($score->juri_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk aksi ini.');
        }

        if (!$score->delete_requested) {
            return back()->with('error', 'Nilai ini tidak sedang diajukan untuk dihapus.');
        }

        $score->update([
            'delete_requested' => false,
            'delete_reason' => null,
        ]);

        return back()->with('success', 'Pengajuan penghapusan nilai berhasil ditolak.');
    }

    /**
     * Finalisasi dan kunci nilai oleh juri.
     */
    public function finalizeScore(Request $request, Score $score): RedirectResponse
    {
        if ($score->juri_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki otorisasi untuk nilai ini.');
        }

        $oldStatus = $score->status;
        $score->update([
            'status' => Score::STATUS_SUBMITTED,
            'submitted_at' => now(),
            'unlock_requested' => false,
        ]);

        \App\Services\ScoreAuditService::log(
            $score,
            'submitted',
            $score->nilai,
            $score->nilai,
            $oldStatus,
            Score::STATUS_SUBMITTED,
            'Juri mengunci dan memfinalisasi nilai',
            $request
        );

        return back()->with('success', 'Nilai berhasil dikunci. Terima kasih atas finalisasinya.');
    }

    /**
     * Ajukan permintaan buka kunci nilai (Unlock Request) kepada Admin.
     */
    public function requestUnlock(Request $request, Score $score): RedirectResponse
    {
        if ($score->juri_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki otorisasi untuk nilai ini.');
        }

        $validated = $request->validate([
            'unlock_reason' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'unlock_reason.required' => 'Mohon sertakan alasan pengajuan buka kunci nilai.',
            'unlock_reason.min' => 'Alasan minimal 5 karakter.',
        ]);

        $score->update([
            'unlock_requested' => true,
            'unlock_reason' => $validated['unlock_reason'],
        ]);

        \App\Services\ScoreAuditService::log(
            $score,
            'unlock_requested',
            $score->nilai,
            $score->nilai,
            $score->status,
            $score->status,
            'Alasan pengajuan: ' . $validated['unlock_reason'],
            $request
        );

        return back()->with('success', 'Pengajuan buka kunci nilai berhasil dikirim ke Administrator. Harap tunggu persetujuan.');
    }
}
