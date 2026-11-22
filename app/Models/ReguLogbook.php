<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReguLogbook extends Model
{
    use HasFactory;

    protected $table = 'regu_logbooks';

    protected $fillable = [
        'regu_profile_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'nama_kegiatan',
        'tempat',
        'pemimpin_kegiatan',
        'ringkasan_materi',
        'hasil_kegiatan',
        'foto_kegiatan_path',
        'catatan_evaluasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }
}
