<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKaryaLombaRequest;
use App\Models\KaryaLomba;
use App\Models\Season;
use App\Services\KaryaStorageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesertaKaryaController extends Controller
{
    public function __construct(
        protected KaryaStorageService $storageService
    ) {}

    /**
     * Tampilkan form & status Hasta Karya: Scout Craftsmanship
     */
    public function hastaKarya(Request $request): View
    {
        $user = $request->user();
        $regu = $user->reguProfile;

        $activeSeason = Season::where('is_active', true)->first();
        $seasonId = $activeSeason ? $activeSeason->id : null;

        $karya = null;
        if ($regu) {
            $karya = KaryaLomba::where('regu_profile_id', $regu->id)
                ->where('mata_lomba_slug', 'hasta-karya')
                ->first();
        }

        $anggota = $regu ? $regu->anggotaRegu()->orderBy('urutan')->get() : collect();

        return view('peserta.hasta-karya', compact('regu', 'karya', 'anggota', 'activeSeason'));
    }

    /**
     * Simpan / Perbarui Karya Lomba (Hasta Karya, Tapak Kemah, Masak Rimba)
     */
    public function storeOrUpdate(StoreKaryaLombaRequest $request, string $slug): RedirectResponse
    {
        $user = $request->user();
        $regu = $user->reguProfile;

        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $activeSeason = Season::where('is_active', true)->first();
        $seasonId = $activeSeason ? $activeSeason->id : $regu->season_id;

        // Ambil data existing atau buat instance baru
        $karya = KaryaLomba::firstOrNew([
            'regu_profile_id' => $regu->id,
            'mata_lomba_slug' => $slug,
        ]);

        $karya->season_id = $seasonId;
        $karya->judul_karya = $request->input('judul_karya');
        $karya->kategori_bahan = $request->input('kategori_bahan');
        $karya->rincian_bahan_alat = $request->input('rincian_bahan_alat');
        $karya->fungsi_guna = $request->input('fungsi_guna');
        $karya->deskripsi_filosofi = $request->input('deskripsi_filosofi');
        $karya->resep_gizi = $request->input('resep_gizi');
        $karya->creator_id = $request->input('creator_id');

        // Handle file uploads
        if ($request->hasFile('foto_utama')) {
            $this->storageService->deletePhoto($karya->foto_utama_path);
            $karya->foto_utama_path = $this->storageService->storeKaryaPhoto(
                $request->file('foto_utama'),
                $regu->id,
                $slug,
                'utama'
            );
        }

        if ($request->hasFile('foto_detail')) {
            $this->storageService->deletePhoto($karya->foto_detail_path);
            $karya->foto_detail_path = $this->storageService->storeKaryaPhoto(
                $request->file('foto_detail'),
                $regu->id,
                $slug,
                'detail'
            );
        }

        if ($request->hasFile('foto_proses')) {
            $this->storageService->deletePhoto($karya->foto_proses_path);
            $karya->foto_proses_path = $this->storageService->storeKaryaPhoto(
                $request->file('foto_proses'),
                $regu->id,
                $slug,
                'proses'
            );
        }

        if ($request->hasFile('foto_extra')) {
            $this->storageService->deletePhoto($karya->foto_extra_path);
            $karya->foto_extra_path = $this->storageService->storeKaryaPhoto(
                $request->file('foto_extra'),
                $regu->id,
                $slug,
                'extra'
            );
        }

        $karya->status = 'submitted';
        $karya->save();

        return back()->with('success', 'Portofolio karya berhasil disimpan dan diverifikasi!');
    }

    /**
     * Hapus berkas karya
     */
    public function destroy(Request $request, string $slug): RedirectResponse
    {
        $user = $request->user();
        $regu = $user->reguProfile;

        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $karya = KaryaLomba::where('regu_profile_id', $regu->id)
            ->where('mata_lomba_slug', $slug)
            ->first();

        if ($karya) {
            $this->storageService->deletePhoto($karya->foto_utama_path);
            $this->storageService->deletePhoto($karya->foto_detail_path);
            $this->storageService->deletePhoto($karya->foto_proses_path);
            $this->storageService->deletePhoto($karya->foto_extra_path);
            $karya->delete();
        }

        return back()->with('success', 'Data karya berhasil dihapus.');
    }
}
