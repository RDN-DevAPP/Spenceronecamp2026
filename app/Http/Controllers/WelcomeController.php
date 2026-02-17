<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ReguProfile;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Landing page LT-I Spencerone Camp 2026 (tanpa login).
     */
    public function __invoke(): View
    {
        // Fetch all competitions
        $mataLomba = MataLomba::orderBy('urutan')->get(['id', 'nama', 'slug', 'urutan']);

        // Fetch all teams with their scores and calculate rankings
        $allRegu = ReguProfile::with('scores')->get()
            ->map(function ($regu) {
                $totalNilai = $regu->scores->sum('nilai');
                return (object) [
                    'id' => $regu->id,
                    'nama_regu' => $regu->nama_regu,
                    'jenis' => $regu->jenis,
                    'nomor_regu' => $regu->nomor_regu,
                    'total_nilai' => $totalNilai,
                ];
            })
            ->sortByDesc('total_nilai')
            ->values();

        // Add ranking
        $rank = 1;
        foreach ($allRegu as $index => $regu) {
            $regu->rank = $rank++;
        }

        // Fetch sponsorships grouped by tier
        $sponsorships = Sponsorship::all()->groupBy('tier');

        return view('welcome', compact('mataLomba', 'allRegu', 'sponsorships'));
    }
}
