<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminSponsorshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $sponsorships = Sponsorship::latest()->get();
        return view('admin.sponsorships.index', compact('sponsorships'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.sponsorships.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tier' => 'required|in:platinum,gold,silver',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_url' => 'nullable|url|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('sponsorships', 'public');
        }

        Sponsorship::create($data);

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sponsorship $sponsorship): View
    {
        return view('admin.sponsorships.edit', compact('sponsorship'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sponsorship $sponsorship): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tier' => 'required|in:platinum,gold,silver',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website_url' => 'nullable|url|max:255',
        ]);

        $data = $request->all();

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($sponsorship->logo) {
                Storage::disk('public')->delete($sponsorship->logo);
            }
            $data['logo'] = $request->file('logo')->store('sponsorships', 'public');
        }

        $sponsorship->update($data);

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship updated successfully.');
    }

    public function destroy(Sponsorship $sponsorship): RedirectResponse
    {
        if ($sponsorship->logo) {
            Storage::disk('public')->delete($sponsorship->logo);
        }
        if ($sponsorship->receipt) {
            Storage::disk('public')->delete($sponsorship->receipt);
        }

        $sponsorship->delete();

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship deleted successfully.');
    }

    /**
     * Approve and define the tier for pending sponsorships.
     */
    public function approve(Request $request, Sponsorship $sponsorship): RedirectResponse
    {
        $request->validate([
            'tier' => 'required|in:platinum,gold,silver',
        ]);

        $sponsorship->update([
            'tier' => $request->tier,
            'is_approved' => true,
        ]);

        return redirect()->route('admin.sponsorships.index')
            ->with('success', 'Sponsorship berhasil disetujui sebagai sponsor ' . ucfirst($request->tier));
    }
}
