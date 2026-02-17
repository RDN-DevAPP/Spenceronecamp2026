<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CerdasCermatQuestion extends Model
{
    use HasFactory;

    protected $table = 'cerdas_cermat_questions';

    protected $fillable = [
        'mata_lomba_id',
        'type',
        'question',
        'options',
        'correct_answer',
        'score',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }
}
