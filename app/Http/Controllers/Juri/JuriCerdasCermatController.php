<?php

namespace App\Http\Controllers\Juri;

use App\Events\CoinRoyaleUpdated;
use App\Events\RoundStatusChanged;
use App\Events\ScoreUpdated;
use App\Events\SessionUnlocked;
use App\Http\Controllers\Controller;
use App\Models\CerdasCermatAnswer;
use App\Models\CerdasCermatSession;
use App\Models\CerdasCermatSetting;
use App\Models\ExamViolation;
use App\Models\QuizCipherGrid;
use App\Models\QuizCipherWord;
use App\Models\QuizCoinRoyaleBet;
use App\Models\QuizCoinRoyaleRound;
use App\Models\QuizQrQuestion;
use App\Services\CyberQuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JuriCerdasCermatController extends Controller
{
    public function index(CyberQuizService $quizService)
    {
        $this->checkAuthorization();

        $arenaData = $this->buildJuriArenaPayload($quizService);

        return view('juri.cerdas-cermat.index', [
            'arenaData' => $arenaData,
        ]);
    }

    public function state(CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();
        return response()->json([
            'success' => true,
            'data' => $this->buildJuriArenaPayload($quizService),
        ]);
    }

    /**
     * Centralized Real-Time Round Status Control ('LOCKED' | 'ACTIVE' | 'FINISHED').
     */
    public function setRoundStatus(Request $request, int $round, CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();
        if (!in_array($round, [1, 2, 3], true)) {
            return response()->json(['success' => false, 'message' => 'Babak tidak valid.'], 422);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['LOCKED', 'ACTIVE', 'FINISHED'])],
        ]);

        $status = $validated['status'];

        // When activating Babak 3, auto-qualify Top 5 if none verified yet
        if ($round === 3 && $status === 'ACTIVE') {
            $hasVerified = CerdasCermatSession::where('is_verified_round_3', true)->exists();
            if (!$hasVerified) {
                $quizService->initializeTop5CoinRoyale(false);
            }
        }

        $allStatuses = $quizService->setRoundStatus($round, $status);

        $durationMinutes = (int) CerdasCermatSetting::getValue("round_{$round}_duration", 60);
        $endTime = $status === 'ACTIVE' ? now()->addMinutes($durationMinutes)->toIso8601String() : null;
        $remaining = $status === 'ACTIVE' ? $durationMinutes * 60 : 0;

        $statusText = match ($status) {
            'ACTIVE' => "Babak {$round} kini AKTIF! Regu dapat masuk arena.",
            'FINISHED' => "Babak {$round} telah SELESAI / DITUTUP oleh Dewan Juri.",
            default => "Babak {$round} dikunci oleh Dewan Juri.",
        };

        broadcast(new RoundStatusChanged(
            $round,
            $status,
            $endTime,
            $remaining,
            $statusText,
            $allStatuses
        ));

        return response()->json([
            'success' => true,
            'message' => $statusText,
            'round_statuses' => $allStatuses,
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    /**
     * Manually trigger / refresh Top 5 Finalists for Coin Royale (B1 + B2 total, tie-breaker fastest time).
     */
    public function selectFinalists(Request $request, CyberQuizService $quizService)
    {
        $this->checkAuthorization();

        $forceReset = $request->boolean('force_reset', false);
        $top5 = $quizService->initializeTop5CoinRoyale($forceReset);

        if ($top5->isEmpty()) {
            $msg = 'Belum ada regu terdaftar pada Cerdas Cermat untuk dikualifikasi.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $state = $quizService->getCoinRoyaleState(false);
        broadcast(new CoinRoyaleUpdated($state, 'Top 5 Finalis Coin Royale telah ditetapkan!'));

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Top 5 Finalis Coin Royale berhasil disaring dan Saldo Koin Awal telah diisi!',
                'coin_royale' => $quizService->getCoinRoyaleState(true),
            ]);
        }

        return redirect()->back()->with('success', 'Top 5 Finalis Babak Coin Royale berhasil ditetapkan.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // COIN ROYALE REFEREE CONTROLS (9 Rounds: 1,2,4,5,7,8 Normal | 3,6,9 Special)
    // ──────────────────────────────────────────────────────────────────────────

    public function coinRoyaleUpdateQuestion(Request $request, int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();
        if ($roundNumber < 1 || $roundNumber > 9) {
            return response()->json(['success' => false, 'message' => 'Nomor ronde harus 1-9.'], 422);
        }

        $validated = $request->validate([
            'question_text' => 'nullable|string',
            'correct_answer' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'remove_image' => 'nullable|boolean',
        ]);

        QuizCoinRoyaleRound::ensureNineRoundsExist();
        $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->firstOrFail();

        $imagePath = $round->question_image_path;
        if ($request->boolean('remove_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('quiz-coin-royale', 'public');
        }

        $round->update([
            'question_text' => array_key_exists('question_text', $validated) ? $validated['question_text'] : $round->question_text,
            'correct_answer' => array_key_exists('correct_answer', $validated) ? $validated['correct_answer'] : $round->correct_answer,
            'question_image_path' => $imagePath,
        ]);

        if ($round->phase === QuizCoinRoyaleRound::PHASE_QUESTION_OPEN) {
            broadcast(new CoinRoyaleUpdated($quizService->getCoinRoyaleState(false)));
        }

        return response()->json([
            'success' => true,
            'message' => "Soal Ronde {$roundNumber} berhasil diperbarui.",
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    public function coinRoyaleOpenBetting(int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();
        if ($roundNumber < 1 || $roundNumber > 9) {
            return response()->json(['success' => false, 'message' => 'Nomor ronde harus 1-9.'], 422);
        }

        QuizCoinRoyaleRound::ensureNineRoundsExist();

        if (!CerdasCermatSession::where('is_verified_round_3', true)->exists()) {
            $quizService->initializeTop5CoinRoyale(false);
        }

        $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->firstOrFail();
        if ($round->phase === QuizCoinRoyaleRound::PHASE_GRADED) {
            return response()->json(['success' => false, 'message' => "Ronde {$roundNumber} sudah dinilai."], 422);
        }

        // Close any other currently open round back to PENDING if it had no bets
        QuizCoinRoyaleRound::where('round_number', '!=', $roundNumber)
            ->whereIn('phase', [
                QuizCoinRoyaleRound::PHASE_BETTING_OPEN,
                QuizCoinRoyaleRound::PHASE_BETTING_LOCKED,
                QuizCoinRoyaleRound::PHASE_QUESTION_OPEN,
            ])
            ->update(['phase' => QuizCoinRoyaleRound::PHASE_PENDING]);

        $round->update([
            'phase' => QuizCoinRoyaleRound::PHASE_BETTING_OPEN,
            'opened_at' => now(),
        ]);

        $label = $round->is_special ? 'Ronde Spesial (3x / -2x)' : 'Ronde Normal (2x / -1x)';
        $publicState = $quizService->getCoinRoyaleState(false);
        broadcast(new CoinRoyaleUpdated($publicState, "Taruhan Ronde {$roundNumber} ({$label}) DIBUKA!"));

        return response()->json([
            'success' => true,
            'message' => "Fase taruhan Ronde {$roundNumber} dibuka.",
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    public function coinRoyaleLockBetting(int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();
        $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->firstOrFail();

        if ($round->phase === QuizCoinRoyaleRound::PHASE_GRADED) {
            return response()->json(['success' => false, 'message' => "Ronde {$roundNumber} sudah dinilai."], 422);
        }

        DB::transaction(function () use ($round) {
            $finalists = CerdasCermatSession::where('is_verified_round_3', true)->lockForUpdate()->get();
            foreach ($finalists as $session) {
                $existing = QuizCoinRoyaleBet::where('coin_royale_round_id', $round->id)
                    ->where('session_id', $session->id)
                    ->first();

                if (!$existing) {
                    QuizCoinRoyaleBet::create([
                        'coin_royale_round_id' => $round->id,
                        'session_id' => $session->id,
                        'regu_id' => $session->regu_id,
                        'action' => $round->is_special ? QuizCoinRoyaleBet::ACTION_PASS : QuizCoinRoyaleBet::ACTION_BET,
                        'bet_amount' => 0,
                        'balance_before' => (int) $session->coin_balance,
                        'result' => $round->is_special ? QuizCoinRoyaleBet::RESULT_PASS : QuizCoinRoyaleBet::RESULT_PENDING,
                    ]);
                }
            }

            $round->update([
                'phase' => QuizCoinRoyaleRound::PHASE_BETTING_LOCKED,
                'locked_at' => now(),
            ]);
        });

        $publicState = $quizService->getCoinRoyaleState(false);
        broadcast(new CoinRoyaleUpdated($publicState, "Taruhan Ronde {$roundNumber} DIKUNCI!"));

        return response()->json([
            'success' => true,
            'message' => "Taruhan Ronde {$roundNumber} dikunci.",
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    public function coinRoyaleOpenQuestion(int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();
        $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->firstOrFail();

        if ($round->phase === QuizCoinRoyaleRound::PHASE_GRADED) {
            return response()->json(['success' => false, 'message' => "Ronde {$roundNumber} sudah dinilai."], 422);
        }

        // Auto-lock bets first if still open
        if ($round->phase === QuizCoinRoyaleRound::PHASE_BETTING_OPEN) {
            $this->coinRoyaleLockBetting($roundNumber, $quizService);
            $round->refresh();
        }

        $round->update([
            'phase' => QuizCoinRoyaleRound::PHASE_QUESTION_OPEN,
        ]);

        $publicState = $quizService->getCoinRoyaleState(false);
        broadcast(new CoinRoyaleUpdated($publicState, "Soal Ronde {$roundNumber} DITAMPILKAN!"));

        return response()->json([
            'success' => true,
            'message' => "Soal Ronde {$roundNumber} ditampilkan ke peserta & proyektor.",
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    public function coinRoyaleGradeRound(Request $request, int $roundNumber, CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();

        $validated = $request->validate([
            'results' => 'required|array',
            'results.*' => ['required', Rule::in(['CORRECT', 'WRONG'])],
        ]);

        $results = $validated['results'];

        try {
            DB::transaction(function () use ($roundNumber, $results) {
                $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->lockForUpdate()->firstOrFail();

                if ($round->phase === QuizCoinRoyaleRound::PHASE_GRADED) {
                    throw new \RuntimeException("Ronde {$roundNumber} sudah dinilai sebelumnya.");
                }

                $finalists = CerdasCermatSession::where('is_verified_round_3', true)
                    ->lockForUpdate()
                    ->get();

                foreach ($finalists as $session) {
                    $bet = QuizCoinRoyaleBet::where('coin_royale_round_id', $round->id)
                        ->where('session_id', $session->id)
                        ->lockForUpdate()
                        ->first();

                    $saldo = (int) $session->coin_balance;

                    if (!$bet) {
                        $bet = QuizCoinRoyaleBet::create([
                            'coin_royale_round_id' => $round->id,
                            'session_id' => $session->id,
                            'regu_id' => $session->regu_id,
                            'action' => $round->is_special ? QuizCoinRoyaleBet::ACTION_PASS : QuizCoinRoyaleBet::ACTION_BET,
                            'bet_amount' => 0,
                            'balance_before' => $saldo,
                            'result' => QuizCoinRoyaleBet::RESULT_PENDING,
                        ]);
                    }

                    // Special Round + PASS -> balance unchanged
                    if ($round->is_special && $bet->action === QuizCoinRoyaleBet::ACTION_PASS) {
                        $bet->update([
                            'balance_before' => $saldo,
                            'result' => QuizCoinRoyaleBet::RESULT_PASS,
                            'balance_after' => $saldo,
                            'coin_delta' => 0,
                        ]);
                        continue;
                    }

                    $taruhan = (int) $bet->bet_amount;
                    $outcome = $results[(string) $session->id] ?? $results[$session->id] ?? 'WRONG';

                    if ($round->is_special) {
                        // Spesial: Benar => (Saldo - Taruhan) + (Taruhan * 3); Salah => Saldo - (Taruhan * 2)
                        $newSaldo = $outcome === 'CORRECT'
                            ? ($saldo - $taruhan) + ($taruhan * 3)
                            : max(0, $saldo - ($taruhan * 2));
                    } else {
                        // Normal: Benar => (Saldo - Taruhan) + (Taruhan * 2); Salah => Saldo - Taruhan
                        $newSaldo = $outcome === 'CORRECT'
                            ? ($saldo - $taruhan) + ($taruhan * 2)
                            : max(0, $saldo - $taruhan);
                    }

                    $delta = $newSaldo - $saldo;

                    $bet->update([
                        'balance_before' => $saldo,
                        'result' => $outcome,
                        'balance_after' => $newSaldo,
                        'coin_delta' => $delta,
                    ]);

                    $session->update([
                        'coin_balance' => $newSaldo,
                        'score_round_3' => $newSaldo - (int) $session->coin_initial,
                        'status' => $roundNumber === 9 ? 'finished' : 'round_3_ongoing',
                        'is_finalized_round_3' => $roundNumber === 9,
                    ]);
                }

                $round->update([
                    'phase' => QuizCoinRoyaleRound::PHASE_GRADED,
                    'graded_at' => now(),
                ]);
            });
        } catch (\RuntimeException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $publicState = $quizService->getCoinRoyaleState(false);
        broadcast(new CoinRoyaleUpdated($publicState, "Hasil Ronde {$roundNumber} disahkan! Saldo koin diperbarui."));

        return response()->json([
            'success' => true,
            'message' => "Hasil Ronde {$roundNumber} berhasil dihitung dan disiarkan ke Layar Proyektor!",
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    public function coinRoyaleResetAll(CyberQuizService $quizService): JsonResponse
    {
        $this->checkAuthorization();

        DB::transaction(function () use ($quizService) {
            QuizCoinRoyaleBet::query()->delete();
            QuizCoinRoyaleRound::query()->update([
                'phase' => QuizCoinRoyaleRound::PHASE_PENDING,
                'opened_at' => null,
                'locked_at' => null,
                'graded_at' => null,
            ]);
            $quizService->initializeTop5CoinRoyale(true);
        });

        $publicState = $quizService->getCoinRoyaleState(false);
        broadcast(new CoinRoyaleUpdated($publicState, 'Permainan Coin Royale direset dari Ronde 1.'));

        return response()->json([
            'success' => true,
            'message' => 'Coin Royale berhasil direset ke Ronde 1.',
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // LEGACY & SHARED ENDPOINTS (Kept compatible with routes/web.php)
    // ──────────────────────────────────────────────────────────────────────────

    public function qualifiers()
    {
        $this->checkAuthorization();
        $quizService = app(CyberQuizService::class);
        $qualifiers = $quizService->getRankedQualifiers()->map(function ($s) {
            $s->total_score = $s->b1_b2_total;
            $s->time_used_seconds = $s->qualifier_time_seconds === 999999 ? 0 : $s->qualifier_time_seconds;
            return $s;
        });
        $top3Finalists = $qualifiers->take(5);

        return view('juri.cerdas-cermat.qualifiers', compact('qualifiers', 'top3Finalists'));
    }

    public function show($id, $round)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::with('reguProfile')->findOrFail($id);
        $answers = $session->answers()
            ->whereHas('question', fn($q) => $q->where('type', $round == 2 ? 'Isian Singkat' : 'Uraian'))
            ->with('question')
            ->get();

        $isFinalized = $round == 2 ? (bool) $session->is_finalized_round_2 : (bool) $session->is_finalized_round_3;
        return view('juri.cerdas-cermat.grade', compact('session', 'answers', 'round', 'isFinalized'));
    }

    public function grade(Request $request, $id, $round)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);

        if ($round == 2 && $session->is_finalized_round_2) {
            return redirect()->back()->with('error', 'Nilai Babak 2 sudah difinalkan.');
        }
        if ($round == 3 && $session->is_finalized_round_3) {
            return redirect()->back()->with('error', 'Nilai Babak 3 sudah difinalkan.');
        }

        $scores = $request->input('scores', []);
        $totalScore = 0;

        foreach ($scores as $answerId => $score) {
            $answer = CerdasCermatAnswer::with('question')->findOrFail($answerId);
            $maxScore = $answer->question->score;
            $score = max(0, min((float) $score, $maxScore));
            $answer->update(['score' => $score]);
            $totalScore += $score;
        }

        if ($round == 2) {
            $session->update(['score_round_2' => $totalScore, 'is_graded_round_2' => true]);
        } elseif ($round == 3) {
            $session->update(['score_round_3' => $totalScore]);
        }

        broadcast(new ScoreUpdated(
            $session->id,
            $session->regu_id,
            $session->reguProfile?->nama_regu ?? 'Regu',
            (int) $round,
            (int) $session->score_round_1,
            (int) $session->score_round_2,
            (int) $session->score_round_3,
            (int) ($session->score_round_1 + $session->score_round_2 + $session->score_round_3)
        ));

        return redirect()->route('juri.rekap-nilai.index')
            ->with('success', "Penilaian Babak $round berhasil disimpan.");
    }

    public function finalize(Request $request, $id, $round)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);
        if ($round == 2) {
            $session->update(['is_finalized_round_2' => true]);
        } elseif ($round == 3) {
            $session->update(['is_finalized_round_3' => true]);
        }

        return redirect()->route('juri.rekap-nilai.index')->with('success', "Nilai Babak $round difinalkan.");
    }

    public function finalizeRound(Request $request, $round)
    {
        $this->checkAuthorization();
        app(CyberQuizService::class)->setRoundStatus((int) $round, 'FINISHED');
        return redirect()->back()->with('success', "Babak $round ditandai SELESAI.");
    }

    public function startRound(Request $request, $round)
    {
        $this->checkAuthorization();
        $quizService = app(CyberQuizService::class);
        $round = (int) $round;
        if ($round === 3 && !CerdasCermatSession::where('is_verified_round_3', true)->exists()) {
            $quizService->initializeTop5CoinRoyale(false);
        }
        $allStatuses = $quizService->setRoundStatus($round, 'ACTIVE');
        broadcast(new RoundStatusChanged($round, 'ACTIVE', null, 0, "Babak $round dimulai oleh Dewan Juri.", $allStatuses));

        return redirect()->back()->with('success', "Babak $round berhasil diaktifkan!");
    }

    public function resetRound($round)
    {
        $this->checkAuthorization();
        $quizService = app(CyberQuizService::class);
        $allStatuses = $quizService->setRoundStatus((int) $round, 'LOCKED');
        broadcast(new RoundStatusChanged((int) $round, 'LOCKED', null, 0, "Babak $round dikunci ulang oleh Dewan Juri.", $allStatuses));

        return redirect()->back()->with('success', "Status Babak $round dikembalikan ke LOCKED.");
    }

    public function instantUnlock($sessionId)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::with('reguProfile')->findOrFail($sessionId);
        $session->update(['is_locked' => false, 'warning_count' => 0]);

        broadcast(new SessionUnlocked($session->id, $session->regu_id, 'juri', 'Kunci dibuka oleh juri.'));
        return response()->json(['success' => true]);
    }

    public function updateQualifiers(Request $request, $round)
    {
        $this->checkAuthorization();
        return redirect()->back();
    }

    public function destroy($id)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);
        $session->answers()->delete();
        $session->delete();
        return redirect()->back()->with('success', 'Sesi peserta dihapus.');
    }

    public function resetSession($id)
    {
        $this->checkAuthorization();
        $session = CerdasCermatSession::findOrFail($id);
        $session->update([
            'score_round_1' => 0,
            'score_round_2' => 0,
            'score_round_3' => 0,
            'coin_initial' => 0,
            'coin_balance' => 0,
            'status' => 'registered',
        ]);
        return redirect()->back()->with('success', 'Progress sesi regu direset.');
    }

    public function getViolations()
    {
        $this->checkAuthorization();
        $violations = ExamViolation::with('reguProfile')->orderByDesc('created_at')->take(100)->get();
        return response()->json(['success' => true, 'violations' => $violations]);
    }

    public function generateUnlockCode()
    {
        $this->checkAuthorization();
        $code = str_pad((string) mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        CerdasCermatSetting::updateOrCreate(['key' => 'exam_unlock_code'], ['value' => $code]);
        return response()->json(['success' => true, 'code' => $code]);
    }

    private function buildJuriArenaPayload(CyberQuizService $quizService): array
    {
        $qrQuestions = QuizQrQuestion::with('claimedByRegu')
            ->orderBy('id')
            ->get()
            ->map(fn(QuizQrQuestion $q) => [
                'id' => $q->id,
                'code_label' => $q->code_label,
                'question' => $q->question,
                'points' => $q->points,
                'status' => $q->status,
                'claimed_by' => $q->claimedByRegu?->nama_regu,
                'claimed_answer' => $q->claimed_answer,
                'claimed_at' => $q->claimed_at?->toIso8601String(),
            ])->values();

        $cipherGrids = QuizCipherGrid::with('words.claimedByRegu')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn(QuizCipherGrid $g) => [
                'id' => $g->id,
                'title' => $g->title,
                'cipher_type' => $g->cipher_type,
                'grid_size' => $g->grid_size,
                'words' => $g->words->map(fn(QuizCipherWord $w) => [
                    'id' => $w->id,
                    'word' => $w->word,
                    'points' => $w->points,
                    'is_claimed' => $w->is_claimed,
                    'claimed_by' => $w->claimedByRegu?->nama_regu,
                    'claimed_at' => $w->claimed_at?->toIso8601String(),
                ])->values(),
            ])->values();

        $leaderboard = $quizService->getRankedQualifiers()->map(function ($s, $idx) {
            return [
                'rank' => $idx + 1,
                'session_id' => $s->id,
                'regu_id' => $s->regu_id,
                'nama_regu' => $s->reguProfile?->nama_regu ?? 'Regu',
                'nomor_regu' => $s->reguProfile?->nomor_regu ?? '-',
                'jenis' => $s->reguProfile?->jenis ?? 'putra',
                'score_round_1' => (int) ($s->score_round_1 ?? 0),
                'score_round_2' => (int) ($s->score_round_2 ?? 0),
                'b1_b2_total' => (int) ($s->b1_b2_total ?? 0),
                'qualifier_time_seconds' => (int) ($s->qualifier_time_seconds ?? 999999),
                'is_verified_round_3' => (bool) $s->is_verified_round_3,
                'coin_initial' => (int) ($s->coin_initial ?? 0),
                'coin_balance' => (int) ($s->coin_balance ?? 0),
            ];
        })->values();

        return [
            'round_statuses' => $quizService->getRoundStatuses(),
            'qr_questions' => $qrQuestions,
            'cipher_grids' => $cipherGrids,
            'leaderboard' => $leaderboard,
            'coin_royale' => $quizService->getCoinRoyaleState(true),
        ];
    }

    private function checkAuthorization(): void
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized');
        }
        if ($user->isAdmin()) {
            return;
        }
        $hasAssignments = $user->mataLombas()->exists();
        $isAssigned = $user->mataLombas()->where('slug', 'cerdas-cermat')->exists();
        if ($hasAssignments && !$isAssigned) {
            abort(403, 'Anda tidak ditugaskan sebagai juri untuk lomba ini.');
        }
    }
}
