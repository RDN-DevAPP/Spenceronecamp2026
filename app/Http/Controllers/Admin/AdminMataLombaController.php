<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminMataLombaController extends Controller
{
    public function update(Request $request, MataLomba $mataLomba): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['required', 'string', 'size:6', 'alpha_num', Rule::unique('mata_lomba', 'kode')->ignore($mataLomba->id)],
            'nama' => 'required|string|max:255',
        ]);

        $validated['kode'] = strtoupper($validated['kode']);
        $mataLomba->update($validated);

        return back()->with('success', 'Kode lomba "' . $mataLomba->nama . '" berhasil diperbarui.');
    }

    public function destroy(MataLomba $mataLomba): RedirectResponse
    {
        $name = $mataLomba->nama;

        // Clear single FK on users table
        $mataLomba->juris()->update(['mata_lomba_id' => null]);

        // Remove pivot table records
        \DB::table('user_mata_lomba')->where('mata_lomba_id', $mataLomba->id)->delete();

        $mataLomba->delete();

        return back()->with('success', 'Mata lomba "' . $name . '" berhasil dihapus.');
    }
}
