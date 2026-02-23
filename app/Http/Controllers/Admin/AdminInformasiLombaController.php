<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use Illuminate\Http\Request;

class AdminInformasiLombaController extends Controller
{
    public function index()
    {
        $mataLombas = MataLomba::orderBy('urutan')->get();
        return view('admin.informasi-lomba.index', compact('mataLombas'));
    }

    public function edit(MataLomba $mataLomba)
    {
        return view('admin.informasi-lomba.edit', compact('mataLomba'));
    }

    public function update(Request $request, MataLomba $mataLomba)
    {
        $validated = $request->validate([
            'deskripsi' => 'nullable|string',
            'petunjuk_teknis' => 'nullable|string',
            'ketentuan_pelaksanaan' => 'nullable|string',
            'kriteria_penilaian' => 'nullable|string',
        ]);

        $mataLomba->update($validated);

        return redirect()->route('admin.informasi-lomba.index')
            ->with('success', 'Informasi Lomba ' . $mataLomba->nama . ' berhasil diperbarui.');
    }
}
