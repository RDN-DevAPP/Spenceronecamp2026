<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizCipherWord extends Model
{
    use HasFactory;

    protected $table = 'quiz_cipher_words';

    protected $fillable = [
        'cipher_grid_id',
        'word',
        'hint',
        'points',
        'is_claimed',
        'claimed_by_regu_id',
        'claimed_by_session_id',
        'claimed_at',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_claimed' => 'boolean',
        'claimed_at' => 'datetime',
    ];

    public function grid(): BelongsTo
    {
        return $this->belongsTo(QuizCipherGrid::class, 'cipher_grid_id');
    }

    public function claimedByRegu(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class, 'claimed_by_regu_id');
    }

    public function claimedBySession(): BelongsTo
    {
        return $this->belongsTo(CerdasCermatSession::class, 'claimed_by_session_id');
    }
}
