<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReguProfile;
use App\Models\AnggotaRegu;
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
        $availableTeams = ReguProfile::whereHas('user', function ($query) {
            $query->where('role', User::ROLE_ADMIN);
        })->orderBy('nomor_regu')->get();

        return view('auth.register-regu', compact('availableTeams'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama_regu' => 'required|string|max:255',
            'jenis' => ['required', Rule::in(['putra', 'putri'])],
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'jumlah_anggota' => 'required|integer|min:7|max:10',
            'regu_profile_id' => 'nullable|exists:regu_profiles,id',
            'anggota' => 'required|array',
            'anggota.*.nama' => 'required|string|max:255',
            'anggota.*.kelas' => 'required|integer|min:7|max:9',
            'anggota.*.tingkatan_tku' => ['required', Rule::in(['ramu', 'rakit', 'terap'])],
            'anggota.*.jabatan' => ['required', Rule::in(['pinru', 'wapinru', 'anggota'])],
        ]);

        try {
            DB::beginTransaction();

            // 1. Create User
            $user = User::create([
                'name' => $request->nama_regu,
                'username' => $request->username,
                'email' => $request->username . '@lt1spencerone.com',
                'password' => Hash::make($request->password),
                'role' => User::ROLE_REGU,
            ]);

            if ($request->regu_profile_id) {
                // 2. Claim Existing Regu Profile
                $reguProfile = ReguProfile::findOrFail($request->regu_profile_id);
                $reguProfile->update([
                    'user_id' => $user->id,
                    'nama_regu' => $request->nama_regu,
                ]);

                // 3. Update Existing Anggota Regu
                $reguProfile->anggotaRegu()->delete();

                // Sort members by jabatan (pinru, wapinru, then others)
                $sortedAnggota = collect($request->anggota)->sort(function ($a, $b) {
                    $order = ['pinru' => 1, 'wapinru' => 2, 'anggota' => 3];
                    return $order[$a['jabatan']] <=> $order[$b['jabatan']];
                })->values();

                foreach ($sortedAnggota as $index => $data) {
                    AnggotaRegu::create([
                        'regu_profile_id' => $reguProfile->id,
                        'nama' => $data['nama'],
                        'kelas' => $data['kelas'],
                        'tingkatan_tku' => $data['tingkatan_tku'],
                        'jabatan' => $data['jabatan'],
                        'urutan' => $index + 1,
                    ]);
                }
            } else {
                // 2. Create New Regu Profile (Original Logic)
                if ($request->jenis === 'putra') {
                    $max = ReguProfile::where('jenis', 'putra')->max('nomor_regu');
                    $nomorRegu = $max ? $max + 2 : 1;
                } else {
                    $max = ReguProfile::where('jenis', 'putri')->max('nomor_regu');
                    $nomorRegu = $max ? $max + 2 : 2;
                }

                $reguProfile = ReguProfile::create([
                    'user_id' => $user->id,
                    'nama_regu' => $request->nama_regu,
                    'jenis' => $request->jenis,
                    'nomor_regu' => $nomorRegu,
                ]);

                // 3. Create Anggota Regu
                // Sort members by jabatan (pinru, wapinru, then others)
                $sortedAnggota = collect($request->anggota)->sort(function ($a, $b) {
                    $order = ['pinru' => 1, 'wapinru' => 2, 'anggota' => 3];
                    return $order[$a['jabatan']] <=> $order[$b['jabatan']];
                })->values();

                foreach ($sortedAnggota as $index => $data) {
                    AnggotaRegu::create([
                        'regu_profile_id' => $reguProfile->id,
                        'nama' => $data['nama'],
                        'kelas' => $data['kelas'],
                        'tingkatan_tku' => $data['tingkatan_tku'],
                        'jabatan' => $data['jabatan'],
                        'urutan' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Pendaftaran Berhasil! Silakan login menggunakan akun yang telah dibuat.')
                ->with('reg_username', $request->username)
                ->with('reg_password', $request->password);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
    }
}
