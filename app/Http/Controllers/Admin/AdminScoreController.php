<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataLomba;
use App\Models\Score;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminScoreController extends Controller
{
    /**
     * Display a paginated list of scores for verification with filtering.
     */
    public function index(Request $request): View
    {
        $allMataLomba = MataLomba::all();
        $juri = User::where('role', User::ROLE_JURI)->get();

        $scoresQuery = Score::with(['reguProfile', 'juri', 'mataLomba']);

        if ($request->has('mata_lomba_id') && $request->mata_lomba_id != '') {
            $scoresQuery->where('mata_lomba_id', $request->mata_lomba_id);
        }

        if ($request->has('juri_id') && $request->juri_id != '') {
            $scoresQuery->where('juri_id', $request->juri_id);
        }

        // Order by latest submission first
        $scores = $scoresQuery->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.scores.index', compact('scores', 'allMataLomba', 'juri'));
    }

    /**
     * Delete the specified score.
     */
    public function destroy(Score $score)
    {
        $score->update(['delete_requested' => true]);

        return back()->with('success', 'Pengajuan penghapusan nilai telah dikirim ke Juri terkait untuk diverifikasi.');
    }
}
