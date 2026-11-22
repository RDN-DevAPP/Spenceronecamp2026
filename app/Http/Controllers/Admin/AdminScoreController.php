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
     * Setujui permohonan buka kunci nilai dari Juri.
     */
    public function approveUnlock(Request $request, Score $score)
    {
        $oldStatus = $score->status;
        $reason = $score->unlock_reason;

        $score->update([
            'status' => Score::STATUS_DRAFT,
            'unlock_requested' => false,
            'unlock_reason' => null,
        ]);

        \App\Services\ScoreAuditService::log(
            $score,
            'unlock_approved',
            $score->nilai,
            $score->nilai,
            $oldStatus,
            Score::STATUS_DRAFT,
            'Admin menyetujui pembukaan kunci nilai (Alasan awal juri: ' . $reason . ')',
            $request
        );

        return back()->with('success', 'Permintaan buka kunci disetujui. Status nilai kembali menjadi DRAFT dan dapat disunting oleh Juri.');
    }

    /**
     * Tolak permohonan buka kunci nilai dari Juri.
     */
    public function rejectUnlock(Request $request, Score $score)
    {
        $score->update([
            'unlock_requested' => false,
        ]);

        \App\Services\ScoreAuditService::log(
            $score,
            'unlock_rejected',
            $score->nilai,
            $score->nilai,
            $score->status,
            $score->status,
            'Admin menolak permohonan buka kunci nilai',
            $request
        );

        return back()->with('success', 'Permintaan buka kunci nilai ditolak. Nilai tetap terkunci.');
    }

    /**
     * Verifikasi nilai secara resmi oleh Admin / Ketua Dewan Juri.
     */
    public function verify(Request $request, Score $score)
    {
        $oldStatus = $score->status;

        $score->update([
            'status' => Score::STATUS_VERIFIED,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'unlock_requested' => false,
        ]);

        \App\Services\ScoreAuditService::log(
            $score,
            'verified',
            $score->nilai,
            $score->nilai,
            $oldStatus,
            Score::STATUS_VERIFIED,
            'Nilai diverifikasi dan disahkan oleh Administrator',
            $request
        );

        return back()->with('success', 'Nilai telah resmi diverifikasi (VERIFIED).');
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
