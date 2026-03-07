<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ReguProfile;
use App\Models\Score;
use App\Models\Sponsorship;
use App\Models\FinancialReport;
use App\Models\Setting;
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

        // Fetch countdown target from first schedule entry
        $firstSchedule = \App\Models\Jadwal::orderBy('tanggal')->orderBy('waktu_mulai')->first();
        $countdownTarget = $firstSchedule
            ? \Carbon\Carbon::parse($firstSchedule->tanggal . ' ' . $firstSchedule->waktu_mulai)->format('Y-m-d\TH:i:s')
            : '2026-04-24T13:30:00';

        $eventSettings = Setting::whereIn('key', ['event_start_date', 'event_end_date', 'event_location'])->pluck('value', 'key');

        // Format Display Date Range
        if (isset($eventSettings['event_start_date']) && isset($eventSettings['event_end_date'])) {
            $start = \Carbon\Carbon::parse($eventSettings['event_start_date']);
            $end = \Carbon\Carbon::parse($eventSettings['event_end_date']);

            if ($start->format('M Y') === $end->format('M Y')) {
                $eventSettings['formatted_date'] = $start->format('d') . ' - ' . $end->translatedFormat('d F Y');
            } else {
                $eventSettings['formatted_date'] = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
            }
        } else {
            $eventSettings['formatted_date'] = '24-25 April 2026';
        }

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

        // Fetch all approved sponsorships grouped by tier
        $sponsorships = Sponsorship::where('is_approved', true)->get()->groupBy('tier');

        // -- JUARA UMUM CALCULATION --
        $juaraUmum = [];
        $allRegus = ReguProfile::all();

        // Initialize Juara Umum with all Regus
        foreach ($allRegus as $regu) {
            $juaraUmum[$regu->id] = [
                'reguProfile' => $regu,
                'poin' => 0,
                'emas' => 0,
                'perak' => 0,
                'perunggu' => 0,
            ];
        }

        foreach ($mataLomba as $lomba) {
            $scores = Score::where('mata_lomba_id', $lomba->id)->get();

            // Group by regu
            $reguScores = [];
            foreach ($allRegus as $regu) {
                $reguScores[$regu->id] = [
                    'reguProfile' => $regu,
                    'total_nilai' => 0,
                ];
            }

            foreach ($scores as $score) {
                $reguScores[$score->regu_profile_id]['total_nilai'] += $score->nilai;
            }

            usort($reguScores, function ($a, $b) {
                return $b['total_nilai'] <=> $a['total_nilai'];
            });

            $hasScoresForLomba = $scores->count() > 0;

            $rankList = 1;
            foreach ($reguScores as &$rs) {
                if ($hasScoresForLomba && $rs['total_nilai'] > 0 && $rankList <= 3) {
                    $reguId = $rs['reguProfile']->id;

                    if ($rankList === 1) {
                        $juaraUmum[$reguId]['poin'] += 3;
                        $juaraUmum[$reguId]['emas'] += 1;
                    } elseif ($rankList === 2) {
                        $juaraUmum[$reguId]['poin'] += 2;
                        $juaraUmum[$reguId]['perak'] += 1;
                    } elseif ($rankList === 3) {
                        $juaraUmum[$reguId]['poin'] += 1;
                        $juaraUmum[$reguId]['perunggu'] += 1;
                    }
                }
                $rankList++;
            }
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

        $rankJU = 1;
        foreach ($juaraUmum as &$ju) {
            $ju['peringkat'] = $rankJU++;
        }

        $revealLeaderboard = Setting::where('key', 'reveal_juara_umum')->first()->value ?? '0';
        $showFinancialReport = Setting::where('key', 'show_financial_report')->first()->value ?? '0';

        $activeReport = FinancialReport::where('is_active', true)->latest()->first();

        return view('welcome', compact('mataLomba', 'allRegu', 'sponsorships', 'juaraUmum', 'revealLeaderboard', 'countdownTarget', 'eventSettings', 'activeReport', 'showFinancialReport'));
    }
}
