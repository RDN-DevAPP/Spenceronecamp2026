<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReguProfile extends Model
{
    use HasFactory;

    protected $table = 'regu_profiles';

    protected $fillable = [
        'season_id',
        'user_id',
        'nama_regu',
        'jenis',
        'nomor_regu',
        'nomor_kavling',
        'poster_digital_path',
        'foto_regu_path',
        'foto_regu_caption',
        'foto_tenda_path',
        'tenda_description',
        'foto_masakan_path',
        'masakan_description',
        'foto_karya_path',
        'poster_creator_id',
        'upcycle_creator_id',
        'poster_description',
        'upcycle_description',
    ];

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function anggotaRegu(): HasMany
    {
        return $this->hasMany(AnggotaRegu::class, 'regu_profile_id');
    }

    public function karyaLombas(): HasMany
    {
        return $this->hasMany(KaryaLomba::class, 'regu_profile_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'regu_profile_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ReguVote::class, 'regu_profile_id');
    }

    public function paidVoteTransactions(): HasMany
    {
        return $this->hasMany(PaidVoteTransaction::class, 'regu_profile_id');
    }

    public function finances(): HasMany
    {
        return $this->hasMany(ReguFinance::class, 'regu_profile_id');
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(ReguLogbook::class, 'regu_profile_id');
    }

    public function meetingNotes(): HasMany
    {
        return $this->hasMany(ReguMeetingNote::class, 'regu_profile_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ReguAttendance::class, 'regu_profile_id');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(ReguInventory::class, 'regu_profile_id');
    }

    public function posterCreator(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class, 'poster_creator_id');
    }

    public function upcycleCreator(): BelongsTo
    {
        return $this->belongsTo(AnggotaRegu::class, 'upcycle_creator_id');
    }

    public function cerdasCermatSession()
    {
        return $this->hasOne(CerdasCermatSession::class, 'regu_id');
    }

    public static function getMinAnggotaRequired(): int
    {
        try {
            return max(1, (int) CerdasCermatSetting::getValue('min_anggota_regu', 1));
        } catch (\Throwable $e) {
            return 1;
        }
    }

    public function isEligibleForCerdasCermat(): bool
    {
        return $this->anggotaRegu()->count() >= self::getMinAnggotaRequired();
    }

    /**
     * Total nilai dari semua mata lomba (sum atau rata-rata tergantung aturan).
     */
    public function totalNilai(): float
    {
        return (float) $this->scores()->sum('nilai');
    }

    public function isPutra(): bool
    {
        return $this->jenis === 'putra';
    }

    public function isPutri(): bool
    {
        return $this->jenis === 'putri';
    }

    public function getCompetitionPhoto(string $lombaName): ?string
    {
        return match ($lombaName) {
            'Tapak Kemah' => $this->foto_tenda_path,
            'Masak Konvensional' => $this->foto_masakan_path,
            'Upcycle Art' => $this->foto_karya_path,
            'Desain Poster Digital' => $this->poster_digital_path,
            default => null,
        };
    }
}
