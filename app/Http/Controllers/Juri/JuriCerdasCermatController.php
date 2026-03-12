<?php

namespace App\Http\Controllers\Juri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CerdasCermatSession;
use App\Models\CerdasCermatAnswer;
use App\Models\CerdasCermatSetting;

class JuriCerdasCermatController extends Controller
{
    public function index()
    {
        $this->checkAuthorization();
        $sessions = CerdasCermatSession::with(['reguProfile', 'answers'])
            ->latest()
            ->get();

        // Ready Round 1: All registered sessions
        $readyRound1 = CerdasCermatSession::with('reguProfile')
            ->whereIn('status', ['registered', 'round_1_ongoing'])
            ->get();

        // Ready Round 2: Verified and finished round 1
        $readyRound2 = CerdasCermatSession::with('reguProfile')
            ->where('is_verified_round_2', true)
            ->whereIn('status', ['round_1_done', 'round_2_ongoing'])
            ->get();

        // Ready Round 3: Finished round 2, graded, and top 3 based on round 2 score
        $readyRound3 = CerdasCermatSession::with('reguProfile')
            ->where('is_graded_round_2', true)
            ->whereIn('status', ['round_2_done', 'round_3_ongoing'])
            ->orderByDesc('score_round_2')
            ->take(3)
            ->get();

        // Get round start settings
        $round1Started = CerdasCermatSetting::getValue('round_1_started', false);
        $round2Started = CerdasCermatSetting::getValue('round_2_started', false);
        $round3Started = CerdasCermatSetting::getValue('round_3_started', false);

        $totalVerifiedRound2 = CerdasCermatSession::where('is_verified_round_2', true)
            ->whereIn('status', ['round_1_done', 'round_2_ongoing'])
            ->count();

        $totalGradedRound2 = CerdasCermatSession::where('is_graded_round_2', true)
            ->whereIn('status', ['round_2_done', 'round_3_ongoing'])
            ->count();

        return view('juri.cerdas-cermat.index', compact(
            'sessions',
            'readyRound1',
            'readyRound2',
            'readyRound3',
            'round1Started',
            'round2Started',
            'round3Started',
            'totalVerifiedRound2',
            'totalGradedRound2'
        ));
    }

    public function show($id, $round)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::with('reguProfile')->findOrFail($id);

        $answers = $session->answers()
            ->whereHas('question', function ($query) use ($round) {
                $type = $round == 2 ? 'Isian Singkat' : 'Uraian';
                $query->where('type', $type);
            })
            ->with('question')
            ->get();

        // Check if this round is finalized
        $isFinalized = false;
        if ($round == 2) {
            $isFinalized = (bool) $session->is_finalized_round_2;
        } elseif ($round == 3) {
            $isFinalized = (bool) $session->is_finalized_round_3;
        }

