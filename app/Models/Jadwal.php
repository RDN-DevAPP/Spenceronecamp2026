<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'hari_ke',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'kegiatan',
        'lokasi',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }
}
