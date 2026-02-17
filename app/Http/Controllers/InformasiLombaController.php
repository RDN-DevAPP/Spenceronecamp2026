<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InformasiLombaController extends Controller
{
    /**
     * Halaman publik: Informasi Ketentuan Lomba (6 mata lomba).
     */
    public function __invoke(): View
    {
        return view('informasi-lomba');
    }
}
