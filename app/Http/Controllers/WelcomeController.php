<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\ReguProfile;
use App\Models\Score;
use App\Models\Sponsorship;
use App\Models\FinancialReport;
use App\Models\Setting;
use App\Services\ActiveSeasonService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    /**
     * Landing page Spencerone Camp Season II (tanpa login).
     */
    public function __invoke(Request $request): View
    {
        $activeSeasonService = app(ActiveSeasonService::class);
        $activeSeason = $activeSeasonService->getActiveSeason();
        $seasonId = $activeSeason?->id;

        // Fetch 5 official scored competitions
        $mataLomba = MataLomba::where('is_scored', true)->orderBy('urutan')->get();

        // Fetch event settings from admin
        $eventSettings = Setting::whereIn('key', ['event_start_date', 'event_end_date', 'event_location'])->pluck('value', 'key')->toArray();

        // Fallback location to active season if not set
        if (empty($eventSettings['event_location']) && $activeSeason?->location) {
            $eventSettings['event_location'] = $activeSeason->location;
        }

        // Format Date Range
        if (isset($eventSettings['event_start_date']) && isset($eventSettings['event_end_date'])) {
            $start = \Carbon\Carbon::parse($eventSettings['event_start_date']);
            $end = \Carbon\Carbon::parse($eventSettings['event_end_date']);

            if ($start->format('M Y') === $end->format('M Y')) {
                $eventSettings['formatted_date'] = $start->format('d') . ' - ' . $end->translatedFormat('d F Y');
            } else {
                $eventSettings['formatted_date'] = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
            }
        } elseif ($activeSeason?->start_date && $activeSeason?->end_date) {
            $start = $activeSeason->start_date;
            $end = $activeSeason->end_date;
            $eventSettings['formatted_date'] = $start->format('d') . ' - ' . $end->translatedFormat('d F Y');
        } else {
            $eventSettings['formatted_date'] = '15 - 17 September 2026';
        }

        // 1. TANGGAL: Mengikuti tanggal yang disimpan pada user admin (event_start_date)
        $targetDate = !empty($eventSettings['event_start_date']) 
            ? $eventSettings['event_start_date'] 
            : ($activeSeason?->start_date?->format('Y-m-d') ?? '2026-09-15');

        // 2. JAM: Disesuaikan dengan jam pada jadwal pertama yang ditentukan pada user admin
        $firstSchedule = \App\Models\Jadwal::orderBy('tanggal')->orderBy('waktu_mulai')->first();
        $targetTime = '08:00:00';
        if ($firstSchedule && !empty($firstSchedule->waktu_mulai)) {
            $rawTime = trim($firstSchedule->waktu_mulai);
            $targetTime = strlen($rawTime) === 5 ? $rawTime . ':00' : $rawTime;
        }

        // 3. Kombinasi DateTime Target Countdown
        $countdownCarbon = \Carbon\Carbon::parse($targetDate . ' ' . $targetTime);
        $countdownTarget = $countdownCarbon->format('Y-m-d\TH:i:s');

        // Simpan metadata ke eventSettings
        $eventSettings['event_date_iso'] = $countdownTarget;
        $eventSettings['first_schedule_activity'] = $firstSchedule?->kegiatan;
        $eventSettings['first_schedule_time_formatted'] = substr($targetTime, 0, 5) . ' WITA';
        $eventSettings['countdown_target_readable'] = $countdownCarbon->translatedFormat('d F Y') . ' pukul ' . substr($targetTime, 0, 5) . ' WITA';

        $allRegu = ReguProfile::with('scores')
            ->when($seasonId, fn($q) => $q->where('season_id', $seasonId))
            ->get()
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
        foreach ($allRegu as $regu) {
            $regu->rank = $rank++;
        }

        // Fetch approved sponsorships
        $sponsorships = Sponsorship::where('is_approved', true)
            ->when($seasonId, fn($q) => $q->where(fn($sub) => $sub->where('season_id', $seasonId)->orWhereNull('season_id')))
            ->get()
            ->groupBy('tier');

        // -- JUARA UMUM CALCULATION --
        $allRegus = ReguProfile::when($seasonId, fn($q) => $q->where('season_id', $seasonId))->get();

        $juaraUmum = [];
        foreach ($allRegus as $regu) {
            $juaraUmum[$regu->id] = [
                'reguProfile' => $regu,
                'poin' => 0,
                'emas' => 0,
                'perak' => 0,
                'perunggu' => 0,
                'total_nilai' => 0,
            ];
        }

        foreach ($mataLomba as $lomba) {
            $scores = Score::where('mata_lomba_id', $lomba->id)->get();

            $reguScores = [];
            foreach ($allRegus as $regu) {
                $reguScores[$regu->id] = [
                    'reguProfile' => $regu,
                    'total_nilai' => 0,
                ];
            }

            foreach ($scores as $score) {
                if (isset($reguScores[$score->regu_profile_id])) {
                    $reguScores[$score->regu_profile_id]['total_nilai'] += $score->nilai;
                }
                if (isset($juaraUmum[$score->regu_profile_id])) {
                    $juaraUmum[$score->regu_profile_id]['total_nilai'] += $score->nilai;
                }
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

        $sortLeaderboard = function (&$list) {
            usort($list, function ($a, $b) {
                if ($a['poin'] !== $b['poin']) {
                    return $b['poin'] <=> $a['poin'];
                }
                if ($a['emas'] !== $b['emas']) {
                    return $b['emas'] <=> $a['emas'];
                }
                if ($a['perak'] !== $b['perak']) {
                    return $b['perak'] <=> $a['perak'];
                }
                if ($a['perunggu'] !== $b['perunggu']) {
                    return $b['perunggu'] <=> $a['perunggu'];
                }
                return $b['total_nilai'] <=> $a['total_nilai'];
            });

            $rankJU = 1;
            foreach ($list as &$ju) {
                $ju['peringkat'] = $rankJU++;
            }
        };

        // Overall Juara Umum
        $juaraUmumAll = array_values($juaraUmum);
        $sortLeaderboard($juaraUmumAll);

        // Putra
        $juaraUmumPutra = array_values(array_filter($juaraUmum, fn($item) => $item['reguProfile']->jenis === 'putra'));
        $sortLeaderboard($juaraUmumPutra);

        // Putri
        $juaraUmumPutri = array_values(array_filter($juaraUmum, fn($item) => $item['reguProfile']->jenis === 'putri'));
        $sortLeaderboard($juaraUmumPutri);

        $revealLeaderboard = Setting::where('key', 'reveal_juara_umum')->first()->value ?? '0';
        $showFinancialReport = Setting::where('key', 'show_financial_report')->first()->value ?? '0';

        $activeReport = FinancialReport::where('is_active', true)->latest()->first();

        return view('welcome', compact(
            'activeSeason',
            'mataLomba',
            'allRegu',
            'sponsorships',
            'revealLeaderboard',
            'countdownTarget',
            'eventSettings',
            'activeReport',
            'showFinancialReport',
            'juaraUmumAll',
            'juaraUmumPutra',
            'juaraUmumPutri'
        ));
    }
}
