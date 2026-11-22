<?php

namespace App\Services;

use App\Models\CerdasCermatSession;
use App\Models\CerdasCermatSetting;
use App\Models\QuizCipherGrid;
use App\Models\QuizCoinRoyaleBet;
use App\Models\QuizCoinRoyaleRound;
use App\Models\QuizQrQuestion;
use App\Models\ReguProfile;

class CyberQuizService
{
    public const STATUS_LOCKED = 'LOCKED';
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_FINISHED = 'FINISHED';

    /**
     * Get centralized statuses for Babak 1, 2, 3 ('LOCKED' | 'ACTIVE' | 'FINISHED').
     */
    public function getRoundStatuses(): array
    {
        return [
            'round_1' => $this->normalizeRoundStatus(1),
            'round_2' => $this->normalizeRoundStatus(2),
            'round_3' => $this->normalizeRoundStatus(3),
        ];
    }

    public function getRoundStatus(int $round): string
    {
        return $this->normalizeRoundStatus($round);
    }

    private function normalizeRoundStatus(int $round): string
    {
        $val = CerdasCermatSetting::getValue("round_{$round}_status", null);
        if (in_array($val, [self::STATUS_LOCKED, self::STATUS_ACTIVE, self::STATUS_FINISHED], true)) {
            return $val;
        }

        // Backwards compatibility with legacy round_{n}_started flag
        $started = (bool) CerdasCermatSetting::getValue("round_{$round}_started", false);
        return $started ? self::STATUS_ACTIVE : self::STATUS_LOCKED;
    }

    public function setRoundStatus(int $round, string $status): array
    {
        $status = strtoupper($status);
        if (!in_array($status, [self::STATUS_LOCKED, self::STATUS_ACTIVE, self::STATUS_FINISHED], true)) {
            $status = self::STATUS_LOCKED;
        }

        CerdasCermatSetting::updateOrCreate(
            ['key' => "round_{$round}_status"],
            ['value' => $status]
        );

        CerdasCermatSetting::updateOrCreate(
            ['key' => "round_{$round}_started"],
            ['value' => $status === self::STATUS_ACTIVE ? '1' : '0']
        );

        if ($status === self::STATUS_ACTIVE) {
            CerdasCermatSetting::updateOrCreate(
                ['key' => "round_{$round}_start_at"],
                ['value' => now()->toIso8601String()]
            );
        }

        return $this->getRoundStatuses();
    }

    /**
     * Ensure a CerdasCermatSession exists for an eligible ReguProfile.
     */
    public function ensureSessionForRegu(ReguProfile $regu): CerdasCermatSession
    {
        $session = CerdasCermatSession::where('regu_id', $regu->id)->first();
        if ($session) {
            return $session;
        }

        $members = $regu->anggotaRegu()->orderBy('urutan')->take(3)->pluck('nama')->values();

        return CerdasCermatSession::create([
            'season_id' => $regu->season_id,
            'regu_id' => $regu->id,
            'name_1' => $members[0] ?? $regu->nama_regu,
            'name_2' => $members[1] ?? null,
            'name_3' => $members[2] ?? null,
            'current_round' => 1,
            'score_round_1' => 0,
            'score_round_2' => 0,
            'score_round_3' => 0,
            'coin_initial' => 0,
            'coin_balance' => 0,
            'status' => 'registered',
        ]);
    }

    /**
     * Compute Top 5 Qualifiers from Babak 1 + Babak 2 (tie-breaker: fastest cumulative time).
     */
    public function getRankedQualifiers(): \Illuminate\Support\Collection
    {
        return CerdasCermatSession::with('reguProfile')
            ->whereHas('reguProfile')
            ->get()
            ->map(function (CerdasCermatSession $s) {
                $b1 = (int) ($s->score_round_1 ?? 0);
                $b2 = (int) ($s->score_round_2 ?? 0);
                $s->b1_b2_total = $b1 + $b2;
                $s->qualifier_time_seconds = $s->getQualifierTimeSeconds();
                return $s;
            })
            ->sort(function ($a, $b) {
                if ($a->b1_b2_total !== $b->b1_b2_total) {
                    return $b->b1_b2_total <=> $a->b1_b2_total;
                }
                if ($a->qualifier_time_seconds !== $b->qualifier_time_seconds) {
                    return $a->qualifier_time_seconds <=> $b->qualifier_time_seconds;
                }
                return $a->id <=> $b->id;
            })
            ->values();
    }

    /**
     * Automatically filter Top 5 Regu and initialize Coin Royale balances (B1 + B2 = Initial Coin).
     */
    public function initializeTop5CoinRoyale(bool $forceResetCoins = false): \Illuminate\Support\Collection
    {
        QuizCoinRoyaleRound::ensureNineRoundsExist();

        $ranked = $this->getRankedQualifiers();
        $top5 = $ranked->take(5);
        $top5Ids = $top5->pluck('id')->all();

        CerdasCermatSession::whereNotIn('id', $top5Ids ?: [0])->update([
            'is_verified_round_3' => false,
        ]);

        foreach ($top5 as $session) {
            $initialCoins = (int) ($session->score_round_1 ?? 0) + (int) ($session->score_round_2 ?? 0);
            $hasBets = QuizCoinRoyaleBet::where('session_id', $session->id)->exists();

            $updates = [
                'is_verified_round_3' => true,
            ];

            if ($forceResetCoins || !$hasBets) {
                $updates['coin_initial'] = $initialCoins;
                $updates['coin_balance'] = $initialCoins;
                $updates['score_round_3'] = 0;
            }

            CerdasCermatSession::where('id', $session->id)->update($updates);
        }

        return CerdasCermatSession::with('reguProfile')
            ->where('is_verified_round_3', true)
            ->orderByDesc('coin_balance')
            ->orderByDesc('coin_initial')
            ->get()
            ->values();
    }

