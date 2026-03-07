<?php

namespace App\Http\Controllers;

use App\Models\ReguProfile;
use Illuminate\View\View;

class DaftarReguController extends Controller
{
    public function __invoke(): View
    {
        $reguPutra = ReguProfile::where('jenis', 'putra')
            ->with('anggotaRegu')
            ->orderBy('nomor_regu')
            ->get();

        $reguPutri = ReguProfile::where('jenis', 'putri')
            ->with('anggotaRegu')
            ->orderBy('nomor_regu')
            ->get();

        return view('daftar-regu', compact('reguPutra', 'reguPutri'));
    }
}
