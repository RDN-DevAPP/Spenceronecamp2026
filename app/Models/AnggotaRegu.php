<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggotaRegu extends Model
{
    use HasFactory;

    protected $table = 'anggota_regu';

    protected $fillable = [
        'regu_profile_id',
        'nama',
        'nomor_punggung',
        'jabatan',
        'urutan',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }
}