    /**
     * Build sanitized Coin Royale state payload for Juri, Peserta, and /live-display.
     */
    public function getCoinRoyaleState(bool $includeAnswers = false): array
    {
        QuizCoinRoyaleRound::ensureNineRoundsExist();

        $rounds = QuizCoinRoyaleRound::with(['bets.regu'])
            ->orderBy('round_number')
            ->get();

        // Pick active round (any round currently open/locked/question_open, else latest graded, else round 1)
        $activeRound = $rounds->first(fn($r) => in_array($r->phase, [
            QuizCoinRoyaleRound::PHASE_BETTING_OPEN,
            QuizCoinRoyaleRound::PHASE_BETTING_LOCKED,
            QuizCoinRoyaleRound::PHASE_QUESTION_OPEN,
        ], true));

        if (!$activeRound) {
            $activeRound = $rounds->where('phase', QuizCoinRoyaleRound::PHASE_GRADED)->sortByDesc('round_number')->first()
                ?? $rounds->first();
        }

        $finalists = CerdasCermatSession::with('reguProfile')
            ->where('is_verified_round_3', true)
            ->get()
            ->sort(function ($a, $b) {
                if ((int) $a->coin_balance !== (int) $b->coin_balance) {
                    return (int) $b->coin_balance <=> (int) $a->coin_balance;
                }
                return ((int) $b->score_round_1 + (int) $b->score_round_2) <=> ((int) $a->score_round_1 + (int) $a->score_round_2);
            })
            ->values()
            ->map(function (CerdasCermatSession $s, int $idx) use ($activeRound) {
                $lastBet = $activeRound
                    ? $activeRound->bets->firstWhere('session_id', $s->id)
                    : null;

                return [
                    'rank' => $idx + 1,
                    'session_id' => $s->id,
                    'regu_id' => $s->regu_id,
                    'nama_regu' => $s->reguProfile?->nama_regu ?? 'Regu #' . $s->regu_id,
                    'nomor_regu' => $s->reguProfile?->nomor_regu ?? '-',
                    'jenis' => $s->reguProfile?->jenis ?? 'putra',
                    'score_round_1' => (int) ($s->score_round_1 ?? 0),
                    'score_round_2' => (int) ($s->score_round_2 ?? 0),
                    'coin_initial' => (int) ($s->coin_initial ?? 0),
                    'coin_balance' => (int) ($s->coin_balance ?? 0),
                    'net_delta' => (int) ($s->coin_balance ?? 0) - (int) ($s->coin_initial ?? 0),
                    'current_bet' => $lastBet ? [
                        'action' => $lastBet->action,
                        'bet_amount' => (int) $lastBet->bet_amount,
                        'result' => $lastBet->result,
                        'coin_delta' => (int) $lastBet->coin_delta,
                    ] : null,
                ];
            });

        $roundsData = $rounds->map(function (QuizCoinRoyaleRound $r) use ($includeAnswers) {
            $revealQuestion = $includeAnswers || in_array($r->phase, [
                QuizCoinRoyaleRound::PHASE_QUESTION_OPEN,
                QuizCoinRoyaleRound::PHASE_GRADED,
            ], true);

            return [
                'id' => $r->id,
                'round_number' => $r->round_number,
                'is_special' => (bool) $r->is_special,
                'phase' => $r->phase,
                'question_text' => $revealQuestion ? $r->question_text : null,
                'question_image_url' => $revealQuestion && $r->question_image_path ? asset('storage/' . $r->question_image_path) : null,
                'correct_answer' => $includeAnswers ? $r->correct_answer : null,
                'bets' => $r->bets->map(fn(QuizCoinRoyaleBet $b) => [
                    'id' => $b->id,
                    'session_id' => $b->session_id,
                    'regu_id' => $b->regu_id,
                    'nama_regu' => $b->regu?->nama_regu ?? 'Regu',
                    'action' => $b->action,
                    'bet_amount' => (int) $b->bet_amount,
                    'balance_before' => (int) $b->balance_before,
                    'result' => $b->result,
                    'balance_after' => $b->balance_after !== null ? (int) $b->balance_after : null,
                    'coin_delta' => (int) $b->coin_delta,
                ])->values(),
            ];
        });

        return [
            'round_3_status' => $this->getRoundStatus(3),
            'active_round_number' => $activeRound?->round_number ?? 1,
            'active_round' => $roundsData->firstWhere('round_number', $activeRound?->round_number ?? 1),
            'rounds' => $roundsData,
            'finalists' => $finalists,
        ];
    }

    /**
     * Get summary data for Babak 1 & Babak 2 monitoring.
     */
    public function getArenaOverview(): array
    {
        return [
            'round_statuses' => $this->getRoundStatuses(),
            'qr_stats' => [
                'total' => QuizQrQuestion::count(),
                'available' => QuizQrQuestion::where('status', 'available')->count(),
                'claimed' => QuizQrQuestion::where('status', 'claimed')->count(),
            ],
            'cipher_stats' => [
                'grids' => QuizCipherGrid::where('is_active', true)->count(),
                'total_words' => \App\Models\QuizCipherWord::count(),
                'claimed_words' => \App\Models\QuizCipherWord::where('is_claimed', true)->count(),
            ],
        ];
    }
}
