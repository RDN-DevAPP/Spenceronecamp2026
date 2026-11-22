<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizCoinRoyaleBet extends Model
{
    use HasFactory;

    public const ACTION_BET = 'BET';
    public const ACTION_PASS = 'PASS';

    public const RESULT_PENDING = 'PENDING';
    public const RESULT_CORRECT = 'CORRECT';
    public const RESULT_WRONG = 'WRONG';
    public const RESULT_PASS = 'PASS';

    protected $table = 'quiz_coin_royale_bets';

    protected $fillable = [
        'coin_royale_round_id',
        'session_id',
        'regu_id',
        'action',
        'bet_amount',
        'balance_before',
        'result',
        'balance_after',
        'coin_delta',
    ];

    protected $casts = [
        'bet_amount' => 'integer',
        'balance_before' => 'integer',
        'balance_after' => 'integer',
        'coin_delta' => 'integer',
    ];

    public function round(): BelongsTo
    {
        return $this->belongsTo(QuizCoinRoyaleRound::class, 'coin_royale_round_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CerdasCermatSession::class, 'session_id');
    }

    public function regu(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class, 'regu_id');
    }
}
