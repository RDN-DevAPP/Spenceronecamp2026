<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScoreAuditLog extends Model
{
    use HasFactory;

    protected $table = 'score_audit_logs';

    protected $fillable = [
        'score_id',
        'user_id',
        'regu_profile_id',
        'mata_lomba_id',
        'action',
        'old_score',
        'new_score',
        'old_status',
        'new_status',
        'reason',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_score' => 'decimal:2',
            'new_score' => 'decimal:2',
        ];
    }

    public function score(): BelongsTo
    {
        return $this->belongsTo(Score::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }
}
