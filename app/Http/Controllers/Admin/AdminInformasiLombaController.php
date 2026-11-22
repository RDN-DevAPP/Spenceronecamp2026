<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\Season;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminInformasiLombaController extends Controller
{
    public function index(): View
    {
        $mataLombas = MataLomba::orderBy('urutan')->get();
        return view('admin.informasi-lomba.index', compact('mataLombas'));
    }

    public function create(): View
    {
        $nextUrutan = (MataLomba::max('urutan') ?? 0) + 1;
        return view('admin.informasi-lomba.create', compact('nextUrutan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|min:2|max:10|alpha_num|unique:mata_lomba,kode',
            'kategori' => 'required|string|in:lomba_utama,giat_seni,giat_alam,lainnya',
            'is_scored' => 'required|boolean',
            'nilai_maksimal' => 'required|numeric|min:1',
            'urutan' => 'required|integer|min:1',
            'deskripsi' => 'nullable|string',
            'petunjuk_teknis' => 'nullable|string',
            'ketentuan_pelaksanaan' => 'nullable|string',
            'kriteria_penilaian' => 'nullable|string',
            'wajib_upload_foto' => 'nullable|boolean',
            'instruksi_upload_foto' => 'nullable|string',
            'max_foto' => 'nullable|integer|min:1|max:10',
        ], [
            'kode.required' => 'Kode lomba wajib diisi.',
            'kode.min' => 'Kode lomba minimal :min karakter.',
            'kode.max' => 'Kode lomba maksimal :max karakter.',
            'kode.alpha_num' => 'Kode lomba hanya boleh berupa huruf dan angka tanpa spasi.',
            'kode.unique' => 'Kode lomba sudah digunakan oleh mata lomba lain.',
        ]);

        $validated['kode'] = strtoupper($validated['kode']);
        $validated['wajib_upload_foto'] = $request->boolean('wajib_upload_foto');
        $validated['max_foto'] = $request->input('max_foto', 1);

        // Generate a unique slug
        $baseSlug = Str::slug($validated['nama']);
        $slug = $baseSlug;
        $counter = 1;
        while (MataLomba::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        // Associate with active season if available
        $activeSeason = Season::where('is_active', true)->first();
        if ($activeSeason) {
            $validated['season_id'] = $activeSeason->id;
        }

        $mataLomba = MataLomba::create($validated);

        return redirect()->route('admin.informasi-lomba.index')
            ->with('success', 'Mata lomba "' . $mataLomba->nama . '" berhasil ditambahkan.');
    }

    public function edit(MataLomba $mataLomba): View
    {
        return view('admin.informasi-lomba.edit', compact('mataLomba'));
    }

    public function update(Request $request, MataLomba $mataLomba): RedirectResponse
    {
        $rules = [
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'petunjuk_teknis' => 'nullable|string',
            'ketentuan_pelaksanaan' => 'nullable|string',
            'kriteria_penilaian' => 'nullable|string',
            'kategori' => 'sometimes|required|string',
            'is_scored' => 'sometimes|required|boolean',
            'urutan' => 'sometimes|required|integer|min:1',
            'nilai_maksimal' => 'sometimes|required|numeric|min:0',
            'wajib_upload_foto' => 'nullable|boolean',
            'instruksi_upload_foto' => 'nullable|string',
            'max_foto' => 'nullable|integer|min:1|max:10',
        ];

        // Only allow changing code if not cerdas-cermat
        if ($mataLomba->slug !== 'cerdas-cermat' && $request->has('kode')) {
            $rules['kode'] = ['required', 'string', 'min:2', 'max:10', 'alpha_num', Rule::unique('mata_lomba', 'kode')->ignore($mataLomba->id)];
        }

        $validated = $request->validate($rules, [
            'kode.required' => 'Kode lomba wajib diisi.',
            'kode.min' => 'Kode lomba minimal :min karakter.',
            'kode.max' => 'Kode lomba maksimal :max karakter.',
            'kode.alpha_num' => 'Kode lomba hanya boleh berupa huruf dan angka tanpa spasi.',
            'kode.unique' => 'Kode lomba sudah digunakan oleh mata lomba lain.',
        ]);

        if (isset($validated['kode'])) {
            $validated['kode'] = strtoupper($validated['kode']);
        }

        if ($request->has('wajib_upload_foto_submitted')) {
            $validated['wajib_upload_foto'] = $request->boolean('wajib_upload_foto');
        } elseif ($request->has('wajib_upload_foto')) {
            $validated['wajib_upload_foto'] = $request->boolean('wajib_upload_foto');
        }

        $mataLomba->update($validated);

        if ($request->has('from_kriteria')) {
            return redirect()->route('admin.kriteria.show', $mataLomba->id)
                ->with('success', 'Nilai maksimal berhasil diperbarui.');
        }

        return redirect()->route('admin.informasi-lomba.index')
            ->with('success', 'Informasi Lomba ' . $mataLomba->nama . ' berhasil diperbarui.');
    }

    public function destroy(MataLomba $mataLomba): RedirectResponse
    {
        // PROTEKSI MUTLAK: Cerdas Cermat tidak boleh dihapus
        if ($mataLomba->slug === 'cerdas-cermat' || strtolower($mataLomba->nama) === 'cerdas cermat' || strtoupper($mataLomba->kode) === 'CRC001') {
            return redirect()->route('admin.informasi-lomba.index')
                ->with('error', 'Mata lomba Cerdas Cermat adalah modul inti real-time sistem dan tidak boleh dihapus!');
        }

        $namaLomba = $mataLomba->nama;

        // Clear direct foreign key on users table
        $mataLomba->juris()->update(['mata_lomba_id' => null]);

        // Remove pivot table records
        \DB::table('user_mata_lomba')->where('mata_lomba_id', $mataLomba->id)->delete();

        // Delete scoring criteria & scores if any
        $mataLomba->scoringCriteria()->delete();
        $mataLomba->scores()->delete();

        // Delete the competition record
        $mataLomba->delete();

        return redirect()->route('admin.informasi-lomba.index')
            ->with('success', 'Mata lomba "' . $namaLomba . '" berhasil dihapus dari sistem.');
    }
}