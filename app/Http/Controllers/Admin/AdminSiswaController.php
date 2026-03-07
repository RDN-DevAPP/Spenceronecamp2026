<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminSiswaController extends Controller
{
    public function index(): View
    {
        $siswaKelas7 = Siswa::where('kelas', 7)->orderByRaw('nama COLLATE NOCASE')->get();
        $siswaKelas8 = Siswa::where('kelas', 8)->orderByRaw('nama COLLATE NOCASE')->get();
        $siswaKelas9 = Siswa::where('kelas', 9)->orderByRaw('nama COLLATE NOCASE')->get();

        return view('admin.siswa.index', compact('siswaKelas7', 'siswaKelas8', 'siswaKelas9'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => ['required', Rule::in([7, 8, 9])],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
        ]);

        Siswa::create($request->only('nama', 'kelas', 'jenis_kelamin'));

        return back()->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => ['required', Rule::in([7, 8, 9])],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
        ]);

        $siswa->update($request->only('nama', 'kelas', 'jenis_kelamin'));

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $siswa->delete();

        return back()->with('success', 'Siswa berhasil dihapus.');
    }

    public function deleteAll(int $kelas): RedirectResponse
    {
        Siswa::where('kelas', $kelas)->delete();

        return back()->with('success', "Semua data siswa kelas {$kelas} berhasil dihapus.");
    }

    public function importCsv(Request $request): RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
            'kelas' => ['required', Rule::in([7, 8, 9])],
        ]);

        $kelas = $request->input('kelas');
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau format tidak valid.');
        }

        // Normalize header names
        $header = array_map(fn($h) => strtolower(trim($h)), $header);
        $namaIdx = array_search('nama', $header);
        $jkIdx = array_search('jenis_kelamin', $header);

        // Also try single-char alias
        if ($jkIdx === false) {
            $jkIdx = array_search('jk', $header);
        }

        if ($namaIdx === false) {
            fclose($handle);
            return back()->with('error', 'Kolom "nama" tidak ditemukan di header CSV. Header yang ditemukan: ' . implode(', ', $header));
        }

        $imported = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $nama = trim($row[$namaIdx] ?? '');
            $jkRaw = strtolower(trim($row[$jkIdx] ?? ''));

            if (empty($nama)) {
                $skipped++;
                continue;
            }

            // Improve gender mapping
            $jk = null;
            if (in_array($jkRaw, ['p', 'perempuan', 'female', 'wanita'])) {
                $jk = 'P';
            } elseif (in_array($jkRaw, ['l', 'laki-laki', 'male', 'pria'])) {
                $jk = 'L';
            } elseif (!empty($jkRaw)) {
                // If not matched but not empty, maybe its already 'L' or 'P'
                $jk = strtoupper(substr($jkRaw, 0, 1));
                if (!in_array($jk, ['L', 'P'])) {
                    $jk = null;
                }
            }

            Siswa::create([
                'nama' => $nama,
                'kelas' => $kelas,
                'jenis_kelamin' => $jk,
            ]);
            $imported++;
        }

        fclose($handle);

        $msg = "{$imported} siswa kelas {$kelas} berhasil diimport.";
        if ($skipped > 0) {
            $msg .= " ({$skipped} baris dilewati karena kosong)";
        }

        return back()->with('success', $msg);
    }
}
