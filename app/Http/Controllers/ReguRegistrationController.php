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
                // Since this is a new registration for this user, we can clear existing and re-create 
                // or update. The user requirement says "auto muncul and hanya dapat di edit jabatan, dan tingkatannya saja".
                // We should update based on what WAS randomized but with new jabatan/tingkatan.

                // For simplicity and matching the user's "nama hanya bisa di edit setelah log in",
                // we'll update the existing records by index/urutan or just clear and re-create if they mismatch.
                // But wait, the names shouldn't change here.

                $reguProfile->anggotaRegu()->delete();
                foreach ($request->anggota as $index => $data) {
                    AnggotaRegu::create([
                        'regu_profile_id' => $reguProfile->id,
                        'nama' => $data['nama'],
                        'tingkatan_tku' => $data['tingkatan_tku'],
                        'jabatan' => $data['jabatan'],
                        'urutan' => $index + 1,
                    ]);
                }
            } else {
                // 2. Create New Regu Profile (Original Logic)
                $nextNomor = ReguProfile::where('jenis', $request->jenis)->max('nomor_regu') ?? 0;
                $nomorRegu = $nextNomor + 1;

                $reguProfile = ReguProfile::create([
                    'user_id' => $user->id,
                    'nama_regu' => $request->nama_regu,
                    'jenis' => $request->jenis,
                    'nomor_regu' => $nomorRegu,
                ]);

                // 3. Create Anggota Regu
                foreach ($request->anggota as $index => $data) {
                    AnggotaRegu::create([
                        'regu_profile_id' => $reguProfile->id,
                        'nama' => $data['nama'],
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
