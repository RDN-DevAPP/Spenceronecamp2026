<?php

namespace App\Http\Controllers;

use App\Models\MataLomba;
use Illuminate\View\View;

class InformasiLombaController extends Controller
{
    /**
     * Halaman publik: Informasi Ketentuan Lomba (6 mata lomba).
     */
    public function __invoke(): View
    {
        $mataLombas = MataLomba::orderBy('urutan')->get();
        return view('informasi-lomba', compact('mataLombas'));
    }
}
