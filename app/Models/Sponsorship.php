<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sponsorship extends Model
{
    use HasFactory;

    public const TIER_PLATINUM = 'platinum';
    public const TIER_GOLD = 'gold';
    public const TIER_SILVER = 'silver';

    protected $fillable = [
        'name',
        'tier',
        'logo',
        'website_url',
        'pic_name',
        'email',
        'phone',
        'receipt',
        'is_approved',
    ];
}
