<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class QuizQrQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_qr_questions';

    protected $fillable = [
        'mata_lomba_id',
        'code_label',
        'qr_token',
        'question',
        'image_path',
        'accepted_answers',
        'points',
        'status',
        'claimed_by_regu_id',
        'claimed_by_session_id',
        'claimed_answer',
        'claimed_at',
    ];

    protected $casts = [
        'accepted_answers' => 'array',
        'points' => 'integer',
        'claimed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->qr_token)) {
                $model->qr_token = (string) Str::uuid();
            }
            if (empty($model->code_label)) {
                $nextNum = (self::max('id') ?? 0) + 1;
                $model->code_label = 'QR-' . str_pad((string) $nextNum, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }

    public function claimedByRegu(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class, 'claimed_by_regu_id');
    }

    public function claimedBySession(): BelongsTo
    {
        return $this->belongsTo(CerdasCermatSession::class, 'claimed_by_session_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizQrAttempt::class, 'qr_question_id');
    }

    /**
     * Case-insensitive and whitespace-normalized answer verification.
     */
    public function isAnswerCorrect(string $submittedAnswer): bool
    {
        $normalizedInput = self::normalizeAnswer($submittedAnswer);
        if ($normalizedInput === '') {
            return false;
        }

        foreach ($this->accepted_answers ?? [] as $valid) {
            if (self::normalizeAnswer((string) $valid) === $normalizedInput) {
                return true;
            }
        }

        return false;
    }

    public static function normalizeAnswer(string $answer): string
    {
        $trimmed = trim(preg_replace('/\s+/', ' ', $answer) ?? '');
        return mb_strtolower($trimmed);
    }
}
