<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Season extends Model
{
    use HasFactory;

    protected $table = 'seasons';

    protected $fillable = [
        'name',
        'slug',
        'is_active',
        'theme',
        'tagline',
        'start_date',
        'end_date',
        'location',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function reguProfiles(): HasMany
    {
        return $this->hasMany(ReguProfile::class, 'season_id');
    }

    public function mataLombas(): HasMany
    {
        return $this->hasMany(MataLomba::class, 'season_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'season_id');
    }

    public function jadwals(): HasMany
    {
        return $this->hasMany(Jadwal::class, 'season_id');
    }

    public function cerdasCermatSessions(): HasMany
    {
        return $this->hasMany(CerdasCermatSession::class, 'season_id');
    }

    public function sponsorships(): HasMany
    {
        return $this->hasMany(Sponsorship::class, 'season_id');
    }

    public function financialReports(): HasMany
    {
        return $this->hasMany(FinancialReport::class, 'season_id');
    }
}
