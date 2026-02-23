<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    /**
     * Display the schedule page.
     */
    public function __invoke()
    {
        $jadwals = Jadwal::orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal;
            });

        $eventSettings = \App\Models\Setting::whereIn('key', ['event_start_date', 'event_end_date', 'event_location'])->pluck('value', 'key');

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

        return view('jadwal', compact('jadwals', 'eventSettings'));
    }
}
