<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CerdasCermatSession;
use Illuminate\View\View;

class AdminRekapNilaiController extends Controller
{
    public function index(): View
    {
        // Round 1 - ranked
        $rekapBabak1 = CerdasCermatSession::with('reguProfile')
            ->orderByDesc('score_round_1')
            ->get();

        // Round 2 - ranked
        $rekapBabak2 = CerdasCermatSession::with('reguProfile')
            ->orderByDesc('score_round_2')
            ->get();

        // Round 3 - ranked
        $rekapBabak3 = CerdasCermatSession::with('reguProfile')
            ->orderByDesc('score_round_3')
            ->get();

        return view('admin.rekap-nilai.index', compact('rekapBabak1', 'rekapBabak2', 'rekapBabak3'));
    }
}
