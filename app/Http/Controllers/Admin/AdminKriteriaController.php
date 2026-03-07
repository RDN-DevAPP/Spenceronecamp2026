<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ScoringCriteria;
use Illuminate\Http\Request;

class AdminKriteriaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(MataLomba $mataLomba)
    {
        return view('admin.kriteria.create', compact('mataLomba'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, MataLomba $mataLomba)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|min:0|gte:nilai_min',
            'urutan' => 'integer|min:0',
        ]);

        $mataLomba->scoringCriteria()->create($validated);

        return redirect()->route('admin.kriteria.show', $mataLomba->id)->with('success', 'Kriteria berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataLomba $mataLomba)
    {
        $criteria = $mataLomba->scoringCriteria()->orderBy('urutan')->get();
        return view('admin.kriteria.show', compact('mataLomba', 'criteria'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScoringCriteria $kriteria)
    {
        $mataLomba = $kriteria->mataLomba;
        return view('admin.kriteria.edit', compact('mataLomba', 'kriteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScoringCriteria $kriteria)
    {
        $mataLomba = $kriteria->mataLomba;

        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:255',
            'nilai_min' => 'required|numeric|min:0',
            'nilai_max' => 'required|numeric|min:0|gte:nilai_min',
            'urutan' => 'integer|min:0',
        ]);

        $kriteria->update($validated);

        return redirect()->route('admin.kriteria.show', $mataLomba->id)->with('success', 'Kriteria berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScoringCriteria $kriteria)
    {
        $mataLomba = $kriteria->mataLomba;

        $kriteria->delete();

        return redirect()->route('admin.kriteria.show', $mataLomba->id)->with('success', 'Kriteria berhasil dihapus!');
    }
}
