<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataLomba extends Model
{
    use HasFactory;

    protected $table = 'mata_lomba';

    protected $fillable = [
        'season_id',
        'nama',
        'kode',
        'slug',
        'kategori',
        'is_scored',
        'urutan',
        'deskripsi',
        'nilai_maksimal',
        'petunjuk_teknis',
        'ketentuan_pelaksanaan',
        'kriteria_penilaian',
        'wajib_upload_foto',
        'instruksi_upload_foto',
        'max_foto',
    ];

    protected function casts(): array
    {
        return [
            'is_scored' => 'boolean',
            'wajib_upload_foto' => 'boolean',
            'max_foto' => 'integer',
        ];
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'mata_lomba_id');
    }

    public function scoringCriteria(): HasMany
    {
        return $this->hasMany(ScoringCriteria::class, 'mata_lomba_id');
    }

    public function juris(): HasMany
    {
        return $this->hasMany(User::class, 'mata_lomba_id');
    }
}
