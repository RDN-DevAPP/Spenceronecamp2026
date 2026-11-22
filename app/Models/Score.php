<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Score extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_VERIFIED = 'verified';

    protected $fillable = [
        'season_id',
        'regu_profile_id',
        'mata_lomba_id',
        'juri_id',
        'nilai',
        'status',
        'unlock_requested',
        'unlock_reason',
        'submitted_at',
        'verified_at',
        'verified_by',
        'catatan',
        'delete_requested'
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
            'delete_requested' => 'boolean',
            'unlock_requested' => 'boolean',
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isLocked(): bool
    {
        return in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_VERIFIED]);
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }

    public function juri(): BelongsTo
    {
        return $this->belongsTo(User::class, 'juri_id');
    }

    public function scoreDetails(): HasMany
    {
        return $this->hasMany(ScoreDetail::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(ScoreAuditLog::class);
    }
}
