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

        DB::transaction(function () use ($validated, $juriId, $mataLomba) {
            // Calculate total if criteria exists
            $totalNilai = $validated['nilai'] ?? 0;

            if (isset($validated['criteria']) && !empty($validated['criteria'])) {
                $totalNilai = collect($validated['criteria'])->sum('nilai');
            }

            $score = Score::updateOrCreate(
                [
                    'regu_profile_id' => $validated['regu_profile_id'],
                    'mata_lomba_id' => $mataLomba->id,
                    'juri_id' => $juriId,
                ],
                [
                    'nilai' => $totalNilai,
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
        });

        return to_route('juri.lomba.score', $mataLomba->slug)->with('success', 'Nilai berhasil disimpan.');
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

        DB::transaction(function () use ($validated, $juriId, $mataLombaId, $hasCriteria) {
            foreach ($validated['scores'] as $item) {
                // Skip if no nilai and no criteria
                if (empty($item['nilai']) && empty($item['criteria'])) {
                    continue;
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

        // Kembalikan status delete_requested ke false
        $score->update(['delete_requested' => false]);

        return back()->with('success', 'Permintaan penghapusan nilai ditolak. Nilai tetap dipertahankan.');
    }
}
