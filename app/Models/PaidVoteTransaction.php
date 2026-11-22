<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaidVoteTransaction extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_REJECTED = 'rejected';

    protected $table = 'paid_vote_transactions';

    protected $fillable = [
        'transaction_code',
        'regu_profile_id',
        'user_id',
        'voter_name',
        'voter_identifier',
        'ip_address',
        'votes_count',
        'unit_price',
        'total_amount',
        'payment_method',
        'qris_payload',
        'gateway_fee',
        'gateway_total_payment',
        'expired_at',
        'gateway_response',
        'proof_path',
        'status',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'gateway_total_payment' => 'decimal:2',
        'votes_count' => 'integer',
        'expired_at' => 'datetime',
        'verified_at' => 'datetime',
        'gateway_response' => 'array',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class, 'regu_profile_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ReguVote::class, 'paid_vote_transaction_id');
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
