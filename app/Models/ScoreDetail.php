<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoreDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'score_id',
        'scoring_criteria_id',
        'nilai',
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
        ];
    }

    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }

    public function scoringCriteria(): BelongsTo
    {
        return $this->belongsTo(ScoringCriteria::class);
    }
}
