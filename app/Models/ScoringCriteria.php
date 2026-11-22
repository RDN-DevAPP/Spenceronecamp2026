<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScoringCriteria extends Model
{
    use HasFactory;

    protected $table = 'scoring_criteria';

    protected $fillable = [
        'mata_lomba_id',
        'nama_kriteria',
        'kelompok',
        'rubrik',
        'nilai_min',
        'nilai_max',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_min' => 'decimal:2',
            'nilai_max' => 'decimal:2',
            'rubrik' => 'array',
        ];
    }

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }

    public function scoreDetails(): HasMany
    {
        return $this->hasMany(ScoreDetail::class);
    }
}
