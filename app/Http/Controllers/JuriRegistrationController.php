<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MataLomba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JuriRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register-juri');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'kode_mata_lomba' => 'required|string|size:6|alpha_num',
        ], [
            'kode_mata_lomba.required' => 'Kode mata lomba wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.unique' => 'Email sudah digunakan.',
            'password.confirmed' => 'Konfirmasi sandi tidak cocok.',
            'password.min' => 'Sandi minimal 8 karakter.',
        ]);

        $mataLomba = MataLomba::where('kode', strtoupper($request->kode_mata_lomba))->first();

        if (!$mataLomba) {
            return back()
                ->withErrors(['kode_mata_lomba' => 'Kode mata lomba tidak valid. Silakan periksa kembali.'])
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => User::ROLE_JURI,
            ]);

            $user->mataLombas()->attach($mataLomba->id);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login sebagai Juri ' . $mataLomba->nama . '.')
                ->with('reg_username', $request->username)
                ->with('reg_password', $request->password);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())->withInput();
        }
    }
}
