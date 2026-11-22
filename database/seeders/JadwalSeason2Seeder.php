<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Season;
use Illuminate\Database\Seeder;

class JadwalSeason2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activeSeason = Season::where('is_active', true)->first();
        $seasonId = $activeSeason?->id;

        $rundown = [
            // HARI 1: JUM'AT, 15 SEPTEMBER 2026
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '08:30:00',
                'kegiatan' => 'Registrasi Regu & Penataan Awal Kavling Tenda',
                'lokasi' => 'Bumi Perkemahan SMPN 1 Cerbon',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '08:30:00',
                'waktu_selesai' => '09:30:00',
                'kegiatan' => 'Upacara Pembukaan Spencerone Camp Season II',
                'lokasi' => 'Lapangan Utama Buper',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '09:30:00',
                'waktu_selesai' => '11:30:00',
                'kegiatan' => 'Lomba: Pioneer Camp Layout & Bivak (Tapak Kemah)',
                'lokasi' => 'Kavling Regu Masing-masing',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '11:30:00',
                'waktu_selesai' => '13:30:00',
                'kegiatan' => 'Ishoma (Sholat Jum\'at Berjamaah & Santap Siang)',
                'lokasi' => 'Masjid Sekolah & Tenda Regu',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '13:30:00',
                'waktu_selesai' => '15:30:00',
                'kegiatan' => 'Lomba: PBB Precision & Dynamic Drill (LKBB Bertongkat)',
                'lokasi' => 'Lapangan Upacara Utama',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '16:00:00',
                'waktu_selesai' => '17:30:00',
                'kegiatan' => 'Operasi Semut & Eco-Stewardship (Pembersihan Lingkungan)',
                'lokasi' => 'Zona Konservasi Buper',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 1,
                'tanggal' => '2026-09-15',
                'waktu_mulai' => '19:30:00',
                'waktu_selesai' => '21:30:00',
                'kegiatan' => 'Lomba: Scout Cyber Quiz Arena (Babak 1 PG & Babak 2 Isian)',
                'lokasi' => 'Laboratorium CBT Digital',
            ],

            // HARI 2: SABTU, 16 SEPTEMBER 2026
            [
                'season_id' => $seasonId,
                'hari_ke' => 2,
                'tanggal' => '2026-09-16',
                'waktu_mulai' => '05:30:00',
                'waktu_selesai' => '06:30:00',
                'kegiatan' => 'Senam Pramuka Nusantara & Giat Bugar Pagi',
                'lokasi' => 'Lapangan Utama',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 2,
                'tanggal' => '2026-09-16',
                'waktu_mulai' => '06:30:00',
                'waktu_selesai' => '08:30:00',
                'kegiatan' => 'Lomba: Scout Masterchef \'Isi Piringku\' (Masak Rimba)',
                'lokasi' => 'Area Dapur Lapangan',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 2,
                'tanggal' => '2026-09-16',
                'waktu_mulai' => '08:30:00',
                'waktu_selesai' => '11:30:00',
                'kegiatan' => 'Wide Game & Jungle Tracking (Pos Sandi, Kompas, & Ketangkasan)',
                'lokasi' => 'Rute Penjelajahan Alam Luar',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 2,
                'tanggal' => '2026-09-16',
                'waktu_mulai' => '13:30:00',
                'waktu_selesai' => '15:30:00',
                'kegiatan' => 'Lomba: Scout Craftsmanship: Karsa Pandu Nusantara (Hasta Karya)',
                'lokasi' => 'Pendopo Kreasi Pramuka',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 2,
                'tanggal' => '2026-09-16',
                'waktu_mulai' => '15:30:00',
                'waktu_selesai' => '17:00:00',
                'kegiatan' => 'Lomba: Final Scout Cyber Quiz Arena (Babak 3 Rebutan)',
                'lokasi' => 'Panggung Utama',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 2,
                'tanggal' => '2026-09-16',
                'waktu_mulai' => '19:30:00',
                'waktu_selesai' => '22:30:00',
                'kegiatan' => 'Malam Api Unggun & Gita Pandu (Pentas Seni Budaya)',
                'lokasi' => 'Lingkaran Api Unggun Utama',
            ],

            // HARI 3: MINGGU, 17 SEPTEMBER 2026
            [
                'season_id' => $seasonId,
                'hari_ke' => 3,
                'tanggal' => '2026-09-17',
                'waktu_mulai' => '07:30:00',
                'waktu_selesai' => '09:00:00',
                'kegiatan' => 'Bakti Lingkungan & Pembongkaran Tapak Kemah',
                'lokasi' => 'Bumi Perkemahan',
            ],
            [
                'season_id' => $seasonId,
                'hari_ke' => 3,
                'tanggal' => '2026-09-17',
                'waktu_mulai' => '09:00:00',
                'waktu_selesai' => '11:00:00',
                'kegiatan' => 'Upacara Penutupan & Pengumuman Juara Umum Season II',
                'lokasi' => 'Lapangan Utama',
            ],
        ];

        foreach ($rundown as $item) {
            Jadwal::updateOrCreate(
                [
                    'hari_ke' => $item['hari_ke'],
                    'tanggal' => $item['tanggal'],
                    'waktu_mulai' => $item['waktu_mulai'],
                    'kegiatan' => $item['kegiatan'],
                ],
                $item
            );
        }
    }
}
