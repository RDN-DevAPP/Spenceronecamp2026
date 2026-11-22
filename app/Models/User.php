<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_SUB_ADMIN = 'sub_admin';
    public const ROLE_JURI = 'juri';
    public const ROLE_REGU = 'regu';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'permissions',
        'mata_lomba_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }

    public function reguProfile(): HasOne
    {
        return $this->hasOne(ReguProfile::class);
    }

    public function mataLomba(): BelongsTo
    {
        return $this->belongsTo(MataLomba::class);
    }

    public function mataLombas(): BelongsToMany
    {
        return $this->belongsToMany(MataLomba::class, 'user_mata_lomba')->withTimestamps();
    }

    /**
     * Menentukan apakah user adalah Admin Utama (Super Admin) dengan hak akses penuh tak terbatas.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Menentukan apakah user adalah Admin Khusus (Sub Admin) dengan hak akses terbatas yang diatur.
     */
    public function isSubAdmin(): bool
    {
        return $this->role === self::ROLE_SUB_ADMIN;
    }

    /**
     * Menentukan apakah user berhak masuk ke panel admin (baik Admin Utama maupun Sub Admin).
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_SUB_ADMIN;
    }

    /**
     * Memeriksa apakah user memiliki hak akses ke modul fitur admin tertentu.
     */
    public function hasPermission(string $feature): bool
    {
        // Admin Utama selalu punya akses ke semua fitur
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Sub Admin tidak pernah diizinkan mengakses pengaturan user
        if ($this->isSubAdmin()) {
            if ($feature === 'users') {
                return false;
            }

            $permissions = $this->permissions ?? [];
            return in_array($feature, $permissions, true);
        }

        return false;
    }

    /**
     * Menentukan route pertama yang berhak diakses oleh user ini.
     */
    public function getFirstPermittedRoute(): ?string
    {
        if ($this->isSuperAdmin()) {
            return route('admin.dashboard');
        }

        if ($this->isSubAdmin()) {
            $available = self::availablePermissions();
            $permissions = $this->permissions ?? [];

            foreach ($permissions as $permKey) {
                if (isset($available[$permKey]['route']) && \Illuminate\Support\Facades\Route::has($available[$permKey]['route'])) {
                    return route($available[$permKey]['route']);
                }
            }
        }

        return null;
    }

    /**
     * Daftar fitur admin yang dapat didelegasikan ke Sub Admin (Admin Khusus).
     * Fitur Manajemen User sengaja tidak dimasukkan karena hanya khusus Admin Utama.
     */
    public static function availablePermissions(): array
    {
        return [
            'dashboard' => [
                'label' => 'Dashboard Ringkasan',
                'description' => 'Melihat ringkasan statistik, perolehan medali, dan grafik kegiatan.',
                'category' => 'Umum',
                'icon' => 'layout-dashboard',
                'route' => 'admin.dashboard',
            ],
            'scores' => [
                'label' => 'Verifikasi Nilai Juri',
                'description' => 'Memeriksa, memverifikasi status kunci nilai, dan persetujuan nilai juri.',
                'category' => 'Penilaian & Lomba',
                'icon' => 'clipboard-check',
                'route' => 'admin.scores.index',
            ],
            'rekap_nilai' => [
                'label' => 'Rekapitulasi Nilai & Juara',
                'description' => 'Melihat perolehan medali, rekap nilai keseluruhan, dan ekspor data nilai.',
                'category' => 'Penilaian & Lomba',
                'icon' => 'file-text',
                'route' => 'admin.rekap-nilai.index',
            ],
            'pbb_rubrik' => [
                'label' => 'Rubrik Penilaian PBB',
                'description' => 'Mengatur kriteria rubrik lomba PBB dan cetak lembar penilaian juri.',
                'category' => 'Penilaian & Lomba',
                'icon' => 'clipboard-list',
                'route' => 'admin.pbb-rubrik.index',
            ],
            'cerdas_cermat' => [
                'label' => 'Manajemen Cerdas Cermat',
                'description' => 'Mengelola bank soal, timer, sesi kuis peserta, dan monitoring babak.',
                'category' => 'Penilaian & Lomba',
                'icon' => 'brain',
                'route' => 'admin.cerdas-cermat.index',
            ],
            'informasi_lomba' => [
                'label' => 'Katalog Lomba & Kriteria',
                'description' => 'Mengatur juknis lomba, kriteria penilaian, dan aturan unggah karya.',
                'category' => 'Penilaian & Lomba',
                'icon' => 'trophy',
                'route' => 'admin.informasi-lomba.index',
            ],
            'jadwal' => [
                'label' => 'Jadwal & Rundown Acara',
                'description' => 'Mengelola jadwal kegiatan per hari, jam pelaksanaan, dan lokasi.',
                'category' => 'Penilaian & Lomba',
                'icon' => 'calendar',
                'route' => 'admin.jadwal.index',
            ],
            'id_cards' => [
                'label' => 'ID Card & Barcode Peserta',
                'description' => 'Generate barcode regu/panitia dan cetak kartu tanda pengenal.',
                'category' => 'Administrasi & Peserta',
                'icon' => 'id-card',
                'route' => 'admin.id-cards.index',
            ],
            'verifikasi_qr' => [
                'label' => 'Scanner Verifikasi QR',
                'description' => 'Melakukan pemindaian barcode/QR code peserta di pos giat lapangan.',
                'category' => 'Administrasi & Peserta',
                'icon' => 'qr-code',
                'route' => 'admin.verifikasi-qr.index',
            ],
            'sertifikat' => [
                'label' => 'Cetak Sertifikat Peserta',
                'description' => 'Menetapkan nomor sertifikat resmi, token QR online, dan unduh PDF.',
                'category' => 'Administrasi & Peserta',
                'icon' => 'award',
                'route' => 'admin.sertifikat.peserta',
            ],
            'siswa' => [
                'label' => 'Master Data Siswa / Peserta',
                'description' => 'Mengelola data induk siswa, NTA pramuka, dan kelengkapan data.',
                'category' => 'Administrasi & Peserta',
                'icon' => 'book-user',
                'route' => 'admin.siswa.index',
            ],
            'sponsorships' => [
                'label' => 'Sponsorship & Donatur',
                'description' => 'Memverifikasi bukti transfer sponsor dan pengaturan tier pendukung.',
                'category' => 'Finansial & Mitra',
                'icon' => 'handshake',
                'route' => 'admin.sponsorships.index',
            ],
            'financial_reports' => [
                'label' => 'Laporan Keuangan Terbuka',
                'description' => 'Mengunggah dan mempublikasikan dokumen laporan transparansi kas.',
                'category' => 'Finansial & Mitra',
                'icon' => 'wallet',
                'route' => 'admin.financial-reports.index',
            ],
            'voting' => [
                'label' => 'Voting Regu Terfavorit',
                'description' => 'Melihat daftar voter, kunci jumlah suara, dan jadwal tutup vote.',
                'category' => 'Interaktif',
                'icon' => 'heart',
                'route' => 'admin.voting.index',
            ],
        ];
    }

    public function isJuri(): bool
    {
        return $this->role === self::ROLE_JURI;
    }

    public function isRegu(): bool
    {
        return $this->role === self::ROLE_REGU;
    }
}
