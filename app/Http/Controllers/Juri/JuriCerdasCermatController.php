<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CerdasCermatSession;
use App\Models\CerdasCermatAnswer;

class JuriCerdasCermatController extends Controller
{
    public function index()
    {
        // Get sessions that have completed round 2 or 3
        $sessions = CerdasCermatSession::whereIn('status', ['round_2_done', 'round_3_ongoing', 'round_3_done', 'finished'])
            ->with(['reguProfile', 'answers'])
            ->latest()
            ->get();

        return view('juri.cerdas-cermat.index', compact('sessions'));
    }


    public function show($id, $round)
    {
        $session = CerdasCermatSession::with('reguProfile')->findOrFail($id);

        $answers = $session->answers()
            ->whereHas('question', function ($query) use ($round) {
                // Determine question type based on round
                $type = $round == 2 ? 'Isian Singkat' : 'Uraian';
                $query->where('type', $type);
            })
            ->with('question')
            ->get();

        return view('juri.cerdas-cermat.grade', compact('session', 'answers', 'round'));
    }

    public function qualifiers()
    {
        $qualifiers = CerdasCermatSession::with('reguProfile')
            ->whereNotNull('score_round_1')
            ->orderByDesc('score_round_1')
            ->take(5)
            ->get();

        return view('juri.cerdas-cermat.qualifiers', compact('qualifiers'));
    }

    public function verifyRound2($id)
    {
        $session = CerdasCermatSession::findOrFail($id);
        $session->update(['is_verified_round_2' => true]);

        return redirect()->back()->with('success', 'Peserta berhasil diverifikasi untuk Babak 2.');
    }

    public function grade(Request $request, $id, $round)
    {
        $session = CerdasCermatSession::findOrFail($id);
        $scores = $request->input('scores'); // array of answer_id => score

        $totalScore = 0;

        foreach ($scores as $answerId => $score) {
            $answer = CerdasCermatAnswer::with('question')->findOrFail($answerId);

            // Validate score vs question max point
            $maxScore = $answer->question->score;
            if ($score > $maxScore) {
                return redirect()->back()->with('error', "Nilai untuk soal '" . substr($answer->question->question, 0, 30) . "...' tidak boleh melebihi $maxScore.");
            }

            $answer->update(['score' => $score]);
            $totalScore += $score;
        }

        // Update session score based on round
        if ($round == 2) {
            $session->update([
                'score_round_2' => $totalScore,
                'is_graded_round_2' => true
            ]);
        } elseif ($round == 3) {
            $session->update(['score_round_3' => $totalScore]);
        }

        return redirect()->route('juri.cerdas-cermat.index')
            ->with('success', "Penilaian Babak $round untuk " . $session->reguProfile->nama_regu . " berhasil disimpan.");
    }
}
