<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanitiaMember extends Model
{
    use HasFactory;

    protected $table = 'panitia_members';

    protected $fillable = [
        'season_id',
        'nama',
        'jabatan',
        'divisi',
        'asal_instansi',
        'no_hp',
        'foto_path',
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
}
