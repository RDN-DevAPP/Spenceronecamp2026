<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'regu_id',
        'violation_type',
        'round',
        'description',
    ];

    public function session()
    {
        return $this->belongsTo(CerdasCermatSession::class, 'session_id');
    }

    public function reguProfile()
    {
        return $this->belongsTo(ReguProfile::class, 'regu_id');
    }

    public function getViolationLabelAttribute(): string
    {
        return match($this->violation_type) {
            'tab_switch'   => 'Keluar Tab',
            'copy'         => 'Copy Teks',
            'paste'        => 'Paste Teks',
            'cut'          => 'Cut Teks',
            'context_menu' => 'Klik Kanan',
            'screenshot'   => 'Screenshot',
            'devtools'     => 'Developer Tools',
            default        => 'Lainnya',
        };
    }
}
