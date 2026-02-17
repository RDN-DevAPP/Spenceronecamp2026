<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CerdasCermatSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'regu_id',
        'name_1',
        'name_2',
        'name_3',
        'current_round',
        'score_round_1',
        'score_round_2',
        'score_round_3',
        'status',
        'penalty_seconds',
        'start_time_round_1',
        'end_time_round_1',
        'start_time_round_2',
        'end_time_round_2',
        'start_time_round_3',
        'end_time_round_3',
        'is_verified_round_2',
        'is_graded_round_2',
    ];

    protected $casts = [
        'start_time_round_1' => 'datetime',
        'end_time_round_1' => 'datetime',
        'start_time_round_2' => 'datetime',
        'end_time_round_2' => 'datetime',
        'start_time_round_3' => 'datetime',
        'end_time_round_3' => 'datetime',
    ];

    public function reguProfile()
    {
        return $this->belongsTo(ReguProfile::class, 'regu_id');
    }

    public function answers()
    {
        return $this->hasMany(CerdasCermatAnswer::class, 'session_id');
    }
}
