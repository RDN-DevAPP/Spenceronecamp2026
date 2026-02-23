<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
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
        return view('sponsorship.daftar');
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
