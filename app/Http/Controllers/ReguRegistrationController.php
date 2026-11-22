<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Season;
use App\Models\ReguProfile;
use App\Models\AnggotaRegu;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReguRegistrationController extends Controller
{
    public function create(): View
    {
        $activeSeason = Season::where('is_active', true)->first();
        return view('auth.register-regu', compact('activeSeason'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_regu' => 'required|string|max:255',
            'jenis' => ['required', Rule::in(['putra', 'putri'])],
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'jumlah_anggota' => 'required|integer|min:7|max:10',
            'anggota' => 'required|array',
            'anggota.*.nama' => 'required|string|max:255',
            'anggota.*.nisn' => 'nullable|string|max:20',
            'anggota.*.kelas' => 'required|integer|min:7|max:9',
            'anggota.*.tingkatan_tku' => ['required', Rule::in(['ramu', 'rakit', 'terap'])],
            'anggota.*.jabatan' => ['required', Rule::in(['pinru', 'wapinru', 'anggota'])],
        ]);

        $activeSeason = Season::where('is_active', true)->first();
        $seasonId = $activeSeason ? $activeSeason->id : null;

        try {
            DB::beginTransaction();

            // 1. Create User Account
            $user = User::create([
                'name' => $request->nama_regu,
                'username' => $request->username,
                'email' => $request->username . '@lt1spencerone.com',
                'password' => Hash::make($request->password),
                'role' => User::ROLE_REGU,
            ]);

            // 2. Generate Nomor Regu
            if ($request->jenis === 'putra') {
                $max = ReguProfile::where('jenis', 'putra')
                    ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
                    ->max('nomor_regu');
                $nomorRegu = $max ? $max + 2 : 1;
            } else {
                $max = ReguProfile::where('jenis', 'putri')
                    ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
                    ->max('nomor_regu');
                $nomorRegu = $max ? $max + 2 : 2;
            }

            // Nomor Kavling default (misal "KAV-" + nomor_regu terformat)
            $nomorKavling = 'KAV-' . str_pad($nomorRegu, 2, '0', STR_PAD_LEFT);

            // 3. Create Regu Profile
            $reguProfile = ReguProfile::create([
                'season_id' => $seasonId,
                'user_id' => $user->id,
                'nama_regu' => $request->nama_regu,
                'jenis' => $request->jenis,
                'nomor_regu' => $nomorRegu,
                'nomor_kavling' => $nomorKavling,
            ]);

            // 4. Sort and create Anggota Regu & Sync to Siswa table
            $sortedAnggota = collect($request->anggota)->sort(function ($a, $b) {
                $order = ['pinru' => 1, 'wapinru' => 2, 'anggota' => 3];
                return $order[$a['jabatan']] <=> $order[$b['jabatan']];
            })->values();

            $jenisKelamin = $request->jenis === 'putra' ? 'L' : 'P';

            foreach ($sortedAnggota as $index => $data) {
                $nisn = !empty($data['nisn']) ? trim($data['nisn']) : null;

                // Create Anggota Regu
                AnggotaRegu::create([
                    'regu_profile_id' => $reguProfile->id,
                    'nama' => $data['nama'],
                    'nisn' => $nisn,
                    'nta' => $nisn,
                    'kelas' => $data['kelas'],
                    'tingkatan_tku' => $data['tingkatan_tku'],
                    'jabatan' => $data['jabatan'],
                    'urutan' => $index + 1,
                ]);

                // Sync to Master Siswa (separated per class 7, 8, 9)
                Siswa::updateOrCreate(
                    [
                        'nama' => trim($data['nama']),
                        'kelas' => (int) $data['kelas'],
                        'season_id' => $seasonId,
                    ],
                    [
                        'nisn' => $nisn,
                    'nta' => $nisn,
                        'jenis_kelamin' => $jenisKelamin,
                        'tingkatan_tku' => $data['tingkatan_tku'],
                        'regu_profile_id' => $reguProfile->id,
                    ]
                );
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', "Pendaftaran Regu {$reguProfile->nama_regu} (Nomor: {$reguProfile->nomor_regu}, Kavling: {$nomorKavling}) Berhasil! Silakan login.")
                ->with('reg_username', $request->username)
                ->with('reg_password', $request->password);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
    }
}
