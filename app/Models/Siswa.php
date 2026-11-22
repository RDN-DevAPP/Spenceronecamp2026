<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'nama',
        'nisn',
        'kelas',
        'tingkatan_tku',
        'jenis_kelamin',
        'regu_profile_id',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function isPutra(): bool
    {
        return $this->jenis_kelamin === 'L';
    }

    public function isPutri(): bool
    {
        return $this->jenis_kelamin === 'P';
    }
}
