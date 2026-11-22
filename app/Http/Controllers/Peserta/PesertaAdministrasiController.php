<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\AnggotaRegu;
use App\Models\ReguAttendance;
use App\Models\ReguAttendanceDetail;
use App\Models\ReguFinance;
use App\Models\ReguInventory;
use App\Models\ReguLogbook;
use App\Models\ReguMeetingNote;
use App\Models\ReguProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PesertaAdministrasiController extends Controller
{
    /**
     * Dapatkan profil regu user saat ini.
     */
    private function getRegu(): ReguProfile
    {
        $user = Auth::user();
        $regu = $user?->reguProfile;
        if (!$regu) {
            abort(403, 'Profil regu tidak ditemukan atau akun ini bukan akun regu.');
        }
        return $regu;
    }

    /**
     * Halaman Utama Administrasi Regu (Overview & Hub 6 Buku).
     */
    public function index(): View
    {
        $regu = $this->getRegu();

        // 1. Buku Anggota Count
        $totalAnggota = $regu->anggotaRegu()->count();

        // 2. Keuangan
        $kasMasuk = $regu->finances()->where('jenis', 'masuk')->sum('nominal');
        $kasKeluar = $regu->finances()->where('jenis', 'keluar')->sum('nominal');
        $saldoKas = $kasMasuk - $kasKeluar;

        // 3. Logbook Count
        $totalLogbook = $regu->logbooks()->count();

        // 4. Notulen Count
        $totalNotulen = $regu->meetingNotes()->count();

        // 5. Presensi Count
        $totalPresensi = $regu->attendances()->count();

        // 6. Inventaris Count
        $totalInventaris = $regu->inventories()->sum('jumlah');

        return view('peserta.administrasi.index', compact(
            'regu',
            'totalAnggota',
            'kasMasuk',
            'kasKeluar',
            'saldoKas',
            'totalLogbook',
            'totalNotulen',
            'totalPresensi',
            'totalInventaris'
        ));
    }

    /* =========================================================================
     * 1. BUKU ANGGOTA
     * ========================================================================= */
    public function bukuAnggota(): View
    {
        $regu = $this->getRegu();
        $anggotaList = $regu->anggotaRegu()
            ->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")
            ->orderBy('urutan')
            ->get();

        return view('peserta.administrasi.buku-anggota', compact('regu', 'anggotaList'));
    }

    public function updateAnggotaDetail(Request $request, $id)
    {
        $regu = $this->getRegu();
        $anggota = $regu->anggotaRegu()->findOrFail($id);

        $validated = $request->validate([
            'nta' => 'nullable|string|max:50',
            'nisn' => 'nullable|string|max:20',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:500',
            'tingkatan_tku' => 'required|in:ramu,rakit,terap',
            'tkk' => 'nullable|string|max:1000',
        ]);

        $anggota->update($validated);

        return back()->with('success', "Data Buku Anggota untuk {$anggota->nama} berhasil diperbarui.");
    }

    public function printBukuAnggota(): View
    {
        $regu = $this->getRegu();
        $anggotaList = $regu->anggotaRegu()
            ->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")
            ->orderBy('urutan')
            ->get();

        return view('peserta.administrasi.print-buku-anggota', compact('regu', 'anggotaList'));
    }

    /* =========================================================================
     * 2. BUKU KEUANGAN
     * ========================================================================= */
    public function bukuKeuangan(): View
    {
        $regu = $this->getRegu();
        $finances = $regu->finances()->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate(25);

        $kasMasuk = $regu->finances()->where('jenis', 'masuk')->sum('nominal');
        $kasKeluar = $regu->finances()->where('jenis', 'keluar')->sum('nominal');
        $saldoKas = $kasMasuk - $kasKeluar;

        return view('peserta.administrasi.buku-keuangan', compact('regu', 'finances', 'kasMasuk', 'kasKeluar', 'saldoKas'));
    }

    public function storeKeuangan(Request $request)
    {
        $regu = $this->getRegu();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'uraian' => 'required|string|max:255',
            'jenis' => 'required|in:masuk,keluar',
            'nominal' => 'required|numeric|min:1',
            'kategori' => 'nullable|string|max:100',
            'bukti' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $buktiPath = null;
        if ($request->hasFile('bukti')) {
            $buktiPath = $request->file('bukti')->store('regu/finances', 'public');
        }

        $regu->finances()->create([
            'tanggal' => $validated['tanggal'],
            'uraian' => $validated['uraian'],
            'jenis' => $validated['jenis'],
            'nominal' => $validated['nominal'],
            'kategori' => $validated['kategori'] ?? null,
            'bukti_path' => $buktiPath,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return back()->with('success', 'Catatan keuangan kas regu berhasil ditambahkan.');
    }

    public function destroyKeuangan($id)
    {
        $regu = $this->getRegu();
        $finance = $regu->finances()->findOrFail($id);

        if ($finance->bukti_path && Storage::disk('public')->exists($finance->bukti_path)) {
            Storage::disk('public')->delete($finance->bukti_path);
        }

        $finance->delete();
        return back()->with('success', 'Catatan keuangan berhasil dihapus.');
    }

    public function printBukuKeuangan(): View
    {
        $regu = $this->getRegu();
        $finances = $regu->finances()->orderBy('tanggal', 'asc')->orderBy('id', 'asc')->get();

        $kasMasuk = $regu->finances()->where('jenis', 'masuk')->sum('nominal');
        $kasKeluar = $regu->finances()->where('jenis', 'keluar')->sum('nominal');
        $saldoKas = $kasMasuk - $kasKeluar;

        return view('peserta.administrasi.print-buku-keuangan', compact('regu', 'finances', 'kasMasuk', 'kasKeluar', 'saldoKas'));
    }

    /* =========================================================================
     * 3. BUKU CATATAN KEGIATAN (JURNAL / LOGBOOK)
     * ========================================================================= */
    public function bukuLogbook(): View
    {
        $regu = $this->getRegu();
        $logbooks = $regu->logbooks()->orderBy('tanggal', 'desc')->paginate(15);

        return view('peserta.administrasi.buku-logbook', compact('regu', 'logbooks'));
    }

    public function storeLogbook(Request $request)
    {
        $regu = $this->getRegu();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu_mulai' => 'nullable|string|max:20',
            'waktu_selesai' => 'nullable|string|max:20',
            'nama_kegiatan' => 'required|string|max:255',
            'tempat' => 'nullable|string|max:255',
            'pemimpin_kegiatan' => 'nullable|string|max:255',
            'ringkasan_materi' => 'nullable|string',
            'hasil_kegiatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:3072',
            'catatan_evaluasi' => 'nullable|string',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('regu/logbooks', 'public');
        }

        $regu->logbooks()->create([
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'] ?? null,
            'waktu_selesai' => $validated['waktu_selesai'] ?? null,
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'tempat' => $validated['tempat'] ?? null,
            'pemimpin_kegiatan' => $validated['pemimpin_kegiatan'] ?? null,
            'ringkasan_materi' => $validated['ringkasan_materi'] ?? null,
            'hasil_kegiatan' => $validated['hasil_kegiatan'] ?? null,
            'foto_kegiatan_path' => $fotoPath,
            'catatan_evaluasi' => $validated['catatan_evaluasi'] ?? null,
        ]);

        return back()->with('success', 'Catatan jurnal kegiatan berhasil disimpan.');
    }

    public function destroyLogbook($id)
    {
        $regu = $this->getRegu();
        $logbook = $regu->logbooks()->findOrFail($id);

        if ($logbook->foto_kegiatan_path && Storage::disk('public')->exists($logbook->foto_kegiatan_path)) {
            Storage::disk('public')->delete($logbook->foto_kegiatan_path);
        }

        $logbook->delete();
        return back()->with('success', 'Catatan logbook berhasil dihapus.');
    }

    public function printBukuLogbook(): View
    {
        $regu = $this->getRegu();
        $logbooks = $regu->logbooks()->orderBy('tanggal', 'asc')->get();

        return view('peserta.administrasi.print-buku-logbook', compact('regu', 'logbooks'));
    }

    /* =========================================================================
     * 4. BUKU NOTULEN RAPAT
     * ========================================================================= */
    public function bukuNotulen(): View
    {
        $regu = $this->getRegu();
        $notes = $regu->meetingNotes()->orderBy('tanggal', 'desc')->paginate(15);

        return view('peserta.administrasi.buku-notulen', compact('regu', 'notes'));
    }

    public function storeNotulen(Request $request)
    {
        $regu = $this->getRegu();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'nullable|string|max:30',
            'tempat' => 'nullable|string|max:255',
            'agenda' => 'required|string|max:255',
            'pemimpin_rapat' => 'nullable|string|max:255',
            'notulis' => 'nullable|string|max:255',
            'jumlah_hadir' => 'nullable|integer|min:0',
            'daftar_hadir_teks' => 'nullable|string',
            'jalannya_rapat' => 'nullable|string',
            'keputusan' => 'required|string',
            'catatan_tambahan' => 'nullable|string',
        ]);

        $regu->meetingNotes()->create($validated);

        return back()->with('success', 'Notulen rapat regu berhasil disimpan.');
    }

    public function destroyNotulen($id)
    {
        $regu = $this->getRegu();
        $note = $regu->meetingNotes()->findOrFail($id);
        $note->delete();

        return back()->with('success', 'Notulen rapat berhasil dihapus.');
    }

    public function printBukuNotulen(): View
    {
        $regu = $this->getRegu();
        $notes = $regu->meetingNotes()->orderBy('tanggal', 'asc')->get();

        return view('peserta.administrasi.print-buku-notulen', compact('regu', 'notes'));
    }

    /* =========================================================================
     * 5. BUKU PRESENSI
     * ========================================================================= */
    public function bukuPresensi(): View
    {
        $regu = $this->getRegu();
        $attendances = $regu->attendances()->with(['details.anggotaRegu'])->orderBy('tanggal', 'desc')->paginate(15);
        $anggotaList = $regu->anggotaRegu()->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")->get();

        return view('peserta.administrasi.buku-presensi', compact('regu', 'attendances', 'anggotaList'));
    }

    public function storePresensi(Request $request)
    {
        $regu = $this->getRegu();

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_kegiatan' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'required|array',
            'status.*' => 'required|in:hadir,izin,sakit,alpa',
        ]);

        $attendance = $regu->attendances()->create([
            'tanggal' => $validated['tanggal'],
            'nama_kegiatan' => $validated['nama_kegiatan'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        foreach ($validated['status'] as $anggotaId => $status) {
            $attendance->details()->create([
                'anggota_regu_id' => $anggotaId,
                'status' => $status,
                'keterangan' => $request->input("keterangan_anggota.{$anggotaId}"),
            ]);
        }

        return back()->with('success', 'Presensi kegiatan berhasil dicatat.');
    }

    public function destroyPresensi($id)
    {
        $regu = $this->getRegu();
        $attendance = $regu->attendances()->findOrFail($id);
        $attendance->delete();

        return back()->with('success', 'Data presensi berhasil dihapus.');
    }

    public function printBukuPresensi(): View
    {
        $regu = $this->getRegu();
        $attendances = $regu->attendances()->with(['details.anggotaRegu'])->orderBy('tanggal', 'asc')->get();
        $anggotaList = $regu->anggotaRegu()->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")->get();

        return view('peserta.administrasi.print-buku-presensi', compact('regu', 'attendances', 'anggotaList'));
    }

    /* =========================================================================
     * 6. BUKU INVENTARIS
     * ========================================================================= */
    public function bukuInventaris(): View
    {
        $regu = $this->getRegu();
        $inventories = $regu->inventories()->orderBy('nama_barang')->paginate(25);
        $totalBarang = $regu->inventories()->sum('jumlah');
        $kondisiBaik = $regu->inventories()->where('kondisi', 'baik')->sum('jumlah');
        $kondisiRusak = $regu->inventories()->whereIn('kondisi', ['rusak_ringan', 'rusak_berat'])->sum('jumlah');

        return view('peserta.administrasi.buku-inventaris', compact('regu', 'inventories', 'totalBarang', 'kondisiBaik', 'kondisiRusak'));
    }

    public function storeInventaris(Request $request)
    {
        $regu = $this->getRegu();

        $validated = $request->validate([
            'kode_barang' => 'nullable|string|max:50',
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'asal_barang' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $regu->inventories()->create($validated);

        return back()->with('success', 'Barang inventaris regu berhasil ditambahkan.');
    }

    public function destroyInventaris($id)
    {
        $regu = $this->getRegu();
        $item = $regu->inventories()->findOrFail($id);
        $item->delete();

        return back()->with('success', 'Barang inventaris berhasil dihapus.');
    }

    public function printBukuInventaris(): View
    {
        $regu = $this->getRegu();
        $inventories = $regu->inventories()->orderBy('nama_barang')->get();

        return view('peserta.administrasi.print-buku-inventaris', compact('regu', 'inventories'));
    }
}
