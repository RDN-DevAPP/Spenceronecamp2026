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
        $reguCount = ReguProfile::whereHas('user', fn($q) => $q->where('role', 'regu'))->count();
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

        $query = ReguProfile::with('user:id,name,username')
            ->whereHas('user', fn($q) => $q->where('role', 'regu'));

        if ($slug === 'lkbb-tongkat') {
            $putra = (clone $query)->where('jenis', 'putra')->orderBy('nomor_regu')->get();
            $putri = (clone $query)->where('jenis', 'putri')->orderBy('nomor_regu')->get();
            
            $regu = collect();
            $max = max($putra->count(), $putri->count());
            for ($i = 0; $i < $max; $i++) {
                if ($putra->has($i)) $regu->push($putra[$i]);
                if ($putri->has($i)) $regu->push($putri[$i]);
            }
        } else {
            $regu = $query->orderBy('jenis')
                ->orderBy('nomor_regu')
                ->get();
        }

        // Load existing scores for this lomba to show status and details
        $existingScores = Score::where('juri_id', $juriId)
            ->where('mata_lomba_id', $lomba->id)
            ->with('scoreDetails.scoringCriteria')
            ->get()
            ->keyBy('regu_profile_id');

        // Load criteria for this competition
        $criteria = ScoringCriteria::where('mata_lomba_id', $lomba->id)
            ->orderBy('urutan')
            ->get();

        $isBulkCapable = in_array($slug, ['lkbb', 'lkbb-tongkat', 'tapak-kemah']);

        // Lomba PBB dengan Lembar Penilaian berjenjang (rubrik) -> daftar regu menuju lembar penilaian khusus
        if ($criteria->contains(fn ($c) => !empty($c->rubrik))) {
            return view('juri.pbb-list', [
                'lomba' => $lomba,
                'regu' => $regu,
                'existingScores' => $existingScores,
                'totalMax' => $criteria->sum('nilai_max'),
            ]);
        }

        return view('juri.score-lomba', [
            'lomba' => $lomba,
            'regu' => $regu,
            'existingScores' => $existingScores,
            'isVisual' => $isVisual,
            'criteria' => $criteria,
            'isBulkCapable' => $isBulkCapable,
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
        $allReguIds = ReguProfile::whereHas('user', fn($q) => $q->where('role', 'regu'))
            ->orderBy('jenis')
            ->orderBy('nomor_regu')
            ->pluck('id')
            ->toArray();

        $currentIndex = array_search($reguId, $allReguIds);
        $prevReguId = $currentIndex > 0 ? $allReguIds[$currentIndex - 1] : null;
        $nextReguId = $currentIndex < count($allReguIds) - 1 ? $allReguIds[$currentIndex + 1] : null;

        // Load karyaLomba if visual/hasta-karya
        $karyaLomba = \App\Models\KaryaLomba::where('regu_profile_id', $reguId)
            ->where(function($q) use ($slug) {
                $q->where('mata_lomba_slug', $slug)
                  ->orWhere('mata_lomba_slug', 'like', '%' . $slug . '%');
            })
            ->first();

        // Get audit logs for this score if existing
        $auditLogs = $existingScore ? $existingScore->auditLogs()->with('user')->latest()->get() : collect();

        // Lembar Penilaian PBB (rubrik berjenjang per poin)
        if ($criteria->contains(fn ($c) => !empty($c->rubrik))) {
            return view('juri.pbb-lembar', [
                'lomba' => $lomba,
                'regu' => $regu,
                'existingScore' => $existingScore,
                'groups' => $criteria->groupBy(fn ($c) => $c->kelompok ?: 'Penilaian'),
                'labels' => \App\Support\PbbRubrikDefault::LEVEL_LABELS,
                'prevReguId' => $prevReguId,
                'nextReguId' => $nextReguId,
            ]);
        }

        return view('juri.score-regu', [
            'lomba' => $lomba,
            'regu' => $regu,
            'existingScore' => $existingScore,
            'karyaLomba' => $karyaLomba,
            'auditLogs' => $auditLogs,
            'criteria' => $criteria,
            'hasCriteria' => $criteria->isNotEmpty(),
            'prevReguId' => $prevReguId,
            'nextReguId' => $nextReguId,
        ]);
    }
}
