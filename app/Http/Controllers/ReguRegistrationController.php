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
    /**
     * Show the application registration form.
     */
    public function create(): View
    {
        return view('auth.register-regu');
    }

    /**
     * Handle a registration request for the application.
     */
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
            'anggota.*.tingkatan_tku' => ['required', Rule::in(['ramu', 'rakit', 'terap'])],
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

            // 2. Create Regu Profile
            $reguProfile = ReguProfile::create([
                'user_id' => $user->id,
                'nama_regu' => $request->nama_regu,
                'jenis' => $request->jenis,
                'nomor_regu' => $request->nomor_regu ?? '-',
            ]);

            // 3. Create Anggota Regu
            foreach ($request->anggota as $index => $data) {
                $jabatan = 'anggota';
                if ($index == 0) {
                    $jabatan = 'pinru';
                } elseif ($index == 1) {
                    $jabatan = 'wapinru';
                }

                AnggotaRegu::create([
                    'regu_profile_id' => $reguProfile->id,
                    'nama' => $data['nama'],
                    'tingkatan_tku' => $data['tingkatan_tku'],
                    'jabatan' => $jabatan,
                    'urutan' => $index + 1,
                ]);
            }

            DB::commit();

            return redirect()->route('login')->with('success', 'Pendaftaran Berhasil! Silakan login menggunakan akun yang telah dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
    }
}
