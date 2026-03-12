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
            'nama' => 'sometimes|required|string|max:255',
            'nilai_maksimal' => 'nullable|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'petunjuk_teknis' => 'nullable|string',
            'ketentuan_pelaksanaan' => 'nullable|string',
        ]);

        if (isset($validated['kode'])) {
            $validated['kode'] = strtoupper($validated['kode']);
        }

        $mataLomba->update($validated);

        if ($request->has('from_kriteria')) {
            return redirect()->route('admin.kriteria.show', $mataLomba->id)
                ->with('success', 'Nilai maksimal berhasil diperbarui.');
        }

        return redirect()->route('admin.informasi-lomba.index')
            ->with('success', 'Informasi Lomba ' . $mataLomba->nama . ' berhasil diperbarui.');
    }
}
