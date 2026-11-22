<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizCipherGrid extends Model
{
    use HasFactory;

    protected $table = 'quiz_cipher_grids';

    protected $fillable = [
        'title',
        'cipher_type',
        'grid_size',
        'grid_matrix',
        'word_placements',
        'default_points',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'grid_size' => 'integer',
        'grid_matrix' => 'array',
        'word_placements' => 'array',
        'default_points' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function words(): HasMany
    {
        return $this->hasMany(QuizCipherWord::class, 'cipher_grid_id');
    }
}
