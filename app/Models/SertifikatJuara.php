<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SertifikatJuara extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_juaras';

    protected $fillable = [
        'season_id',
        'mata_lomba_id',
        'regu_profile_id',
        'anggota_regu_id',
        'nama_penerima',
        'nama_lomba',
        'kategori',
        'peringkat',
        'mode',
        'nomor_sertifikat',
        'nomor_urut',
        'verify_token',
        'diterbitkan_pada',
    ];

    protected function casts(): array
    {
        return [
            'diterbitkan_pada' => 'date',
            'nomor_urut' => 'integer',
        ];
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function anggotaRegu(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class);
    }
}
