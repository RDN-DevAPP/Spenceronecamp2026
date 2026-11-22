<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KaryaLomba extends Model
{
    use HasFactory;

    protected $table = 'karya_lombas';

    protected $fillable = [
        'season_id',
        'regu_profile_id',
        'mata_lomba_slug',
        'judul_karya',
        'kategori_bahan',
        'rincian_bahan_alat',
        'fungsi_guna',
        'deskripsi_filosofi',
        'resep_gizi',
        'foto_utama_path',
        'foto_detail_path',
        'foto_proses_path',
        'foto_extra_path',
        'creator_id',
        'status',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class, 'creator_id');
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
}
