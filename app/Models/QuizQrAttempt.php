<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizQrAttempt extends Model
{
    use HasFactory;

    protected $table = 'quiz_qr_attempts';

    protected $fillable = [
        'qr_question_id',
        'session_id',
        'regu_id',
        'submitted_answer',
        'is_correct',
        'points_awarded',
        'status',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_awarded' => 'integer',
    ];

    public function qrQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQrQuestion::class, 'qr_question_id');
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
