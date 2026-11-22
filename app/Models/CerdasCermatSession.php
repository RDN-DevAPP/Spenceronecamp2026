<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CerdasCermatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'regu_id',
        'name_1',
        'name_2',
        'name_3',
        'current_round',
        'score_round_1',
        'score_round_2',
        'score_round_3',
        'coin_initial',
        'coin_balance',
        'status',
        'is_locked',
        'warning_count',
        'penalty_seconds',
        'start_time_round_1',
        'end_time_round_1',
        'round_1_last_activity_at',
        'start_time_round_2',
        'end_time_round_2',
        'round_2_last_activity_at',
        'start_time_round_3',
        'end_time_round_3',
        'is_verified_round_2',
        'is_graded_round_2',
        'is_verified_round_3',
        'is_finalized_round_2',
        'is_finalized_round_3',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'warning_count' => 'integer',
        'coin_initial' => 'integer',
        'coin_balance' => 'integer',
        'is_verified_round_2' => 'boolean',
        'is_graded_round_2' => 'boolean',
        'is_verified_round_3' => 'boolean',
        'is_finalized_round_2' => 'boolean',
        'is_finalized_round_3' => 'boolean',
        'start_time_round_1' => 'datetime',
        'end_time_round_1' => 'datetime',
        'round_1_last_activity_at' => 'datetime',
        'start_time_round_2' => 'datetime',
        'end_time_round_2' => 'datetime',
        'round_2_last_activity_at' => 'datetime',
        'start_time_round_3' => 'datetime',
        'end_time_round_3' => 'datetime',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function reguProfile()
    {
        return $this->belongsTo(ReguProfile::class, 'regu_id');
    }

    public function answers()
    {
        return $this->hasMany(CerdasCermatAnswer::class, 'session_id');
    }

    public function qrAttempts()
    {
        return $this->hasMany(QuizQrAttempt::class, 'session_id');
    }

    public function claimedQrQuestions()
    {
        return $this->hasMany(QuizQrQuestion::class, 'claimed_by_session_id');
    }

    public function claimedCipherWords()
    {
        return $this->hasMany(QuizCipherWord::class, 'claimed_by_session_id');
    }

    public function coinRoyaleBets()
    {
        return $this->hasMany(QuizCoinRoyaleBet::class, 'session_id');
    }

    /**
     * Elapsed seconds in Round 1 for tie-breaking (lower is faster).
     */
    public function getRound1ElapsedSeconds(): int
    {
        if ($this->start_time_round_1 && ($this->round_1_last_activity_at || $this->end_time_round_1)) {
            $end = $this->round_1_last_activity_at ?? $this->end_time_round_1;
            return max(0, $this->start_time_round_1->diffInSeconds($end));
        }
        return 999999;
    }

    /**
     * Elapsed seconds in Round 2 for tie-breaking (lower is faster).
     */
    public function getRound2ElapsedSeconds(): int
    {
        if ($this->start_time_round_2 && ($this->round_2_last_activity_at || $this->end_time_round_2)) {
            $end = $this->round_2_last_activity_at ?? $this->end_time_round_2;
            return max(0, $this->start_time_round_2->diffInSeconds($end));
        }
        return 999999;
    }

    /**
     * Cumulative B1 + B2 time for Top 5 Coin Royale tie-breaking.
     */
    public function getQualifierTimeSeconds(): int
    {
        return $this->getRound1ElapsedSeconds() + $this->getRound2ElapsedSeconds();
    }
}
