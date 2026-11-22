<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReguAttendance extends Model
{
    use HasFactory;

    protected $table = 'regu_attendances';

    protected $fillable = [
        'regu_profile_id',
        'tanggal',
        'nama_kegiatan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function reguProfile(): BelongsTo
    {
        return $this->belongsTo(ReguProfile::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReguAttendanceDetail::class, 'regu_attendance_id');
    }
}
