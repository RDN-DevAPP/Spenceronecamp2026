<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with scores.
     */
    public function index(Request $request): View
    {
        $query = Score::with(['reguProfile', 'mataLomba', 'juri']);

        // Filter by Mata Lomba
        if ($request->has('mata_lomba_id') && $request->mata_lomba_id != '') {
            $query->where('mata_lomba_id', $request->mata_lomba_id);
        }

        // Filter by Juri
        if ($request->has('juri_id') && $request->juri_id != '') {
            $query->where('juri_id', $request->juri_id);
        }

        $scores = $query->latest()->get();

        $mataLomba = MataLomba::all();
        $juri = User::where('role', User::ROLE_JURI)->get();

        return view('admin.dashboard', compact('scores', 'mataLomba', 'juri'));
    }

    public function destroy(Score $score)
    {
        $score->delete();

        return back()->with('success', 'Penilaian berhasil dihapus.');
    }
}
