<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class AdminJadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::orderBy('tanggal')->orderBy('waktu_mulai')->get();
        $settings = \App\Models\Setting::whereIn('key', ['event_start_date', 'event_end_date', 'event_location'])->pluck('value', 'key');
        return view('admin.jadwal.index', compact('jadwals', 'settings'));
    }

    public function create()
    {
        $settings = \App\Models\Setting::whereIn('key', ['event_start_date', 'event_end_date'])->pluck('value', 'key');
        return view('admin.jadwal.create', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date|after_or_equal:event_start_date',
            'event_location' => 'required|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            \App\Models\Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan utama kegiatan berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $settings = \App\Models\Setting::whereIn('key', ['event_start_date', 'event_end_date'])->pluck('value', 'key');
        $min = $settings['event_start_date'] ?? '2000-01-01';
        $max = $settings['event_end_date'] ?? '2099-12-31';

        $validated = $request->validate([
            'hari_ke' => 'required|integer|min:1',
            'tanggal' => "required|date|after_or_equal:$min|before_or_equal:$max",
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'kegiatan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ], [
            'tanggal.after_or_equal' => 'Tanggal harus berada dalam rentang pelaksanaan kegiatan (' . $min . ' s/d ' . $max . ').',
            'tanggal.before_or_equal' => 'Tanggal harus berada dalam rentang pelaksanaan kegiatan (' . $min . ' s/d ' . $max . ').',
        ]);

        Jadwal::create($validated);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Jadwal $jadwal)
    {
        $settings = \App\Models\Setting::whereIn('key', ['event_start_date', 'event_end_date'])->pluck('value', 'key');
        return view('admin.jadwal.edit', compact('jadwal', 'settings'));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $settings = \App\Models\Setting::whereIn('key', ['event_start_date', 'event_end_date'])->pluck('value', 'key');
        $min = $settings['event_start_date'] ?? '2000-01-01';
        $max = $settings['event_end_date'] ?? '2099-12-31';

        $validated = $request->validate([
            'hari_ke' => 'required|integer|min:1',
            'tanggal' => "required|date|after_or_equal:$min|before_or_equal:$max",
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'nullable',
            'kegiatan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
        ], [
            'tanggal.after_or_equal' => 'Tanggal harus berada dalam rentang pelaksanaan kegiatan (' . $min . ' s/d ' . $max . ').',
            'tanggal.before_or_equal' => 'Tanggal harus berada dalam rentang pelaksanaan kegiatan (' . $min . ' s/d ' . $max . ').',
        ]);

        $jadwal->update($validated);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
