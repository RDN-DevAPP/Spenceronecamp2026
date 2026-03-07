<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ReguProfile;
use App\Models\Score;
use App\Models\ScoringCriteria;
use Illuminate\View\View;

class JuriDashboardController extends Controller
{
    /**
     * Dashboard Juri: list mata lomba untuk dipilih.
     */
    public function index(): View
    {
        $juriId = auth()->id();
        $user = auth()->user();

        // Only show the mata lomba assigned to this juri (many-to-many)
        $assignedIds = $user->mataLombas()->pluck('mata_lomba.id');
        if ($assignedIds->isNotEmpty()) {
            $mataLomba = MataLomba::whereIn('id', $assignedIds)->orderBy('urutan')->get();
        } else {
            // Fallback: show all if no mata_lomba assigned (legacy juri)
            $mataLomba = MataLomba::orderBy('urutan')->get();
        }

        // Get scoring statistics for each lomba
        $reguCount = ReguProfile::count();
        $scoringStats = [];

        foreach ($mataLomba as $lomba) {
            $scoredCount = Score::where('juri_id', $juriId)
                ->where('mata_lomba_id', $lomba->id)
                ->count();

            $scoringStats[$lomba->id] = [
                'scored' => $scoredCount,
                'total' => $reguCount,
                'percentage' => $reguCount > 0 ? round(($scoredCount / $reguCount) * 100) : 0,
            ];
        }

        // Get pending deletion requests from admin
        $pendingDeletions = Score::with(['reguProfile', 'mataLomba'])
            ->where('juri_id', $juriId)
            ->where('delete_requested', true)
            ->get();

        return view('juri.dashboard', [
            'mataLomba' => $mataLomba,
            'scoringStats' => $scoringStats,
            'pendingDeletions' => $pendingDeletions,
        ]);
    }

    /**
     * Halaman penilaian untuk satu mata lomba.
     */
    /**
     * Halaman daftar regu untuk dinilai pada satu mata lomba.
     */
    public function scoreLomba(string $slug)
    {
        // Custom redirection for Cerdas Cermat
        if ($slug === 'cerdas-cermat') {
            return redirect()->route('juri.cerdas-cermat.index');
        }

        $juriId = auth()->id();

        $lomba = MataLomba::where('slug', $slug)->firstOrFail();

        // Determine if this is a visual competition
        $visualSlugs = ['desain-poster-digital', 'masak-konvensional', 'tapak-kemah', 'upcycle-art'];
        $isVisual = in_array($slug, $visualSlugs);

        $regu = ReguProfile::with('user:id,name,username')
            ->orderBy('jenis')
            ->orderBy('nomor_regu')
            ->get();

        // Load existing scores for this lomba to show status
        $existingScoresQuery = Score::where('juri_id', $juriId)
            ->where('mata_lomba_id', $lomba->id);

        // If visual, we need the details for the modal form
        if ($isVisual) {
            $existingScoresQuery->with('scoreDetails.scoringCriteria');
        }

        $existingScores = $existingScoresQuery->get()->keyBy('regu_profile_id');

        // Load criteria if it's a visual competition (for the modal)
        $criteria = null;
        if ($isVisual) {
            $criteria = ScoringCriteria::where('mata_lomba_id', $lomba->id)
                ->orderBy('urutan')
                ->get();
        }

        return view('juri.score-lomba', [
            'lomba' => $lomba,
            'regu' => $regu,
            'existingScores' => $existingScores,
            'isVisual' => $isVisual,
            'criteria' => $criteria,
        ]);
    }

    /**
     * Halaman form penilaian untuk satu regu.
     */
    public function scoreRegu(string $slug, int $reguId): View
    {
        $juriId = auth()->id();

        $lomba = MataLomba::where('slug', $slug)->firstOrFail();
        $regu = ReguProfile::with('user:id,name,username')->findOrFail($reguId);

        // Check if this lomba has scoring criteria
        $criteria = ScoringCriteria::where('mata_lomba_id', $lomba->id)
            ->orderBy('urutan')
            ->get();

        // Load existing score for this regu
        $existingScore = Score::where('juri_id', $juriId)
            ->where('mata_lomba_id', $lomba->id)
            ->where('regu_profile_id', $reguId)
            ->with('scoreDetails.scoringCriteria')
            ->first();

        // Get next and previous regu IDs for navigation
        $allReguIds = ReguProfile::orderBy('jenis')
            ->orderBy('nomor_regu')
            ->pluck('id')
            ->toArray();

        $currentIndex = array_search($reguId, $allReguIds);
        $prevReguId = $currentIndex > 0 ? $allReguIds[$currentIndex - 1] : null;
        $nextReguId = $currentIndex < count($allReguIds) - 1 ? $allReguIds[$currentIndex + 1] : null;

        return view('juri.score-regu', [
            'lomba' => $lomba,
            'regu' => $regu,
            'existingScore' => $existingScore,
            'criteria' => $criteria,
            'hasCriteria' => $criteria->isNotEmpty(),
            'prevReguId' => $prevReguId,
            'nextReguId' => $nextReguId,
        ]);
    }
}
