<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CerdasCermatSession;
use Illuminate\View\View;

class AdminRekapNilaiController extends Controller
{
    public function index(): View
    {
        // Round 1 - ranked and tiebreak
        $rekapBabak1 = CerdasCermatSession::with('reguProfile')
            ->where('status', '!=', 'registered')
            ->get()
            ->map(function ($s) {
                $s->score_round_1 = $s->score_round_1 ?? 0;
                $s->time_used_seconds = ($s->start_time_round_1 && $s->end_time_round_1) ? $s->start_time_round_1->diffInSeconds($s->end_time_round_1) : 0;
                return $s;
            })
            ->sort(function ($a, $b) {
                if ($a->score_round_1 !== $b->score_round_1) return $b->score_round_1 <=> $a->score_round_1;
                return $a->time_used_seconds <=> $b->time_used_seconds;
            })
            ->values();

        // Round 2 - ranked and tiebreak
        $rekapBabak2 = CerdasCermatSession::with('reguProfile')
            ->whereIn('status', ['round_2_ongoing', 'round_2_done', 'round_3_ongoing', 'round_3_done', 'finished'])
            ->get()
            ->map(function ($s) {
                $s->score_round_2 = $s->score_round_2 ?? 0;
                $s->time_used_seconds = ($s->start_time_round_2 && $s->end_time_round_2) ? $s->start_time_round_2->diffInSeconds($s->end_time_round_2) : 0;
                return $s;
            })
            ->sort(function ($a, $b) {
                if ($a->score_round_2 !== $b->score_round_2) return $b->score_round_2 <=> $a->score_round_2;
                return $a->time_used_seconds <=> $b->time_used_seconds;
            })
            ->values();

        // Round 3 - ranked and tiebreak
        $rekapBabak3 = CerdasCermatSession::with('reguProfile')
            ->whereIn('status', ['round_3_ongoing', 'round_3_done', 'finished'])
            ->get()
            ->map(function ($s) {
                $s->score_round_3 = $s->score_round_3 ?? 0;
                $s->time_used_seconds = ($s->start_time_round_3 && $s->end_time_round_3) ? $s->start_time_round_3->diffInSeconds($s->end_time_round_3) : 0;
                return $s;
            })
            ->sort(function ($a, $b) {
                if ($a->score_round_3 !== $b->score_round_3) return $b->score_round_3 <=> $a->score_round_3;
                return $a->time_used_seconds <=> $b->time_used_seconds;
            })
            ->values();

        // Check if all grading is complete for global lock buttons
        $allGradedRound2 = $rekapBabak2->isNotEmpty() && $rekapBabak2->every(fn($s) => (bool)$s->is_graded_round_2);
        $isFinalizedRound2 = $rekapBabak2->isNotEmpty() && $rekapBabak2->every(fn($s) => (bool)$s->is_finalized_round_2);

        $allGradedRound3 = $rekapBabak3->isNotEmpty() && $rekapBabak3->every(fn($s) => !is_null($s->score_round_3));
        $isFinalizedRound3 = $rekapBabak3->isNotEmpty() && $rekapBabak3->every(fn($s) => (bool)$s->is_finalized_round_3);

        return view('admin.rekap-nilai.index', compact(
            'rekapBabak1', 
            'rekapBabak2', 
            'rekapBabak3',
            'allGradedRound2',
            'isFinalizedRound2',
            'allGradedRound3',
            'isFinalizedRound3'
        ));
    }
}
