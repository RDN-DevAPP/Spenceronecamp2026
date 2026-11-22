<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReguVote extends Model
{
    use HasFactory;

    public const TYPE_FREE = 'free';
    public const TYPE_PAID = 'paid';

    protected $table = 'regu_votes';

    protected $fillable = [
        'regu_profile_id',
        'user_id',
        'voter_identifier',
        'ip_address',
        'user_agent',
        'voter_name',
        'voted_at',
        'vote_type',
        'paid_vote_transaction_id',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class, 'regu_profile_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paidVoteTransaction(): BelongsTo
    {
        return $this->belongsTo(PaidVoteTransaction::class, 'paid_vote_transaction_id');
    }

    public function isFree(): bool
    {
        return ($this->vote_type ?? self::TYPE_FREE) === self::TYPE_FREE;
    }

    public function isPaid(): bool
    {
        return ($this->vote_type ?? self::TYPE_FREE) === self::TYPE_PAID;
    }
}
