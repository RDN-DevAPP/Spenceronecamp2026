<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnggotaRegu extends Model
{
    use HasFactory;

    protected $table = 'anggota_regu';

    protected $fillable = [
        'regu_profile_id',
        'nama',
        'nisn',
        'nta',
        'kelas',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'nomor_punggung',
        'tingkatan_tku',
        'tkk',
        'jabatan',
        'foto_path',
        'verified_at',
        'urutan',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'verified_at' => 'datetime',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function attendanceDetails(): HasMany
    {
        return $this->hasMany(ReguAttendanceDetail::class, 'anggota_regu_id');
    }

    public function sertifikatPeserta()
    {
        return $this->hasOne(SertifikatPeserta::class, 'anggota_regu_id');
    }
}
