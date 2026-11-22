<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReguFinance extends Model
{
    use HasFactory;

    protected $table = 'regu_finances';

    protected $fillable = [
        'regu_profile_id',
        'tanggal',
        'uraian',
        'jenis', // masuk, keluar
        'nominal',
        'kategori',
        'bukti_path',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }
}