        return view('juri.cerdas-cermat.grade', compact('session', 'answers', 'round', 'isFinalized'));
    }

    public function qualifiers()
    {
        $this->checkAuthorization();
        $qualifiers = CerdasCermatSession::with('reguProfile')
            ->whereNotNull('score_round_1')
            ->orderByDesc('score_round_1')
            ->take(5)
            ->get();

        return view('juri.cerdas-cermat.qualifiers', compact('qualifiers'));
    }

    public function verifyRound2($id)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);
        $session->update(['is_verified_round_2' => true]);

        return redirect()->back()->with('success', 'Peserta berhasil diverifikasi untuk Babak 2.');
    }

    public function grade(Request $request, $id, $round)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);

        // Check if finalized
        if ($round == 2 && $session->is_finalized_round_2) {
            return redirect()->back()->with('error', 'Nilai Babak 2 sudah difinalkan dan tidak dapat diubah.');
        }
        if ($round == 3 && $session->is_finalized_round_3) {
            return redirect()->back()->with('error', 'Nilai Babak 3 sudah difinalkan dan tidak dapat diubah.');
        }

        $scores = $request->input('scores'); // array of answer_id => score

        $totalScore = 0;

        foreach ($scores as $answerId => $score) {
            $answer = CerdasCermatAnswer::with('question')->findOrFail($answerId);

            // Clamp score to valid range (0 to max) - Fix for Bug #8
            $maxScore = $answer->question->score;
            $score = max(0, min((float) $score, $maxScore));

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

    public function finalize(Request $request, $id, $round)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);

        if ($round == 2) {
            if (!$session->is_graded_round_2) {
                return redirect()->back()->with('error', 'Nilai Babak 2 belum dinilai. Silakan beri nilai terlebih dahulu.');
            }
            $session->update(['is_finalized_round_2' => true]);
        } elseif ($round == 3) {
            if (is_null($session->score_round_3)) {
                return redirect()->back()->with('error', 'Nilai Babak 3 belum dinilai. Silakan beri nilai terlebih dahulu.');
            }
            $session->update(['is_finalized_round_3' => true]);
        }

        return redirect()->route('juri.cerdas-cermat.index')
            ->with('success', "Nilai Babak $round untuk " . $session->reguProfile->nama_regu . " telah difinalkan.");
    }

    public function startRound(Request $request, $round)
    {
        $this->checkAuthorization();
        $validRounds = [1, 2, 3];
        $round = (int) $round;
        if (!in_array($round, $validRounds)) {
            return redirect()->back()->with('error', 'Babak tidak valid.');
        }

        $key = "round_{$round}_started";

        // Check if already started
        if (CerdasCermatSetting::getValue($key, false)) {
            return redirect()->back()->with('error', "Babak $round sudah dimulai sebelumnya.");
        }

        // Enforce sequence and quota
        if ($round === 2) {
            if (!CerdasCermatSetting::getValue('round_1_started', false)) {
                return redirect()->back()->with('error', 'Babak 1 harus dimulai terlebih dahulu sebelum memulai Babak 2.');
            }

            // Check if at least 5 participants are verified for Round 2
            $verifiedCount = CerdasCermatSession::where('is_verified_round_2', true)
                ->whereIn('status', ['round_1_done', 'round_2_ongoing'])
                ->count();

            if ($verifiedCount < 5) {
                return redirect()->back()->with('error', 'Minimal 5 peserta harus sudah diverifikasi untuk Babak 2 sebelum babak ini dapat dimulai.');
            }
        }

        if ($round === 3) {
            if (!CerdasCermatSetting::getValue('round_2_started', false)) {
                return redirect()->back()->with('error', 'Babak 2 harus dimulai terlebih dahulu sebelum memulai Babak 3.');
            }

            // Check if there are at least 3 graded participants from round 2
            $gradedCount = CerdasCermatSession::where('is_graded_round_2', true)
                ->whereIn('status', ['round_2_done', 'round_3_ongoing'])
                ->count();

            if ($gradedCount < 3) {
                return redirect()->back()->with('error', 'Minimal 3 peserta Babak 2 harus sudah dinilai sebelum Babak 3 dapat dimulai.');
            }
        }

        CerdasCermatSetting::updateOrCreate(
            ['key' => $key],
            ['value' => '1']
        );

        return redirect()->back()->with('success', "Babak $round berhasil dimulai! Peserta sekarang dapat mengerjakan soal.");
    }

    public function destroy($id)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);

        // Delete all answers associated with this session
        $session->answers()->delete();

        // Delete the session itself
        $session->delete();

        return redirect()->back()->with('success', 'Sesi peserta berhasil dibatalkan/dihapus.');
    }

    public function destroyAll()
    {
        $this->checkAuthorization();

        // Use a transaction for safety
        \Illuminate\Support\Facades\DB::transaction(function () {
            CerdasCermatAnswer::truncate();
            CerdasCermatSession::truncate();
        });

        return redirect()->back()->with('success', 'Seluruh sesi dan jawaban peserta berhasil dihapus/direset.');
    }

    public function resetRound($round)
    {
        $this->checkAuthorization();
        $validRounds = [1, 2, 3];
        $round = (int) $round;

        if (!in_array($round, $validRounds)) {
            return redirect()->back()->with('error', 'Babak tidak valid.');
        }

        $key = "round_{$round}_started";
        CerdasCermatSetting::where('key', $key)->delete();

        return redirect()->back()->with('success', "Status Babak $round berhasil direset. Juri dapat memulai ulang babak ini.");
    }

    public function resetSession($id)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);

        // Delete all answers associated with this session
        $session->answers()->delete();

        // Reset progress but keep registration info
        $session->update([
            'current_round' => 1,
            'score_round_1' => null,
            'score_round_2' => null,
            'score_round_3' => null,
            'status' => 'registered',
            'penalty_seconds' => 0,
            'start_time_round_1' => null,
            'end_time_round_1' => null,
            'start_time_round_2' => null,
            'end_time_round_2' => null,
            'start_time_round_3' => null,
            'end_time_round_3' => null,
            'is_verified_round_2' => false,
            'is_graded_round_2' => false,
            'is_finalized_round_2' => false,
            'is_finalized_round_3' => false,
        ]);

        return redirect()->back()->with('success', 'Progress sesi regu ' . $session->reguProfile->nama_regu . ' telah direset ke awal.');
    }

    private function checkAuthorization()
    {
        $user = auth()->user();
        if (!$user->isAdmin()) {
            $isAssigned = $user->mataLombas()->where('slug', 'cerdas-cermat')->exists();
            if (!$isAssigned) {
                abort(403, 'Anda tidak ditugaskan sebagai juri untuk lomba ini.');
            }
        }
    }
}
