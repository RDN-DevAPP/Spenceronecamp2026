<?php

namespace App\Http\Controllers\Peserta;

use App\Events\CipherWordClaimed;
use App\Events\CoinRoyaleUpdated;
use App\Events\QrQuestionClaimed;
use App\Events\ScoreUpdated;
use App\Http\Controllers\Controller;
use App\Models\CerdasCermatSession;
use App\Models\CerdasCermatSetting;
use App\Models\QuizCipherGrid;
use App\Models\QuizCipherWord;
use App\Models\QuizCoinRoyaleBet;
use App\Models\QuizCoinRoyaleRound;
use App\Models\QuizQrAttempt;
use App\Models\QuizQrQuestion;
use App\Models\ReguProfile;
use App\Services\CyberQuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PesertaCerdasCermatController extends Controller
{
    public function index(CyberQuizService $quizService)
    {
        $regu = $this->requireEligibleRegu();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        $session = $quizService->ensureSessionForRegu($regu);
        $roundStatuses = $quizService->getRoundStatuses();
        $ranked = $quizService->getRankedQualifiers();
        $myRankIndex = $ranked->search(fn($s) => $s->id === $session->id);

        $anggota = $regu->anggotaRegu()
            ->orderBy('urutan')
            ->orderBy('id')
            ->get(['id', 'nama', 'jabatan', 'kelas']);

        return view('peserta.cerdas-cermat.dashboard', [
            'regu' => $regu,
            'session' => $session,
            'anggota' => $anggota,
            'roundStatuses' => $roundStatuses,
            'myRank' => $myRankIndex !== false ? $myRankIndex + 1 : null,
            'qrStats' => [
                'total' => QuizQrQuestion::count(),
                'available' => QuizQrQuestion::where('status', 'available')->count(),
                'my_claimed' => QuizQrQuestion::where('claimed_by_session_id', $session->id)->count(),
            ],
            'cipherStats' => [
                'total_words' => QuizCipherWord::count(),
                'available_words' => QuizCipherWord::where('is_claimed', false)->count(),
                'my_claimed' => QuizCipherWord::where('claimed_by_session_id', $session->id)->count(),
            ],
            'coinRoyale' => $quizService->getCoinRoyaleState(false),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BABAK 1: QR-HUNT TRIVIA (SCAN QR -> 1 SOAL = 1 REGU TERCEPAT)
    // ──────────────────────────────────────────────────────────────────────────

    public function round1(CyberQuizService $quizService)
    {
        $regu = $this->requireEligibleRegu();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(1) !== CyberQuizService::STATUS_ACTIVE) {
            return redirect()->route('peserta.cerdas-cermat.index')
                ->with('error', 'Babak 1 (QR-Hunt Trivia) belum dibuka atau sudah ditutup oleh Dewan Juri.');
        }

        $session = $quizService->ensureSessionForRegu($regu);
        if (!$session->start_time_round_1) {
            $session->update([
                'start_time_round_1' => now(),
                'status' => 'round_1_ongoing',
            ]);
        }

        $recentClaims = QuizQrQuestion::with('claimedByRegu')
            ->where('status', 'claimed')
            ->orderByDesc('claimed_at')
            ->take(20)
            ->get()
            ->map(fn(QuizQrQuestion $q) => [
                'id' => $q->id,
                'code_label' => $q->code_label,
                'points' => $q->points,
                'regu_id' => $q->claimed_by_regu_id,
                'regu_name' => $q->claimedByRegu?->nama_regu ?? 'Regu',
                'claimed_at' => $q->claimed_at?->toIso8601String(),
            ])->values();

        return view('peserta.cerdas-cermat.round-1', [
            'regu' => $regu,
            'session' => $session,
            'totalQr' => QuizQrQuestion::count(),
            'availableQr' => QuizQrQuestion::where('status', 'available')->count(),
            'recentClaims' => $recentClaims,
        ]);
    }

    public function scanQr(Request $request, CyberQuizService $quizService): JsonResponse
    {
        $regu = $this->requireEligibleReguJson();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(1) !== CyberQuizService::STATUS_ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'Babak 1 belum aktif atau sudah ditutup oleh Dewan Juri.',
            ], 403);
        }

        $validated = $request->validate([
            'qr_token' => 'required|string|max:120',
        ]);

        $token = trim($validated['qr_token']);
        $session = $quizService->ensureSessionForRegu($regu);

        return DB::transaction(function () use ($token, $session, $regu) {
            $qr = QuizQrQuestion::with('claimedByRegu')
                ->where('qr_token', $token)
                ->orWhere('code_label', $token)
                ->lockForUpdate()
                ->first();

            if (!$qr) {
                return response()->json([
                    'success' => false,
                    'status' => 'not_found',
                    'message' => 'QR Code tidak dikenali sebagai soal resmi QR-Hunt.',
                ], 404);
            }

            if ($qr->status === 'claimed') {
                $claimerName = $qr->claimedByRegu?->nama_regu ?? 'Regu Lain';

                QuizQrAttempt::create([
                    'qr_question_id' => $qr->id,
                    'session_id' => $session->id,
                    'regu_id' => $regu->id,
                    'is_correct' => false,
                    'points_awarded' => 0,
                    'status' => 'rejected_claimed',
                ]);

                return response()->json([
                    'success' => false,
                    'status' => 'claimed',
                    'claimed_by' => $claimerName,
                    'message' => "Soal ini sudah diklaim oleh Regu {$claimerName}!",
                ], 409);
            }

            QuizQrAttempt::create([
                'qr_question_id' => $qr->id,
                'session_id' => $session->id,
                'regu_id' => $regu->id,
                'is_correct' => false,
                'points_awarded' => 0,
                'status' => 'opened',
            ]);

            return response()->json([
                'success' => true,
                'status' => 'available',
                'question' => [
                    'id' => $qr->id,
                    'code_label' => $qr->code_label,
                    'qr_token' => $qr->qr_token,
                    'question' => $qr->question,
                    'image_url' => $qr->image_path ? asset('storage/' . $qr->image_path) : null,
                    'points' => $qr->points,
                ],
            ]);
        });
    }

    public function answerQr(Request $request, CyberQuizService $quizService): JsonResponse
    {
        $regu = $this->requireEligibleReguJson();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(1) !== CyberQuizService::STATUS_ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'Babak 1 sudah tidak aktif.',
            ], 403);
        }

        $validated = $request->validate([
            'qr_token' => 'required|string|max:120',
            'answer' => 'required|string|max:255',
        ]);

        $token = trim($validated['qr_token']);
        $rawAnswer = trim($validated['answer']);
        $session = $quizService->ensureSessionForRegu($regu);

        $result = DB::transaction(function () use ($token, $rawAnswer, $session, $regu) {
            $qr = QuizQrQuestion::with('claimedByRegu')
                ->where('qr_token', $token)
                ->orWhere('code_label', $token)
                ->lockForUpdate()
                ->first();

            if (!$qr) {
                return ['code' => 404, 'payload' => ['success' => false, 'message' => 'Soal QR tidak ditemukan.']];
            }

            if ($qr->status === 'claimed') {
                $claimerName = $qr->claimedByRegu?->nama_regu ?? 'Regu Lain';
                return [
                    'code' => 409,
                    'payload' => [
                        'success' => false,
                        'status' => 'claimed',
                        'claimed_by' => $claimerName,
                        'message' => "Soal ini sudah diklaim oleh Regu {$claimerName}!",
                    ],
                ];
            }

            if (!$qr->isAnswerCorrect($rawAnswer)) {
                QuizQrAttempt::create([
                    'qr_question_id' => $qr->id,
                    'session_id' => $session->id,
                    'regu_id' => $regu->id,
                    'submitted_answer' => $rawAnswer,
                    'is_correct' => false,
                    'points_awarded' => 0,
                    'status' => 'wrong',
                ]);

                return [
                    'code' => 422,
                    'payload' => [
                        'success' => false,
                        'is_correct' => false,
                        'message' => 'Jawaban belum tepat! Coba periksa kembali jawaban Anda.',
                    ],
                ];
            }

            // Lock regu session & award points atomically
            $lockedSession = CerdasCermatSession::where('id', $session->id)->lockForUpdate()->firstOrFail();

            $claimedAt = now();
            $qr->update([
                'status' => 'claimed',
                'claimed_by_regu_id' => $regu->id,
                'claimed_by_session_id' => $lockedSession->id,
                'claimed_answer' => $rawAnswer,
                'claimed_at' => $claimedAt,
            ]);

            $newScore = (int) $lockedSession->score_round_1 + (int) $qr->points;
            $lockedSession->update([
                'score_round_1' => $newScore,
                'start_time_round_1' => $lockedSession->start_time_round_1 ?? $claimedAt,
                'round_1_last_activity_at' => $claimedAt,
                'end_time_round_1' => $claimedAt,
                'status' => 'round_1_ongoing',
            ]);

            QuizQrAttempt::create([
                'qr_question_id' => $qr->id,
                'session_id' => $lockedSession->id,
                'regu_id' => $regu->id,
                'submitted_answer' => $rawAnswer,
                'is_correct' => true,
                'points_awarded' => $qr->points,
                'status' => 'correct',
            ]);

            return [
                'code' => 200,
                'broadcast' => [
                    'questionId' => $qr->id,
                    'codeLabel' => $qr->code_label,
                    'reguId' => $regu->id,
                    'reguName' => $regu->nama_regu,
                    'points' => (int) $qr->points,
                    'newRound1Score' => $newScore,
                    'claimedAnswer' => $rawAnswer,
                    'claimedAt' => $claimedAt->toIso8601String(),
                    'session' => $lockedSession,
                ],
                'payload' => [
                    'success' => true,
                    'is_correct' => true,
                    'points_awarded' => (int) $qr->points,
                    'new_score' => $newScore,
                    'code_label' => $qr->code_label,
                    'message' => "Benar! Soal {$qr->code_label} berhasil diklaim (+{$qr->points} poin).",
                ],
            ];
        });

        if (isset($result['broadcast'])) {
            $b = $result['broadcast'];
            broadcast(new QrQuestionClaimed(
                $b['questionId'],
                $b['codeLabel'],
                $b['reguId'],
                $b['reguName'],
                $b['points'],
                $b['newRound1Score'],
                $b['claimedAnswer'],
                $b['claimedAt']
            ));

            broadcast(new ScoreUpdated(
                $b['session']->id,
                $regu->id,
                $regu->nama_regu,
                1,
                (int) $b['session']->score_round_1,
                (int) $b['session']->score_round_2,
                (int) $b['session']->score_round_3,
                (int) $b['session']->score_round_1 + (int) $b['session']->score_round_2 + (int) $b['session']->score_round_3
            ));
        }

        return response()->json($result['payload'], $result['code']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BABAK 2: CIPHER WORD-SEARCH (1 KATA = 1 REGU TERCEPAT)
    // ──────────────────────────────────────────────────────────────────────────

    public function round2(CyberQuizService $quizService)
    {
        $regu = $this->requireEligibleRegu();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(2) !== CyberQuizService::STATUS_ACTIVE) {
            return redirect()->route('peserta.cerdas-cermat.index')
                ->with('error', 'Babak 2 (Cipher Word-Search) belum dibuka atau sudah ditutup oleh Dewan Juri.');
        }

        $session = $quizService->ensureSessionForRegu($regu);
        if (!$session->start_time_round_2) {
            $session->update([
                'start_time_round_2' => now(),
                'status' => 'round_2_ongoing',
            ]);
        }

        $grids = QuizCipherGrid::with(['words.claimedByRegu'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(function (QuizCipherGrid $g) {
                return [
                    'id' => $g->id,
                    'title' => $g->title,
                    'cipher_type' => $g->cipher_type,
                    'grid_size' => $g->grid_size,
                    'grid_matrix' => $g->grid_matrix,
                    'words' => $g->words->values()->map(function (QuizCipherWord $w, int $idx) {
                        return [
                            'id' => $w->id,
                            'slot_number' => $idx + 1,
                            'length' => strlen($w->word),
                            // Only reveal the word once claimed so players must decode the cipher grid!
                            'word' => $w->is_claimed ? $w->word : null,
                            'points' => $w->points,
                            'is_claimed' => $w->is_claimed,
                            'claimed_by_regu_id' => $w->claimed_by_regu_id,
                            'claimed_by' => $w->claimedByRegu?->nama_regu,
                            'claimed_at' => $w->claimed_at?->toIso8601String(),
                        ];
                    }),
                ];
            })->values();

        return view('peserta.cerdas-cermat.round-2', [
            'regu' => $regu,
            'session' => $session,
            'grids' => $grids,
        ]);
    }

    public function claimCipherWord(Request $request, CyberQuizService $quizService): JsonResponse
    {
        $regu = $this->requireEligibleReguJson();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(2) !== CyberQuizService::STATUS_ACTIVE) {
            return response()->json([
                'success' => false,
                'message' => 'Babak 2 sudah tidak aktif.',
            ], 403);
        }

        $validated = $request->validate([
            'cipher_grid_id' => 'nullable|integer|exists:quiz_cipher_grids,id',
            'word' => 'required|string|max:64',
        ]);

        $normalizedWord = strtoupper(preg_replace('/[^A-Za-z]/', '', $validated['word']));
        if ($normalizedWord === '') {
            return response()->json(['success' => false, 'message' => 'Masukkan kata huruf A-Z yang valid.'], 422);
        }

        $session = $quizService->ensureSessionForRegu($regu);
        $gridId = $validated['cipher_grid_id'] ?? null;

        $result = DB::transaction(function () use ($normalizedWord, $gridId, $session, $regu) {
            $query = QuizCipherWord::with('claimedByRegu')
                ->where('word', $normalizedWord)
                ->whereHas('grid', fn($q) => $q->where('is_active', true));

            if ($gridId) {
                $query->where('cipher_grid_id', $gridId);
            }

            // Lock candidate row(s); prefer unclaimed first if same word exists across multiple categories
            $wordRow = (clone $query)->where('is_claimed', false)->lockForUpdate()->first()
                ?? $query->lockForUpdate()->first();

            if (!$wordRow) {
                return [
                    'code' => 422,
                    'payload' => [
                        'success' => false,
                        'message' => "Kata '{$normalizedWord}' tidak ditemukan pada daftar target kategori ini.",
                    ],
                ];
            }

            if ($wordRow->is_claimed) {
                $claimerName = $wordRow->claimedByRegu?->nama_regu ?? 'Regu Lain';
                return [
                    'code' => 409,
                    'payload' => [
                        'success' => false,
                        'already_claimed' => true,
                        'word_id' => $wordRow->id,
                        'grid_id' => $wordRow->cipher_grid_id,
                        'word' => $wordRow->word,
                        'claimed_by' => $claimerName,
                        'message' => "Terlambat! Kata '{$wordRow->word}' sudah diklaim oleh Regu {$claimerName}!",
                    ],
                ];
            }

            $lockedSession = CerdasCermatSession::where('id', $session->id)->lockForUpdate()->firstOrFail();
            $claimedAt = now();

            $wordRow->update([
                'is_claimed' => true,
                'claimed_by_regu_id' => $regu->id,
                'claimed_by_session_id' => $lockedSession->id,
                'claimed_at' => $claimedAt,
            ]);

            $newScore = (int) $lockedSession->score_round_2 + (int) $wordRow->points;
            $lockedSession->update([
                'score_round_2' => $newScore,
                'start_time_round_2' => $lockedSession->start_time_round_2 ?? $claimedAt,
                'round_2_last_activity_at' => $claimedAt,
                'end_time_round_2' => $claimedAt,
                'is_graded_round_2' => true,
                'status' => 'round_2_ongoing',
            ]);

            return [
                'code' => 200,
                'broadcast' => [
                    'gridId' => $wordRow->cipher_grid_id,
                    'wordId' => $wordRow->id,
                    'word' => $wordRow->word,
                    'points' => (int) $wordRow->points,
                    'reguId' => $regu->id,
                    'reguName' => $regu->nama_regu,
                    'newRound2Score' => $newScore,
                    'claimedAt' => $claimedAt->toIso8601String(),
                    'session' => $lockedSession,
                ],
                'payload' => [
                    'success' => true,
                    'grid_id' => $wordRow->cipher_grid_id,
                    'word_id' => $wordRow->id,
                    'word' => $wordRow->word,
                    'points_awarded' => (int) $wordRow->points,
                    'new_score' => $newScore,
                    'message' => "Mantap! Kata '{$wordRow->word}' berhasil diklaim (+{$wordRow->points} poin)!",
                ],
            ];
        });

        if (isset($result['broadcast'])) {
            $b = $result['broadcast'];
            broadcast(new CipherWordClaimed(
                $b['gridId'],
                $b['wordId'],
                $b['word'],
                $b['points'],
                $b['reguId'],
                $b['reguName'],
                $b['newRound2Score'],
                $b['claimedAt']
            ));

            broadcast(new ScoreUpdated(
                $b['session']->id,
                $regu->id,
                $regu->nama_regu,
                2,
                (int) $b['session']->score_round_1,
                (int) $b['session']->score_round_2,
                (int) $b['session']->score_round_3,
                (int) $b['session']->score_round_1 + (int) $b['session']->score_round_2 + (int) $b['session']->score_round_3
            ));
        }

        return response()->json($result['payload'], $result['code']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // BABAK 3: FINAL "COIN ROYALE" (9 RONDE TARUHAN TAKTIS - TOP 5 REGU)
    // ──────────────────────────────────────────────────────────────────────────

    public function round3(CyberQuizService $quizService)
    {
        $regu = $this->requireEligibleRegu();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(3) !== CyberQuizService::STATUS_ACTIVE) {
            return redirect()->route('peserta.cerdas-cermat.index')
                ->with('error', 'Babak 3 (Final Coin Royale) belum dibuka atau sudah selesai.');
        }

        $session = $quizService->ensureSessionForRegu($regu);
        $coinRoyale = $quizService->getCoinRoyaleState(false);

        return view('peserta.cerdas-cermat.round-3', [
            'regu' => $regu,
            'session' => $session,
            'isFinalist' => (bool) $session->is_verified_round_3,
            'coinRoyale' => $coinRoyale,
        ]);
    }

    public function placeCoinRoyaleBet(Request $request, CyberQuizService $quizService): JsonResponse
    {
        $regu = $this->requireEligibleReguJson();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        if ($quizService->getRoundStatus(3) !== CyberQuizService::STATUS_ACTIVE) {
            return response()->json(['success' => false, 'message' => 'Babak 3 belum aktif atau sudah ditutup.'], 403);
        }

        $validated = $request->validate([
            'round_number' => 'required|integer|min:1|max:9',
            'action' => ['required', Rule::in(['BET', 'PASS'])],
            'bet_amount' => 'required|integer|min:0',
        ]);

        $session = $quizService->ensureSessionForRegu($regu);
        if (!$session->is_verified_round_3) {
            return response()->json([
                'success' => false,
                'message' => 'Regu Anda tidak termasuk dalam Top 5 Finalis Coin Royale.',
            ], 403);
        }

        $roundNumber = (int) $validated['round_number'];
        $action = $validated['action'];
        $betAmount = (int) $validated['bet_amount'];

        $txResult = DB::transaction(function () use ($session, $regu, $roundNumber, $action, $betAmount) {
            $lockedSession = CerdasCermatSession::where('id', $session->id)->lockForUpdate()->firstOrFail();
            $round = QuizCoinRoyaleRound::where('round_number', $roundNumber)->lockForUpdate()->firstOrFail();

            if ($round->phase !== QuizCoinRoyaleRound::PHASE_BETTING_OPEN) {
                return [
                    'code' => 422,
                    'payload' => ['success' => false, 'message' => 'Fase taruhan untuk ronde ini tidak sedang dibuka.'],
                ];
            }

            if (!$round->is_special && $action === 'PASS') {
                return [
                    'code' => 422,
                    'payload' => ['success' => false, 'message' => 'Ronde Normal WAJIB memasang taruhan (tidak boleh Pass).'],
                ];
            }

            $saldo = (int) $lockedSession->coin_balance;

            if ($action === 'PASS') {
                $betAmount = 0;
            } elseif ($round->is_special) {
                // Max 50% of current balance so 2x penalty cannot make balance negative
                $maxSpecialBet = (int) floor($saldo * 0.5);
                if ($betAmount > $maxSpecialBet) {
                    return [
                        'code' => 422,
                        'payload' => [
                            'success' => false,
                            'message' => "Ronde Spesial membatasi taruhan maksimal 50% dari saldo saat ini ({$maxSpecialBet} koin).",
                        ],
                    ];
                }
                if ($saldo >= 2 && $betAmount < 1) {
                    return [
                        'code' => 422,
                        'payload' => ['success' => false, 'message' => 'Minimal taruhan ikut Ronde Spesial adalah 1 koin.'],
                    ];
                }
            } else {
                if ($betAmount > $saldo) {
                    return [
                        'code' => 422,
                        'payload' => ['success' => false, 'message' => "Taruhan melebihi saldo koin Anda ({$saldo} koin)."],
                    ];
                }
            }

            QuizCoinRoyaleBet::updateOrCreate(
                [
                    'coin_royale_round_id' => $round->id,
                    'session_id' => $lockedSession->id,
                ],
                [
                    'regu_id' => $regu->id,
                    'action' => $action,
                    'bet_amount' => $betAmount,
                    'balance_before' => $saldo,
                    'result' => $action === 'PASS' ? QuizCoinRoyaleBet::RESULT_PASS : QuizCoinRoyaleBet::RESULT_PENDING,
                ]
            );

            return [
                'code' => 200,
                'payload' => [
                    'success' => true,
                    'message' => $action === 'PASS'
                        ? "Regu memilih PASS pada Ronde Spesial {$roundNumber}. Saldo aman!"
                        : "Taruhan {$betAmount} koin untuk Ronde {$roundNumber} terkunci!",
                ],
            ];
        });

        if ($txResult['code'] === 200) {
            $state = $quizService->getCoinRoyaleState(false);
            broadcast(new CoinRoyaleUpdated($state));
            $txResult['payload']['coin_royale'] = $state;
        }

        return response()->json($txResult['payload'], $txResult['code']);
    }

    public function checkStatus(CyberQuizService $quizService): JsonResponse
    {
        $regu = Auth::user()?->reguProfile;
        $session = $regu ? CerdasCermatSession::where('regu_id', $regu->id)->first() : null;
        $statuses = $quizService->getRoundStatuses();

        return response()->json([
            'round_statuses' => $statuses,
            'round_1_started' => $statuses['round_1'] === CyberQuizService::STATUS_ACTIVE,
            'round_2_started' => $statuses['round_2'] === CyberQuizService::STATUS_ACTIVE,
            'round_3_started' => $statuses['round_3'] === CyberQuizService::STATUS_ACTIVE,
            'is_locked' => (bool) ($session?->is_locked ?? false),
            'server_time' => now()->toIso8601String(),
        ]);
    }

    public function register(Request $request, CyberQuizService $quizService)
    {
        $regu = $request->wantsJson() ? $this->requireEligibleReguJson() : $this->requireEligibleRegu();
        if (!($regu instanceof ReguProfile)) {
            return $regu;
        }

        $anggotaCount = $regu->anggotaRegu()->count();
        $requireThree = $anggotaCount >= 3;

        $validated = $request->validate([
            'name_1' => ['required', 'string', 'max:255'],
            'name_2' => [$requireThree ? 'required' : 'nullable', 'string', 'max:255'],
            'name_3' => [$requireThree ? 'required' : 'nullable', 'string', 'max:255'],
        ], [
            'name_1.required' => 'Nama Peserta 1 wajib dipilih.',
            'name_2.required' => 'Nama Peserta 2 wajib dipilih.',
            'name_3.required' => 'Nama Peserta 3 wajib dipilih.',
        ]);

        $names = array_values(array_filter([
            trim((string) ($validated['name_1'] ?? '')),
            trim((string) ($validated['name_2'] ?? '')),
            trim((string) ($validated['name_3'] ?? '')),
        ], fn($v) => $v !== ''));

        if (count($names) !== count(array_unique(array_map('mb_strtolower', $names)))) {
            $msg = 'Peserta 1, 2, dan 3 tidak boleh orang yang sama.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withErrors(['name_2' => $msg])->withInput();
        }

        $session = $quizService->ensureSessionForRegu($regu);
        $session->update([
            'name_1' => trim((string) ($validated['name_1'] ?? '')) ?: null,
            'name_2' => trim((string) ($validated['name_2'] ?? '')) ?: null,
            'name_3' => trim((string) ($validated['name_3'] ?? '')) ?: null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Perwakilan peserta Cerdas Cermat berhasil didaftarkan.',
                'session' => [
                    'name_1' => $session->name_1,
                    'name_2' => $session->name_2,
                    'name_3' => $session->name_3,
                ],
            ]);
        }

        return redirect()->route('peserta.cerdas-cermat.index')
            ->with('success', 'Perwakilan peserta Cerdas Cermat berhasil didaftarkan.');
    }

    public function submitRound1(Request $request)
    {
        return redirect()->route('peserta.cerdas-cermat.index');
    }

    public function submitRound2(Request $request)
    {
        return redirect()->route('peserta.cerdas-cermat.index');
    }

    public function submitRound3(Request $request)
    {
        return redirect()->route('peserta.cerdas-cermat.index');
    }

    public function autosave(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function verifyUnlockCode(Request $request)
    {
        return response()->json(['success' => true]);
    }

    public function reportViolation(Request $request)
    {
        return response()->json(['success' => true]);
    }

    private function requireEligibleRegu()
    {
        $regu = Auth::user()?->reguProfile;
        if (!$regu) {
            return redirect()->route('peserta.dashboard')->with('error', 'Profil regu Anda belum terdaftar.');
        }
        if (!$regu->isEligibleForCerdasCermat()) {
            $min = ReguProfile::getMinAnggotaRequired();
            return redirect()->route('peserta.dashboard')
                ->with('error', "Menu Cerdas Cermat masih terkunci! Daftarkan minimal {$min} anggota regu terlebih dahulu.");
        }
        return $regu;
    }

    private function requireEligibleReguJson()
    {
        $regu = Auth::user()?->reguProfile;
        if (!$regu || !$regu->isEligibleForCerdasCermat()) {
            return response()->json([
                'success' => false,
                'message' => 'Regu belum memenuhi syarat kelengkapan anggota untuk mengikuti Cerdas Cermat.',
            ], 403);
        }
        return $regu;
    }
}
