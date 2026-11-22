<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReguInventory extends Model
{
    use HasFactory;

    protected $table = 'regu_inventories';

    protected $fillable = [
        'regu_profile_id',
        'kode_barang',
        'nama_barang',
        'jumlah',
        'satuan',
        'kondisi', // baik, rusak_ringan, rusak_berat
        'asal_barang',
        'tanggal_perolehan',
        'lokasi_penyimpanan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_perolehan' => 'date',
        'jumlah' => 'integer',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }
}
