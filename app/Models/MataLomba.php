<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataLomba extends Model
{
    use HasFactory;

    protected $table = 'mata_lomba';

    protected $fillable = [
        'nama',
        'kode',
        'slug',
        'urutan',
        'deskripsi',
        'nilai_maksimal',
        'petunjuk_teknis',
        'ketentuan_pelaksanaan',
        'kriteria_penilaian',
    ];

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
