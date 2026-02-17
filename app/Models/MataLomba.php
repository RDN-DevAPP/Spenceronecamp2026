<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataLomba extends Model
{
    use HasFactory;

    protected $table = 'mata_lomba';

    protected $fillable = [
        'nama',
        'slug',
        'urutan',
        'deskripsi',
        'nilai_maksimal',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'mata_lomba_id');
    }
}
