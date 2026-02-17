<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaPosterController extends Controller
{
    /**
     * Upload berkas Poster Digital.
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'poster' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'], // 5MB
        ], [
            'poster.required' => 'Pilih file poster terlebih dahulu.',
            'poster.mimes' => 'Format file: JPG, PNG, atau PDF.',
            'poster.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $user = $request->user();
        $regu = $user->reguProfile;

        if (! $regu) {
            return back()->with('error', 'Profil regu tidak ditemukan.');
        }

        // Hapus file lama jika ada
        if ($regu->poster_digital_path && Storage::disk('public')->exists($regu->poster_digital_path)) {
            Storage::disk('public')->delete($regu->poster_digital_path);
        }

        $path = $request->file('poster')->store('poster-digital', 'public');
        $regu->update(['poster_digital_path' => $path]);

        return back()->with('success', 'Poster digital berhasil diunggah.');
    }
}
