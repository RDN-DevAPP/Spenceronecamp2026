<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikatPeserta extends Model
{
    use HasFactory;

    protected $table = 'sertifikat_peserta';

    protected $fillable = [
        'anggota_regu_id',
        'nomor_sertifikat',
        'nomor_urut',
        'verify_token',
        'diterbitkan_pada',
    ];

    protected $casts = [
        'diterbitkan_pada' => 'date',
        'nomor_urut' => 'integer',
    ];

    public function anggotaRegu()
    {
        return $this->belongsTo(AnggotaRegu::class, 'anggota_regu_id');
    }
}
