<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Models\Setting;
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
            'pic_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $logoPath = $request->hasFile('logo')
            ? $request->file('logo')->store('sponsorships', 'public')
            : null;

        Sponsorship::create([
            'name' => $request->name,
            'tier' => $request->tier,
            'website_url' => $request->website_url,
            'logo' => $logoPath,
            'pic_name' => $request->pic_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_approved' => true,
        ]);

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
            'pic_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $data = [
            'name' => $request->name,
            'tier' => $request->tier,
            'website_url' => $request->website_url,
            'pic_name' => $request->pic_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

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

    /**
     * Show form to manage bank account & sponsor benefits settings.
     */
    public function settings(): View
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

        return view('admin.sponsorships.settings', compact('bankSettings', 'benefits'));
    }

    /**
     * Update bank account & sponsor benefits settings.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'bank_account' => 'required|string|max:100',
            'bank_owner' => 'required|string|max:150',
            'bank_instruction' => 'nullable|string|max:500',
            'bank_note' => 'nullable|string|max:255',
            'benefits' => 'nullable|array',
            'benefits.*.title' => 'nullable|string|max:255',
            'benefits.*.description' => 'nullable|string|max:500',
        ]);

        Setting::updateOrCreate(['key' => 'sponsor_bank_name'], ['value' => $validated['bank_name']]);
        Setting::updateOrCreate(['key' => 'sponsor_bank_account'], ['value' => $validated['bank_account']]);
        Setting::updateOrCreate(['key' => 'sponsor_bank_owner'], ['value' => $validated['bank_owner']]);
        Setting::updateOrCreate(['key' => 'sponsor_bank_instruction'], ['value' => $validated['bank_instruction'] ?? '']);
        Setting::updateOrCreate(['key' => 'sponsor_bank_note'], ['value' => $validated['bank_note'] ?? '']);

        // Process benefits (filter out empty titles)
        $cleanBenefits = [];
        if (!empty($validated['benefits']) && is_array($validated['benefits'])) {
            foreach ($validated['benefits'] as $b) {
                if (!empty(trim($b['title'] ?? ''))) {
                    $cleanBenefits[] = [
                        'title' => trim($b['title']),
                        'description' => trim($b['description'] ?? ''),
                    ];
                }
            }
        }

        Setting::updateOrCreate(['key' => 'sponsor_benefits'], ['value' => json_encode(array_values($cleanBenefits))]);

        return redirect()->route('admin.sponsorships.settings')
            ->with('success', 'Pengaturan nomor rekening bank dan keuntungan sponsor berhasil disimpan.');
    }
}
