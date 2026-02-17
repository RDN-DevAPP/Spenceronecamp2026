<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReguProfile extends Model
{
    use HasFactory;

    protected $table = 'regu_profiles';

    protected $fillable = [
        'user_id',
        'nama_regu',
        'jenis',
        'nomor_regu',
        'poster_digital_path',
        'foto_tenda_path',
        'foto_masakan_path',
        'foto_karya_path',
        'poster_creator_id',
        'upcycle_creator_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anggotaRegu(): HasMany
    {
        return $this->hasMany(AnggotaRegu::class, 'regu_profile_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'regu_profile_id');
    }

    public function posterCreator(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class, 'poster_creator_id');
    }

    public function upcycleCreator(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class, 'upcycle_creator_id');
    }

    /**
     * Total nilai dari semua mata lomba (sum atau rata-rata tergantung aturan).
     */
    public function totalNilai(): float
    {
        return (float) $this->scores()->sum('nilai');
    }

    public function isPutra(): bool
    {
        return $this->jenis === 'putra';
    }

    public function isPutri(): bool
    {
        return $this->jenis === 'putri';
    }

    public function getCompetitionPhoto(string $lombaName): ?string
    {
        return match ($lombaName) {
            'Tapak Kemah' => $this->foto_tenda_path,
            'Masak Konvensional' => $this->foto_masakan_path,
            'Upcycle Art' => $this->foto_karya_path,
            'Desain Poster Digital' => $this->poster_digital_path,
            default => null,
        };
    }
}
