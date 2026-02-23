<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\Score;
use App\Models\User;
use App\Models\ReguProfile;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with scores.
     */
    public function index(Request $request): View
    {
        $allMataLomba = MataLomba::all();
        $mataLomba = $allMataLomba;

        $allRegus = ReguProfile::all();


        $leaderboards = [];
        $juaraUmum = [];
        $allMataLombaFiltered = [];

        // Initialize Juara Umum with all Regus
        foreach ($allRegus as $regu) {
            $juaraUmum[$regu->id] = [
                'reguProfile' => $regu,
                'poin' => 0,
                'lomba_poin' => [], // Map of lomba.id => points (3,2,1)
                'emas' => 0,
                'perak' => 0,
                'perunggu' => 0,
            ];
        }

        foreach ($mataLomba as $lomba) {
            $allMataLombaFiltered[] = $lomba;

            $scoresQuery = Score::with(['reguProfile', 'juri'])
                ->where('mata_lomba_id', $lomba->id);


            $scores = $scoresQuery->get();

            // Group by regu
            $reguScores = [];

            // Pre-fill with all Regus
            foreach ($allRegus as $regu) {
                $reguScores[$regu->id] = [
                    'reguProfile' => $regu,
                    'juri_scores' => [],
                    'total_nilai' => 0,
                ];
            }

            foreach ($scores as $score) {
                $reguId = $score->regu_profile_id;

                // Track juri scores
                $reguScores[$reguId]['juri_scores'][$score->juri_id] = $score->nilai;
                $reguScores[$reguId]['total_nilai'] += $score->nilai;
            }

            // Convert to array and sort by total_nilai descending
            usort($reguScores, function ($a, $b) {
                return $b['total_nilai'] <=> $a['total_nilai'];
            });

            // If there are no scores at all in this specific Lomba among ALL regus, skip Juara Umum points for this Lomba to prevent ties allocating Emas everywhere.
            $hasScoresForLomba = $scores->count() > 0;

            // Assign ranks
            $rank = 1;
            foreach ($reguScores as &$rs) {
                $rs['peringkat'] = $rank;

                // Add to juara umum if rank is 1, 2, or 3, AND the lomba actually has scores evaluated 
                // We check if their total_nilai is > 0 or if there are any scores basically
                if ($hasScoresForLomba && $rs['total_nilai'] > 0 && $rank <= 3) {
                    $reguId = $rs['reguProfile']->id;

                    if ($rank === 1) {
                        $juaraUmum[$reguId]['poin'] += 3;
                        $juaraUmum[$reguId]['emas'] += 1;
                        $juaraUmum[$reguId]['lomba_poin'][$lomba->id] = 3;
                    } elseif ($rank === 2) {
                        $juaraUmum[$reguId]['poin'] += 2;
                        $juaraUmum[$reguId]['perak'] += 1;
                        $juaraUmum[$reguId]['lomba_poin'][$lomba->id] = 2;
                    } elseif ($rank === 3) {
                        $juaraUmum[$reguId]['poin'] += 1;
                        $juaraUmum[$reguId]['perunggu'] += 1;
                        $juaraUmum[$reguId]['lomba_poin'][$lomba->id] = 1;
                    }
                }

                $rank++;
            }

            $uniqueJuriIds = collect($scores)->pluck('juri_id')->unique()->values();

            $leaderboards[$lomba->id] = [
                'mata_lomba' => $lomba,
                'leaderboard' => $reguScores,
                'juri_columns' => $uniqueJuriIds,
            ];
        }

        // Sort juara umum by poin DESC, then emas DESC, perak DESC, perunggu DESC
        usort($juaraUmum, function ($a, $b) {
            if ($a['poin'] !== $b['poin']) {
                return $b['poin'] <=> $a['poin'];
            }
            if ($a['emas'] !== $b['emas']) {
                return $b['emas'] <=> $a['emas'];
            }
            if ($a['perak'] !== $b['perak']) {
                return $b['perak'] <=> $a['perak'];
            }
            return $b['perunggu'] <=> $a['perunggu'];
        });

        $rank = 1;
        foreach ($juaraUmum as &$ju) {
            $ju['peringkat'] = $rank++;
        }



        $revealLeaderboard = Setting::where('key', 'reveal_juara_umum')->first()->value ?? '0';

        return view('admin.dashboard', compact('leaderboards', 'juaraUmum', 'allMataLomba', 'allMataLombaFiltered', 'revealLeaderboard'));
    }

    public function toggleRevealJuaraUmum(Request $request)
    {
        $setting = Setting::firstOrCreate(['key' => 'reveal_juara_umum']);
        $setting->update(['value' => $request->reveal ? '1' : '0']);

        return back()->with('success', 'Status penampilan Juara Umum berhasil diperbarui.');
    }


}
