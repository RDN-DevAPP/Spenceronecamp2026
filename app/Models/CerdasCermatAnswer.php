<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CerdasCermatAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'question_id',
        'answer_text',
        'is_correct',
        'score',
    ];

    public function session()
    {
        return $this->belongsTo(CerdasCermatSession::class, 'session_id');
    }

    public function question()
    {
        return $this->belongsTo(CerdasCermatQuestion::class, 'question_id');
    }
}
