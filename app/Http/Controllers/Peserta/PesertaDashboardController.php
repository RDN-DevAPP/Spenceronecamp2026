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
        $user = $request->user();
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

        return view('peserta.dashboard', [
            'regu' => $regu,
            'anggota' => $anggota,
            'mataLombas' => $mataLombas,
            'scores' => $scores,
        ]);
    }
    public function createAnggota(Request $request): View
    {
        return view('peserta.create-anggota');
    }

    public function storeAnggota(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $regu = $user->reguProfile;

        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tingkatan_tku' => ['required', 'in:ramu,rakit,terap'],
            'jabatan' => ['required', 'in:pinru,wapinru,anggota'],
        ]);

        if (in_array($validated['jabatan'], ['pinru', 'wapinru'])) {
            $exists = $regu->anggotaRegu()->where('jabatan', $validated['jabatan'])->exists();
            if ($exists) {
                return back()->withInput()->with('error', "Jabatan {$validated['jabatan']} sudah terisi di regu ini.");
            }
        }

        $regu->anggotaRegu()->create([
            'nama' => $validated['nama'],
            'tingkatan_tku' => $validated['tingkatan_tku'],
            'jabatan' => $validated['jabatan'],
            'urutan' => $regu->anggotaRegu()->count() + 1,
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function editAnggota($id): View
    {
        // Ensure the member belongs to the logged-in user's regu
        $user = request()->user();
        $regu = $user->reguProfile;

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        return view('peserta.edit-anggota', ['anggota' => $anggota]);
    }

    public function updateAnggota(Request $request, $id)
    {
        $user = $request->user();
        $regu = $user->reguProfile;

        $anggota = $regu->anggotaRegu()->findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'tingkatan_tku' => ['required', 'in:ramu,rakit,terap'],
            'jabatan' => ['required', 'in:pinru,wapinru,anggota'],
        ]);

        // Check unique constraints for pinru and wapinru if changing role
        if (in_array($validated['jabatan'], ['pinru', 'wapinru']) && $anggota->jabatan !== $validated['jabatan']) {
            $exists = $regu->anggotaRegu()->where('jabatan', $validated['jabatan'])->exists();
            if ($exists) {
                return back()->withInput()->with('error', "Jabatan {$validated['jabatan']} sudah terisi di regu ini.");
            }
        }

        $anggota->update([
            'nama' => $validated['nama'],
            'tingkatan_tku' => $validated['tingkatan_tku'],
            'jabatan' => $validated['jabatan'],
        ]);

        return redirect()->route('peserta.dashboard')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroyAnggota($id)
    {
        $user = request()->user();
        $regu = $user->reguProfile;

        $anggota = $regu->anggotaRegu()->findOrFail($id);
        $anggota->delete();

        return redirect()->route('peserta.dashboard')->with('success', 'Anggota berhasil dihapus.');
    }
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'mata_lomba_slug' => ['required', 'string'],
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'], // Max 5MB
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();
        /** @var \App\Models\ReguProfile $regu */
        $regu = $user->reguProfile;

        if (!$regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        $slug = $request->input('mata_lomba_slug');
        $file = $request->photo;
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

        if (!$column) {
            return back()->with('error', 'Mata lomba tidak valid untuk upload foto ini.');
        }

        // Store file
        $path = $file->store('lomba-photos', 'public');

        $data = [$column => $path];

        if ($creatorColumn && $creatorId) {
            // Verify creator belongs to regu
            $isMember = $regu->anggotaRegu()->where('id', $creatorId)->exists();
            if ($isMember) {
                $data[$creatorColumn] = $creatorId;
            }
        }

        // Update database
        $regu->update($data);

        return back()->with('success', 'Foto berhasil diunggah.');
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

        // Update database
        $regu->update([
            $column => null,
        ]);

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}
