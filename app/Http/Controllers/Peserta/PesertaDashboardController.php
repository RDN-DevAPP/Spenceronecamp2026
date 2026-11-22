<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\ReguProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesertaDashboardController extends Controller
{

    /**
     * Dashboard Peserta (Regu): anggota regu, rekap nilai, upload poster.
     */
    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        /** @var \App\Models\ReguProfile|null $regu */
        $regu = $user->reguProfile()->with(['anggotaRegu', 'scores.mataLomba'])->first();

        if (!$regu) {
            return view('peserta.dashboard', [
                'regu' => null,
                'anggota' => [],
                'mataLombas' => [],
                'scores' => [],
            ]);
        }

        $anggota = $regu->anggotaRegu()
            ->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")
            ->orderByRaw("CASE WHEN tingkatan_tku = 'terap' THEN 1 WHEN tingkatan_tku = 'rakit' THEN 2 WHEN tingkatan_tku = 'ramu' THEN 3 ELSE 4 END")
            ->get();

        // Get all Mata Lomba
        $mataLombas = \App\Models\MataLomba::orderBy('urutan')->get();

        // Get scores for the regu, keyed by mata_lomba_id for easy lookup
        $scores = $regu->scores->keyBy('mata_lomba_id');

        // Integrate Cerdas Cermat scores if they exist
        $ccLomba = $mataLombas->firstWhere('slug', 'cerdas-cermat');
        if ($ccLomba) {
            $ccSession = \App\Models\CerdasCermatSession::where('regu_id', $regu->id)->first();
            if ($ccSession) {
                // Determine accumulated score for recap
                $totalCC = ($ccSession->score_round_1 ?? 0);
                
                // Add Round 2 if graded or round is done
                if ($ccSession->is_graded_round_2) {
                    $totalCC += ($ccSession->score_round_2 ?? 0);
                }
                
                // Add Round 3 if finalized
                if ($ccSession->is_finalized_round_3) {
                    $totalCC += ($ccSession->score_round_3 ?? 0);
                }

                // If score doesn't exist in scores table, create a virtual one
                if (!$scores->has($ccLomba->id)) {
                    $virtualScore = new \stdClass();
                    $virtualScore->nilai = $totalCC;
                    $virtualScore->catatan = null;
                    $scores->put($ccLomba->id, $virtualScore);
                }
            }
        }

        return view('peserta.dashboard', [
            'regu' => $regu,
            'anggota' => $anggota,
            'mataLombas' => $mataLombas,
            'lombaWajibFoto' => $mataLombas->where('wajib_upload_foto', true),
            'scores' => $scores,
        ]);
    }

    public function createAnggota(Request $request)
    {
        $user = $request->user();
        $regu = $user->reguProfile;

        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu tidak ditemukan.');
        }

        $currentCount = $regu->anggotaRegu()->count();
        if ($currentCount >= 8) {
            return redirect()->route('peserta.dashboard')->with('error', 'Jumlah anggota regu sudah mencapai batas maksimal (8 orang).');
        }

        $hasPinru = $regu->anggotaRegu()->where('jabatan', 'pinru')->exists();
        $hasWapinru = $regu->anggotaRegu()->where('jabatan', 'wapinru')->exists();

        return view('peserta.create-anggota', compact('regu', 'currentCount', 'hasPinru', 'hasWapinru'));
    }

    public function storeAnggota(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $regu = $user->reguProfile;

        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu tidak ditemukan.');
        }

        if ($regu->anggotaRegu()->count() >= 8) {
            return redirect()->route('peserta.dashboard')->with('error', 'Jumlah anggota regu sudah mencapai batas maksimal 8 orang.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'kelas' => ['required', 'in:7,8,9'],
            'tingkatan_tku' => ['required', 'in:ramu,rakit,terap'],
            'jabatan' => ['required', 'in:pinru,wapinru,anggota'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if (in_array($validated['jabatan'], ['pinru', 'wapinru'])) {
            $exists = $regu->anggotaRegu()->where('jabatan', $validated['jabatan'])->exists();
            if ($exists) {
                return back()->withInput()->with('error', "Jabatan " . strtoupper($validated['jabatan']) . " sudah terisi di regu ini.");
            }
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('anggota_photos', 'public');
        }

        $newOrder = $regu->anggotaRegu()->count() + 1;

        $regu->anggotaRegu()->create([
            'nama' => $validated['nama'],
            'nisn' => $validated['nisn'] ?? null,
            'nta' => $validated['nisn'] ?? null,
            'kelas' => $validated['kelas'],
            'tingkatan_tku' => $validated['tingkatan_tku'],
            'jabatan' => $validated['jabatan'],
            'foto_path' => $fotoPath,
            'urutan' => $newOrder,
        ]);

        $activeSeason = \App\Models\Season::where('is_active', true)->first();

        \App\Models\Siswa::updateOrCreate(
            [
                'nama' => trim($validated['nama']),
                'kelas' => (int) $validated['kelas'],
                'season_id' => $activeSeason?->id ?? $regu->season_id,
            ],
            [
                'nisn' => $validated['nisn'] ?? null,
            'nta' => $validated['nisn'] ?? null,
                'jenis_kelamin' => ($regu->kategori === 'putri' || $regu->jenis === 'putri') ? 'P' : 'L',
                'tingkatan_tku' => $validated['tingkatan_tku'],
                'regu_profile_id' => $regu->id,
            ]
        );

        // Recalculate order
        $allMembers = $regu->anggotaRegu()->get();
        $sortedMembers = $allMembers->sort(function ($a, $b) {
            $order = ['pinru' => 1, 'wapinru' => 2, 'anggota' => 3];
            if ($order[$a->jabatan] !== $order[$b->jabatan]) {
                return $order[$a->jabatan] <=> $order[$b->jabatan];
            }
            return $a->urutan <=> $b->urutan;
        })->values();

        foreach ($sortedMembers as $index => $m) {
            $m->update(['urutan' => $index + 1]);
        }

        return redirect()->route('peserta.dashboard')->with('success', 'Anggota dan foto ID Card berhasil ditambahkan.');
    }

    public function editAnggota(Request $request, $id)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;

        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu tidak ditemukan.');
        }

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        $hasPinru = $regu->anggotaRegu()->where('jabatan', 'pinru')->where('id', '!=', $id)->exists();
        $hasWapinru = $regu->anggotaRegu()->where('jabatan', 'wapinru')->where('id', '!=', $id)->exists();

        return view('peserta.edit-anggota', compact('regu', 'anggota', 'hasPinru', 'hasWapinru'));
    }

    public function updateAnggota(Request $request, $id)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;

        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu tidak ditemukan.');
        }

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'kelas' => ['required', 'in:7,8,9'],
            'tingkatan_tku' => ['required', 'in:ramu,rakit,terap'],
            'jabatan' => ['required', 'in:pinru,wapinru,anggota'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'hapus_foto' => ['nullable', 'boolean'],
        ]);

        // Check unique constraints for pinru and wapinru if changing role
        if (in_array($validated['jabatan'], ['pinru', 'wapinru']) && $anggota->jabatan !== $validated['jabatan']) {
            $exists = $regu->anggotaRegu()->where('jabatan', $validated['jabatan'])->where('id', '!=', $id)->exists();
            if ($exists) {
                return back()->withInput()->with('error', "Jabatan " . strtoupper($validated['jabatan']) . " sudah terisi di regu ini.");
            }
        }

        $oldName = $anggota->nama;

        $updateData = [
            'nama' => $validated['nama'],
            'nisn' => $validated['nisn'] ?? null,
            'nta' => $validated['nisn'] ?? null,
            'kelas' => $validated['kelas'],
            'tingkatan_tku' => $validated['tingkatan_tku'],
            'jabatan' => $validated['jabatan'],
        ];

        if ($request->boolean('hapus_foto')) {
            if ($anggota->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto_path);
            }
            $updateData['foto_path'] = null;
        } elseif ($request->hasFile('foto')) {
            if ($anggota->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto_path);
            }
            $updateData['foto_path'] = $request->file('foto')->store('anggota_photos', 'public');
        }

        $anggota->update($updateData);

        // Sync to Siswa table
        \App\Models\Siswa::where('regu_profile_id', $regu->id)
            ->where('nama', $oldName)
            ->update([
                'nama' => $validated['nama'],
                'nisn' => $validated['nisn'] ?? null,
            'nta' => $validated['nisn'] ?? null,
                'kelas' => (int) $validated['kelas'],
                'tingkatan_tku' => $validated['tingkatan_tku'],
            ]);

        // Recalculate order for all members
        $allMembers = $regu->anggotaRegu()->get();
        $sortedMembers = $allMembers->sort(function ($a, $b) {
            $order = ['pinru' => 1, 'wapinru' => 2, 'anggota' => 3];
            if ($order[$a->jabatan] !== $order[$b->jabatan]) {
                return $order[$a->jabatan] <=> $order[$b->jabatan];
            }
            return $a->urutan <=> $b->urutan;
        })->values();

        foreach ($sortedMembers as $index => $m) {
            $m->update(['urutan' => $index + 1]);
        }

        return redirect()->route('peserta.dashboard')->with('success', 'Data anggota dan foto ID Card berhasil diperbarui.');
    }

    /**
     * Upload foto ID Card cepat untuk 1 anggota
     */
    public function uploadAnggotaFoto(Request $request, $id)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;
        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ]);

        if ($anggota->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto_path);
        }

        $path = $request->file('foto')->store('anggota_photos', 'public');
        $anggota->update(['foto_path' => $path]);

        return back()->with('success', "Foto ID Card untuk {$anggota->nama} berhasil diunggah.");
    }

    /**
     * Hapus foto ID Card untuk 1 anggota
     */
    public function deleteAnggotaFoto(Request $request, $id)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;
        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        if ($anggota->foto_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($anggota->foto_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($anggota->foto_path);
        }

        $anggota->update(['foto_path' => null]);

        return back()->with('success', "Foto ID Card untuk {$anggota->nama} berhasil dihapus.");
    }

    public function destroyAnggota(Request $request, $id)
    {
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;

        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu tidak ditemukan.');
        }

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        // Unlink from master Siswa
        \App\Models\Siswa::where('regu_profile_id', $regu->id)
            ->where('nama', $anggota->nama)
            ->update(['regu_profile_id' => null]);

        $anggota->delete();

        // Recalculate order for remaining members
        $allMembers = $regu->anggotaRegu()->get();
        $sortedMembers = $allMembers->sort(function ($a, $b) {
            $order = ['pinru' => 1, 'wapinru' => 2, 'anggota' => 3];
            if ($order[$a->jabatan] !== $order[$b->jabatan]) {
                return $order[$a->jabatan] <=> $order[$b->jabatan];
            }
            return $a->urutan <=> $b->urutan;
        })->values();

        foreach ($sortedMembers as $index => $m) {
            $m->update(['urutan' => $index + 1]);
        }

        return redirect()->route('peserta.dashboard')->with('success', 'Anggota berhasil dihapus.');
    }
    public function uploadPhoto(Request $request)
    {
        $slug = $request->input('mata_lomba_slug');
        
        $rules = [
            'mata_lomba_slug' => ['required', 'string'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];

        // Custom validation for file based on slug
        if ($slug === 'poster') {
            $rules['photo'] = ['required', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:10240'];
        } else {
            $rules['photo'] = ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'];
        }

        $request->validate($rules);

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\ReguProfile $regu */
        $regu = $user->reguProfile;

        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $file = $request->file('photo');
        $creatorId = $request->input('creator_id');

        // Map slug to database column
        $column = match ($slug) {
            'tapak-kemah' => 'foto_tenda_path',
            'masak-konvensional' => 'foto_masakan_path',
            'upcycle-art' => 'foto_karya_path',
            'poster' => 'poster_digital_path',
            default => null,
        };

        $creatorColumn = match ($slug) {
            'poster' => 'poster_creator_id',
            'upcycle-art' => 'upcycle_creator_id',
            default => null,
        };

        $descriptionColumn = match ($slug) {
            'poster' => 'poster_description',
            'upcycle-art' => 'upcycle_description',
            'tapak-kemah' => 'tenda_description',
            'masak-konvensional' => 'masakan_description',
            default => null,
        };

        if (!$column) {
            $dynamicLomba = \App\Models\MataLomba::where('slug', $slug)->where('wajib_upload_foto', true)->first();
            if ($dynamicLomba) {
                $path = $file->store('lomba-photos', 'public');
                \App\Models\KaryaLomba::updateOrCreate(
                    [
                        'regu_profile_id' => $regu->id,
                        'season_id' => $regu->season_id ?? 1,
                        'mata_lomba_slug' => $slug,
                    ],
                    [
                        'judul_karya' => $dynamicLomba->nama,
                        'foto_utama_path' => $path,
                        'deskripsi_filosofi' => $request->input('description'),
                        'status' => 'submitted',
                    ]
                );
                return back()->with('success', "Foto karya untuk {$dynamicLomba->nama} berhasil diunggah!");
            }

            return back()->with('error', 'Mata lomba tidak valid untuk upload foto ini.');
        }

        // Store file (poster goes to its own folder)
        $folder = ($slug === 'poster') ? 'poster-digital' : 'lomba-photos';
        $path = $file->store($folder, 'public');

        $data = [$column => $path];

        if ($creatorColumn && $creatorId) {
            // Verify creator belongs to regu
            $isMember = $regu->anggotaRegu()->where('id', $creatorId)->exists();
            if ($isMember) {
                $data[$creatorColumn] = $creatorId;
            }
        }

        if ($descriptionColumn && $request->has('description')) {
            $data[$descriptionColumn] = $request->input('description');
        }

        // Update database
        $regu->update($data);

        return back()->with('success', 'Foto karya berhasil diunggah.');
    }

    public function deletePhoto(Request $request)
    {
        $request->validate([
            'mata_lomba_slug' => ['required', 'string'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\ReguProfile $regu */
        $regu = $user->reguProfile;

        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $slug = $request->input('mata_lomba_slug');

        // Check if dynamic lomba in KaryaLomba
        $dynamicLomba = \App\Models\MataLomba::where('slug', $slug)->first();
        $karya = \App\Models\KaryaLomba::where('regu_profile_id', $regu->id)->where('mata_lomba_slug', $slug)->first();
        if ($karya) {
            if ($karya->foto_utama_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($karya->foto_utama_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($karya->foto_utama_path);
            }
            $karya->delete();
            return back()->with('success', "Foto untuk " . ($dynamicLomba?->nama ?? $slug) . " berhasil dihapus.");
        }

        // Map slug to database column and Mata Lomba Name
        [$column, $lombaName] = match ($slug) {
            'tapak-kemah' => ['foto_tenda_path', 'Tapak Kemah'],
            'masak-konvensional' => ['foto_masakan_path', 'Masak Konvensional'],
            'upcycle-art' => ['foto_karya_path', 'Upcycle Art'],
            'poster' => ['poster_digital_path', 'Desain Poster Digital'],
            default => [null, null],
        };

        if (!$column || !$lombaName) {
            return back()->with('error', 'Mata lomba tidak valid.');
        }

        // Check if score exists for this mata lomba
        $mataLomba = \App\Models\MataLomba::where('nama', $lombaName)->first();
        if ($mataLomba) {
            $hasScore = $regu->scores()->where('mata_lomba_id', $mataLomba->id)->exists();
            if ($hasScore) {
                return back()->with('error', 'Tidak dapat menghapus foto karena penilaian sudah masuk.');
            }
        }

        // Delete file from storage
        if ($regu->$column) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($regu->$column);
        }

        // Map slug to description column
        $descriptionColumn = match ($slug) {
            'poster' => 'poster_description',
            'upcycle-art' => 'upcycle_description',
            'tapak-kemah' => 'tenda_description',
            'masak-konvensional' => 'masakan_description',
            default => null,
        };

        // Update database
        $updateData = [$column => null];
        if ($descriptionColumn) {
            $updateData[$descriptionColumn] = null;
        }
        $regu->update($updateData);

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function updateDescription(Request $request)
    {
        $request->validate([
            'mata_lomba_slug' => ['required', 'string', 'in:poster,upcycle-art,tapak-kemah,masak-konvensional'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\ReguProfile $regu */
        $regu = $user->reguProfile;

        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $slug = $request->input('mata_lomba_slug');

        $descriptionColumn = match ($slug) {
            'poster' => 'poster_description',
            'upcycle-art' => 'upcycle_description',
            'tapak-kemah' => 'tenda_description',
            'masak-konvensional' => 'masakan_description',
            default => null,
        };

        if (!$descriptionColumn) {
            return back()->with('error', 'Mata lomba tidak valid.');
        }

        $regu->update([
            $descriptionColumn => $request->input('description'),
        ]);

        return back()->with('success', 'Deskripsi karya berhasil disimpan.');
    }

    /**
     * Upload Foto Bersama Seluruh Anggota Regu (Untuk Voting Terfavorit & Profil)
     */
    public function uploadFotoRegu(Request $request)
    {
        $request->validate([
            'foto_regu' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'foto_regu_caption' => ['nullable', 'string', 'max:500'],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;
        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        // Hapus file foto regu lama jika ada
        if ($regu->foto_regu_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($regu->foto_regu_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($regu->foto_regu_path);
        }

        $path = $request->file('foto_regu')->store('regu_photos', 'public');

        $regu->update([
            'foto_regu_path' => $path,
            'foto_regu_caption' => $request->input('foto_regu_caption'),
        ]);

        return back()->with('success', 'Foto Regu berhasil diunggah! Foto ini kini tampil di ajang Voting Terfavorit.');
    }

    /**
     * Hapus Foto Bersama Seluruh Anggota Regu
     */
    public function deleteFotoRegu(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user() ?? \Illuminate\Support\Facades\Auth::user();
        $regu = $user?->reguProfile;
        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        if ($regu->foto_regu_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($regu->foto_regu_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($regu->foto_regu_path);
        }

        $regu->update([
            'foto_regu_path' => null,
            'foto_regu_caption' => null,
        ]);

        return back()->with('success', 'Foto Regu berhasil dihapus.');
    }

    /**
     * Halaman cetak Kartu Tanda Peserta (ID Card Digital)
     */
    public function idCards(Request $request)
    {
        return redirect()->route('peserta.dashboard')->with('info', 'Pencetakan ID Card dikelola langsung oleh Panitia/Admin. Pastikan data dan foto seluruh anggota regu Anda sudah lengkap.');
    }

    /**
     * Laman publik Verifikasi Profil Regu via QR Code
     */
    public function verifikasiRegu($id): View
    {
        $regu = ReguProfile::with(['anggotaRegu', 'season', 'karyaLombas'])->findOrFail($id);

        $anggota = $regu->anggotaRegu()
            ->orderByRaw("CASE WHEN jabatan = 'pinru' THEN 1 WHEN jabatan = 'wapinru' THEN 2 ELSE 3 END")
            ->orderBy('urutan')
            ->get();

        return view('verifikasi-regu', compact('regu', 'anggota'));
    }
}
