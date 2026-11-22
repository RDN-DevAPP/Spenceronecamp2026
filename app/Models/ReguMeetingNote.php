<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReguMeetingNote extends Model
{
    use HasFactory;

    protected $table = 'regu_meeting_notes';

    protected $fillable = [
        'regu_profile_id',
        'tanggal',
        'waktu',
        'tempat',
        'agenda',
        'pemimpin_rapat',
        'notulis',
        'jumlah_hadir',
        'daftar_hadir_teks',
        'jalannya_rapat',
        'keputusan',
        'catatan_tambahan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah_hadir' => 'integer',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }
}
