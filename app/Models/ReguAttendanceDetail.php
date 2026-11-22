<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReguAttendanceDetail extends Model
{
    use HasFactory;

    protected $table = 'regu_attendance_details';

    protected $fillable = [
        'regu_attendance_id',
        'anggota_regu_id',
        'status', // hadir, izin, sakit, alpa
        'keterangan',
    ];

    public function attendance(): BelongsTo
    {
        return $this->belongsTo(ReguAttendance::class, 'regu_attendance_id');
    }

    public function anggotaRegu(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class, 'anggota_regu_id');
    }
}
