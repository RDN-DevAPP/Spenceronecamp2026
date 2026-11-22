<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizCoinRoyaleRound extends Model
{
    use HasFactory;

    public const PHASE_PENDING = 'PENDING';
    public const PHASE_BETTING_OPEN = 'BETTING_OPEN';
    public const PHASE_BETTING_LOCKED = 'BETTING_LOCKED';
    public const PHASE_QUESTION_OPEN = 'QUESTION_OPEN';
    public const PHASE_GRADED = 'GRADED';

    protected $table = 'quiz_coin_royale_rounds';

    protected $fillable = [
        'round_number',
        'is_special',
        'question_text',
        'question_image_path',
        'correct_answer',
        'phase',
        'opened_at',
        'locked_at',
        'graded_at',
    ];

    protected $appends = [
        'question_image_url',
    ];

    public function getQuestionImageUrlAttribute(): ?string
    {
        return $this->question_image_path ? asset('storage/' . $this->question_image_path) : null;
    }

    protected $casts = [
        'round_number' => 'integer',
        'is_special' => 'boolean',
        'opened_at' => 'datetime',
        'locked_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function bets(): HasMany
    {
        return $this->hasMany(QuizCoinRoyaleBet::class, 'coin_royale_round_id');
    }

    /**
     * Ensure all 9 rounds (1,2,4,5,7,8 Normal; 3,6,9 Special) exist in DB.
     */
    public static function ensureNineRoundsExist(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            self::firstOrCreate(
                ['round_number' => $i],
                [
                    'is_special' => in_array($i, [3, 6, 9], true),
                    'phase' => self::PHASE_PENDING,
                ]
            );
        }
    }
}
