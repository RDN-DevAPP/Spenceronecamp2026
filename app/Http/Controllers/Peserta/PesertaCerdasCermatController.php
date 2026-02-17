<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\CerdasCermatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaCerdasCermatController extends Controller
{
    public function index()
    {
        $regu = Auth::user()->reguProfile;
        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Anda belum terdaftar dalam regu.');
        }

        // Check if session exists
        $session = CerdasCermatSession::where('regu_id', $regu->id)->first();

        if (!$session) {
            $anggota = $regu->anggotaRegu; // Assuming relationship exists
            return view('peserta.cerdas-cermat.index', compact('anggota')); // Registration view
        }

        // Direct to the correct round view based on status/round
        if ($session->status === 'registered') {
            // Show waiting screen or "Start Round 1" button
            return view('peserta.cerdas-cermat.round-1-intro', compact('session'));
        }

        if ($session->status === 'round_1_ongoing') {
            return redirect()->route('peserta.cerdas-cermat.round-1');
        }

        // Fetch Leaderboard (Usage of exclusive logic: show only the latest relevant one)
        $leaderboardRound1 = null;
        $leaderboardRound2 = null;
        $leaderboardFinal = null;

        if ($session->status === 'finished') {
            // Show Final Leaderboard
            $leaderboardFinal = CerdasCermatSession::with('reguProfile')
                ->selectRaw('*, (COALESCE(score_round_1, 0) + COALESCE(score_round_2, 0) + COALESCE(score_round_3, 0)) as total_score')
                ->orderByDesc('total_score')
                ->get();
        } elseif (in_array($session->status, ['round_2_done', 'round_3_ongoing'])) {
            // Show Round 2 Leaderboard
            $leaderboardRound2 = CerdasCermatSession::with('reguProfile')
                ->whereNotNull('score_round_2')
                ->orderByDesc('score_round_2')
                ->get();
        } elseif (in_array($session->status, ['round_1_done', 'round_2_ongoing'])) {
            // Show Round 1 Leaderboard
            $leaderboardRound1 = CerdasCermatSession::with('reguProfile')
                ->whereNotNull('score_round_1')
                ->orderByDesc('score_round_1')
                ->get();
        }

        return view('peserta.cerdas-cermat.dashboard', compact('session', 'leaderboardRound1', 'leaderboardRound2', 'leaderboardFinal'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name_1' => 'required|string|max:255',
            'name_2' => 'required|string|max:255|different:name_1',
            'name_3' => 'required|string|max:255|different:name_1|different:name_2',
        ], [
            'name_2.different' => 'Nama peserta 2 tidak boleh sama dengan peserta 1.',
            'name_3.different' => 'Nama peserta 3 tidak boleh sama dengan peserta 1 atau 2.',
        ]);

        $regu = Auth::user()->reguProfile;

        // Prevent duplicate registration
        if (CerdasCermatSession::where('regu_id', $regu->id)->exists()) {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Anda sudah terdaftar untuk sesi ini.');
        }

        CerdasCermatSession::create([
            'regu_id' => $regu->id,
            'name_1' => $request->name_1,
            'name_2' => $request->name_2,
            'name_3' => $request->name_3,
            'status' => 'registered',
            'current_round' => 1,
        ]);

        return redirect()->route('peserta.cerdas-cermat.index')->with('success', 'Pendaftaran berhasil. Silakan bersiap untuk Babak 1.');
    }

    public function round1()
    {
        $regu = Auth::user()->reguProfile;
        $session = CerdasCermatSession::where('regu_id', $regu->id)->firstOrFail();

        if ($session->status === 'round_1_done' || $session->status === 'finished') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Anda sudah menyelesaikan Babak 1.');
        }

        // Ensure we have the latest data to prevent race conditions
        $session->refresh();

        // Update status if it's their first time entering
        if ($session->status === 'registered') {
            $session->status = 'round_1_ongoing';
            if ($session->start_time_round_1 === null) {
                $session->start_time_round_1 = now();
            }
            $session->save();
        }

        // Double safety: if status is ongoing but start time is null (should not happen, but safe fallback)
        if ($session->status === 'round_1_ongoing' && $session->start_time_round_1 === null) {
            $session->start_time_round_1 = now();
            $session->save();
        }

        // Apply penalty for refresh (3 seconds)
        $session->increment('penalty_seconds', 3);

        // Calculate remaining time (60 minutes)
        $startTime = $session->start_time_round_1;
        // Calculate remaining time
        $durationMinutes = (int) \App\Models\CerdasCermatSetting::getValue('round_1_duration', 60);
        $durationSeconds = $durationMinutes * 60;

        $elapsedSeconds = now()->diffInSeconds($startTime);
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds - $session->penalty_seconds);

        $questions = \App\Models\CerdasCermatQuestion::where('type', 'Pilihan Ganda')->inRandomOrder()->get();

        return view('peserta.cerdas-cermat.round-1', compact('session', 'questions', 'remainingSeconds'));
    }

    public function submitRound1(Request $request)
    {
        $regu = Auth::user()->reguProfile;
        $session = CerdasCermatSession::where('regu_id', $regu->id)->firstOrFail();

        if ($session->status !== 'round_1_ongoing') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Sesi tidak valid.');
        }

        $answers = $request->input('answers') ?? [];
        $totalScore = 0;

        // Fetch ALL questions for Round 1
        $questions = \App\Models\CerdasCermatQuestion::where('type', 'Pilihan Ganda')->get();

        foreach ($questions as $question) {
            $answerText = $answers[$question->id] ?? null; // Null if unanswered

            $isCorrect = false;
            $score = 0;

            if ($answerText !== null) {
                // Determine correctness only if answered
                $isCorrect = strtolower(trim($answerText)) === strtolower(trim($question->correct_answer));
                $score = $isCorrect ? $question->score : 0;
            } else {
                // Explicitly Wrong if unanswered
                $isCorrect = false;
                $score = 0;
            }

            $totalScore += $score;

            \App\Models\CerdasCermatAnswer::create([
                'session_id' => $session->id,
                'question_id' => $question->id,
                'answer_text' => $answerText, // Can be null
                'is_correct' => $isCorrect,
                'score' => $score,
            ]);
        }

        $session->update([
            'score_round_1' => $totalScore,
            'status' => 'round_1_done',
            'end_time_round_1' => now(),
        ]);

        return redirect()->route('peserta.cerdas-cermat.index')->with('success', 'Babak 1 selesai. Skor Anda: ' . $totalScore);
    }

    public function round2()
    {
        $regu = Auth::user()->reguProfile;
        $session = CerdasCermatSession::where('regu_id', $regu->id)->firstOrFail();

        // Check if finished round 1
        if ($session->status === 'registered' || $session->status === 'round_1_ongoing') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Selesaikan Babak 1 terlebih dahulu.');
        }

        if ($session->status === 'round_2_done') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Anda sudah menyelesaikan Babak 2.');
        }

        // Check if ALL teams have finished Round 1
        $pendingSessions = CerdasCermatSession::whereIn('status', ['registered', 'round_1_ongoing'])->count();
        if ($pendingSessions > 0) {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Menunggu semua regu menyelesaikan Babak 1.');
        }

        // Check qualification (Top 5)
        // We count how many sessions have a higher score in round 1
        $higherScores = CerdasCermatSession::where('score_round_1', '>', $session->score_round_1)->count();
        if ($higherScores >= 5 && $session->status == 'round_1_done') {
            // Not in top 5
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Maaf, Anda tidak lolos ke Babak 2. Peringkat Anda tidak masuk 5 besar.');
        }

        // Check verification by Juri
        if (!$session->is_verified_round_2) {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Menunggu verifikasi Juri untuk lanjut ke Babak 2. Silakan lapor ke meja Juri.');
        }

        // Ensure we have the latest data
        $session->refresh();

        // Update status if entering for first time
        if ($session->status === 'round_1_done') {
            $session->status = 'round_2_ongoing';
            if ($session->start_time_round_2 === null) {
                $session->start_time_round_2 = now();
            }
            $session->save();
        }

        // Double safety for Round 2
        if ($session->status === 'round_2_ongoing' && $session->start_time_round_2 === null) {
            $session->start_time_round_2 = now();
            $session->save();
        }

        // Apply penalty for refresh (3 seconds)
        $session->increment('penalty_seconds', 3);

        // Calculate remaining time (35 minutes)
        $startTime = $session->start_time_round_2;
        // Calculate remaining time
        $durationMinutes = (int) \App\Models\CerdasCermatSetting::getValue('round_2_duration', 35);
        $durationSeconds = $durationMinutes * 60;
        $elapsedSeconds = now()->diffInSeconds($startTime);
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds - $session->penalty_seconds);

        $questions = \App\Models\CerdasCermatQuestion::where('type', 'Isian Singkat')->inRandomOrder()->get();

        return view('peserta.cerdas-cermat.round-2', compact('session', 'questions', 'remainingSeconds'));
    }

    public function submitRound2(Request $request)
    {
        $regu = Auth::user()->reguProfile;
        $session = CerdasCermatSession::where('regu_id', $regu->id)->firstOrFail();

        if ($session->status !== 'round_2_ongoing') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Sesi tidak valid.');
        }

        $answers = $request->input('answers') ?? [];
        $totalScore = 0;

        // Fetch ALL questions for Round 2
        $questions = \App\Models\CerdasCermatQuestion::where('type', 'Isian Singkat')->get();

        foreach ($questions as $question) {
            $answerText = $answers[$question->id] ?? null;

            $isCorrect = false;
            $score = 0;

            if ($answerText !== null) {
                // Strict case-insensitive comparison for Short Answer
                $isCorrect = strtolower(trim($answerText)) === strtolower(trim($question->correct_answer));
                $score = $isCorrect ? $question->score : 0;
            }

            $totalScore += $score;

            \App\Models\CerdasCermatAnswer::create([
                'session_id' => $session->id,
                'question_id' => $question->id,
                'answer_text' => $answerText,
                'is_correct' => $isCorrect,
                'score' => $score,
            ]);
        }

        $session->update([
            'score_round_2' => $totalScore,
            'status' => 'round_2_done',
            'end_time_round_2' => now(),
        ]);

        return redirect()->route('peserta.cerdas-cermat.index')->with('success', 'Babak 2 selesai. Skor Anda: ' . $totalScore);
    }

    public function round3()
    {
        $regu = Auth::user()->reguProfile;
        $session = CerdasCermatSession::where('regu_id', $regu->id)->firstOrFail();

        if ($session->status === 'registered' || $session->status === 'round_1_ongoing' || $session->status === 'round_1_done' || $session->status === 'round_2_ongoing') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Selesaikan babak sebelumnya terlebih dahulu.');
        }

        if ($session->status === 'finished') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Anda sudah menyelesaikan lomba.');
        }

        // Check if ALL teams from Round 2 have finished (active participants of R2)
        // We check if anyone is currently doing Round 2
        $pendingSessionsR2 = CerdasCermatSession::where('status', 'round_2_ongoing')->count();
        if ($pendingSessionsR2 > 0) {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Menunggu semua regu menyelesaikan Babak 2.');
        }

        // Check if Round 2 has been graded/verified by Juri
        if (!$session->is_graded_round_2) {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Menunggu penilaian Juri untuk hasil Babak 2.');
        }

        // Check qualification (Top 3) based on Round 2 Score
        $higherScores = CerdasCermatSession::where('score_round_2', '>', $session->score_round_2)->count();
        if ($higherScores >= 3 && $session->status == 'round_2_done') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Maaf, Anda tidak lolos ke Babak Final. Peringkat Anda tidak masuk 3 besar.');
        }

        // Ensure we have the latest data
        $session->refresh();

        if ($session->status === 'round_2_done') {
            $session->status = 'round_3_ongoing';
            if ($session->start_time_round_3 === null) {
                $session->start_time_round_3 = now();
            }
            $session->save();
        }

        // Double safety for Round 3
        if ($session->status === 'round_3_ongoing' && $session->start_time_round_3 === null) {
            $session->start_time_round_3 = now();
            $session->save();
        }

        // Apply penalty for refresh (3 seconds)
        $session->increment('penalty_seconds', 3);

        // Calculate remaining time (30 minutes)
        $startTime = $session->start_time_round_3;
        // Calculate remaining time
        $durationMinutes = (int) \App\Models\CerdasCermatSetting::getValue('round_3_duration', 30);
        $durationSeconds = $durationMinutes * 60;
        $elapsedSeconds = now()->diffInSeconds($startTime);
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds - $session->penalty_seconds);

        $questions = \App\Models\CerdasCermatQuestion::where('type', 'Uraian')->inRandomOrder()->get();

        return view('peserta.cerdas-cermat.round-3', compact('session', 'questions', 'remainingSeconds'));
    }

    public function submitRound3(Request $request)
    {
        $regu = Auth::user()->reguProfile;
        $session = CerdasCermatSession::where('regu_id', $regu->id)->firstOrFail();

        if ($session->status !== 'round_3_ongoing') {
            return redirect()->route('peserta.cerdas-cermat.index')->with('error', 'Sesi tidak valid.');
        }

        $answers = $request->input('answers') ?? [];

        // Fetch ALL questions for Round 3
        $questions = \App\Models\CerdasCermatQuestion::where('type', 'Uraian')->get();

        foreach ($questions as $question) {
            $answerText = $answers[$question->id] ?? null;

            \App\Models\CerdasCermatAnswer::create([
                'session_id' => $session->id,
                'question_id' => $question->id,
                'answer_text' => $answerText,
                'is_correct' => false, // Manual scoring required
                'score' => 0,
            ]);
        }

        $session->update([
            'status' => 'finished',
            'end_time_round_3' => now(),
        ]);

        return redirect()->route('peserta.cerdas-cermat.index')->with('success', 'Jawaban Babak Final berhasil dikirim. Silakan tunggu pengumuman.');
    }
}
