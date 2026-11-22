<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SponsorshipRegistrationController extends Controller
{
    /**
     * Show the public sponsorship registration form.
     */
    public function create(): View
    {
        $bankSettings = [
            'bank_name' => Setting::where('key', 'sponsor_bank_name')->value('value') ?? 'Bank BRI',
            'bank_account' => Setting::where('key', 'sponsor_bank_account')->value('value') ?? '0643-01-002891-53-4',
            'bank_owner' => Setting::where('key', 'sponsor_bank_owner')->value('value') ?? 'Gudep SMPN 1 Cerbon',
            'bank_instruction' => Setting::where('key', 'sponsor_bank_instruction')->value('value') ?? 'Seluruh kontribusi kemitraan dan sponsorship disalurkan langsung melalui rekening bendahara kegiatan resmi:',
            'bank_note' => Setting::where('key', 'sponsor_bank_note')->value('value') ?? 'Harap simpan bukti transfer untuk dilampirkan pada formulir.',
        ];

        $benefitsJson = Setting::where('key', 'sponsor_benefits')->value('value');
        $benefits = $benefitsJson ? json_decode($benefitsJson, true) : [
            [
                'title' => 'Logo Ditampilkan di Beranda Web',
                'description' => 'Logo dan nama usaha tampil langsung pada portal resmi Spencerone Camp Season II.',
            ],
            [
                'title' => 'Pemasangan Banner & Spanduk',
                'description' => 'Hak pemasangan standing banner / umbul-umbul di area utama Bumi Perkemahan.',
            ],
            [
                'title' => 'Piagam Penghargaan Mitra Resmi',
                'description' => 'Panitia memberikan piagam apresiasi resmi bertandatangan Kepala Sekolah & Kamabigus.',
            ],
        ];

        return view('sponsorship.daftar', compact('bankSettings', 'benefits'));
    }

    /**
     * Store a newly created sponsorship request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'pic_name' => 'required|string|max:255',
            'name' => 'required|string|max:255', // Brand name
            'phone' => 'nullable|string|max:20',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'receipt' => 'required|image|mimes:jpeg,png,jpg,pdf|max:3072',
        ]);

        $data = $request->except(['logo', 'receipt']);
        $data['is_approved'] = false;
        $data['tier'] = null;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sponsorships', 'public');
        }

        if ($request->hasFile('receipt')) {
            $data['receipt'] = $request->file('receipt')->store('sponsorships/receipts', 'public');
        }

        Sponsorship::create($data);

        return redirect()->route('home')->with('success', 'Terima kasih! Pendaftaran sponsorship Anda telah diterima dan akan segera dievaluasi oleh panitia.');
    }
}
