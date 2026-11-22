<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PanitiaMember;
use App\Models\ReguProfile;
use App\Models\Season;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminIdCardController extends Controller
{
    /**
     * Tampilan daftar regu & panitia untuk pencetakan ID Card
     */
    public function index(Request $request)
    {
        $activeSeason = Season::where('is_active', true)->first();
        
        $query = ReguProfile::with('anggotaRegu')
            ->when($activeSeason, fn($q) => $q->where('season_id', $activeSeason->id));

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_regu', 'like', "%{$search}%")
                  ->orWhere('sekolah', 'like', "%{$search}%")
                  ->orWhere('nomor_regu', 'like', "%{$search}%");
            });
        }

        $regus = $query->orderBy('jenis')->orderBy('nama_regu')->get();

        $stats = [
            'total_regu' => $regus->count(),
            'total_anggota' => $regus->sum(fn($r) => $r->anggotaRegu->count()),
            'total_foto' => $regus->sum(fn($r) => $r->anggotaRegu->whereNotNull('foto_path')->count()),
        ];

        $panitiaList = PanitiaMember::when($activeSeason, fn($q) => $q->where(function ($sub) use ($activeSeason) {
            $sub->where('season_id', $activeSeason->id)->orWhereNull('season_id');
        }))
            ->orderByDesc('created_at')
            ->orderBy('nama')
            ->get();

        $panitiaStats = [
            'total_panitia' => $panitiaList->count(),
            'total_foto' => $panitiaList->whereNotNull('foto_path')->count(),
            'total_sertifikat' => $panitiaList->whereNotNull('nomor_sertifikat')->count(),
        ];

        return view('admin.id-cards.index', compact('regus', 'stats', 'activeSeason', 'panitiaList', 'panitiaStats'));
    }

    /**
     * Cetak ID Card per regu (layout A4 2x2 grid)
     */
    public function print($regu)
    {
        if (!$regu instanceof ReguProfile) {
            $regu = ReguProfile::findOrFail($regu);
        }
        $activeSeason = Season::where('is_active', true)->first();
        
        $anggota = $regu->anggotaRegu()
            ->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")
            ->orderBy('urutan')
            ->get();

        // Siapkan QR Code data URI per anggota
        $cards = $anggota->map(function($m) use ($regu) {
            $verificationUrl = route('verifikasi-anggota', $m->id);
            $qrDataUri = QrCodeService::generateDataUri($verificationUrl, 4);
            return [
                'anggota' => $m,
                'regu' => $regu,
                'qr_code' => $qrDataUri,
                'verify_url' => $verificationUrl,
            ];
        });

        return view('admin.id-cards.print', [
            'cards' => $cards,
            'title' => 'ID Card Regu ' . $regu->nama_regu,
            'activeSeason' => $activeSeason,
            'singleRegu' => $regu,
        ]);
    }

    /**
     * Cetak Massal seluruh regu
     */
    public function printAll(Request $request)
    {
        $activeSeason = Season::where('is_active', true)->first();
        
        $query = ReguProfile::with(['anggotaRegu' => function($q) {
            $q->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")->orderBy('urutan');
        }])->when($activeSeason, fn($q) => $q->where('season_id', $activeSeason->id));

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $regus = $query->orderBy('jenis')->orderBy('nama_regu')->get();

        $cards = collect();
        foreach ($regus as $regu) {
            foreach ($regu->anggotaRegu as $m) {
                $verificationUrl = route('verifikasi-anggota', $m->id);
                $cards->push([
                    'anggota' => $m,
                    'regu' => $regu,
                    'qr_code' => QrCodeService::generateDataUri($verificationUrl, 4),
                    'verify_url' => $verificationUrl,
                ]);
            }
        }

        return view('admin.id-cards.print', [
            'cards' => $cards,
            'title' => 'ID Card Semua Regu Peserta',
            'activeSeason' => $activeSeason,
            'singleRegu' => null,
        ]);
    }

    /**
     * Tambah Panitia secara manual oleh Admin
     */
    public function storePanitia(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|min:2|max:150',
            'jabatan' => 'required|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'asal_instansi' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:30',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $activeSeason = Season::where('is_active', true)->first();
        $fotoPath = $request->hasFile('foto') ? $request->file('foto')->store('panitia-fotos', 'public') : null;

        PanitiaMember::create([
            'season_id' => $activeSeason?->id,
            'nama' => trim(preg_replace('/\s+/', ' ', $request->nama)),
            'jabatan' => trim($request->jabatan),
            'divisi' => $request->filled('divisi') ? trim($request->divisi) : null,
            'asal_instansi' => $request->filled('asal_instansi') ? trim($request->asal_instansi) : 'SMP Negeri 1 Cerbon',
            'no_hp' => $request->filled('no_hp') ? trim($request->no_hp) : null,
            'foto_path' => $fotoPath,
        ]);

        return redirect()->route('admin.id-cards.index', ['tab' => 'panitia'])->with('success', 'Data panitia berhasil ditambahkan!');
    }

    /**
     * Perbarui data Panitia
     */
    public function updatePanitia(Request $request, PanitiaMember $panitia)
    {
        $request->validate([
            'nama' => 'required|string|min:2|max:150',
            'jabatan' => 'required|string|max:100',
            'divisi' => 'nullable|string|max:100',
            'asal_instansi' => 'nullable|string|max:150',
            'no_hp' => 'nullable|string|max:30',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = [
            'nama' => trim(preg_replace('/\s+/', ' ', $request->nama)),
            'jabatan' => trim($request->jabatan),
            'divisi' => $request->filled('divisi') ? trim($request->divisi) : null,
            'asal_instansi' => $request->filled('asal_instansi') ? trim($request->asal_instansi) : 'SMP Negeri 1 Cerbon',
            'no_hp' => $request->filled('no_hp') ? trim($request->no_hp) : null,
        ];

        if ($request->hasFile('foto')) {
            if ($panitia->foto_path) {
                Storage::disk('public')->delete($panitia->foto_path);
            }
            $data['foto_path'] = $request->file('foto')->store('panitia-fotos', 'public');
        }

        $panitia->update($data);

        return redirect()->route('admin.id-cards.index', ['tab' => 'panitia'])->with('success', 'Data panitia berhasil diperbarui!');
    }

    /**
     * Hapus Panitia
     */
    public function destroyPanitia(PanitiaMember $panitia)
    {
        if ($panitia->foto_path) {
            Storage::disk('public')->delete($panitia->foto_path);
        }
        $panitia->delete();

        return redirect()->route('admin.id-cards.index', ['tab' => 'panitia'])->with('success', 'Data panitia berhasil dihapus.');
    }

    /**
     * Cetak ID Card Panitia Tunggal
     */
    public function printPanitiaSingle(PanitiaMember $panitia)
    {
        $verificationUrl = route('verifikasi-panitia', $panitia->id);
        $cards = collect([[
            'panitia' => $panitia,
            'qr_code' => QrCodeService::generateDataUri($verificationUrl, 4),
            'verify_url' => $verificationUrl,
        ]]);

        return view('admin.id-cards.print-panitia', [
            'cards' => $cards,
            'title' => 'ID Card Panitia - ' . $panitia->nama,
        ]);
    }

    /**
     * Cetak Semua ID Card Panitia (A4 2x2)
     */
    public function printPanitiaAll()
    {
        $activeSeason = Season::where('is_active', true)->first();
        $list = PanitiaMember::when($activeSeason, fn($q) => $q->where(function ($sub) use ($activeSeason) {
            $sub->where('season_id', $activeSeason->id)->orWhereNull('season_id');
        }))->orderBy('nama')->get();

        $cards = $list->map(function ($p) {
            $verificationUrl = route('verifikasi-panitia', $p->id);
            return [
                'panitia' => $p,
                'qr_code' => QrCodeService::generateDataUri($verificationUrl, 4),
                'verify_url' => $verificationUrl,
            ];
        });

        return view('admin.id-cards.print-panitia', [
            'cards' => $cards,
            'title' => 'ID Card Seluruh Panitia Kegiatan',
        ]);
    }
}
