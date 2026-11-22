<?php

namespace Database\Seeders;

use App\Models\MataLomba;
use App\Models\Season;
use Illuminate\Database\Seeder;

class MataLombaSeason2Seeder extends Seeder
{
    /**
     * Run the database seeds for Season II.
     */
    public function run(): void
    {
        $activeSeason = Season::where('is_active', true)->first();
        $seasonId = $activeSeason?->id;

        // 5 Mata Lomba Resmi (Scored Competitions)
        $competitions = [
            [
                'season_id' => $seasonId,
                'nama' => 'Pioneer Camp Layout & Bivak',
                'slug' => 'tapak-kemah',
                'kode' => 'TPK',
                'kategori' => 'lomba',
                'is_scored' => true,
                'urutan' => 1,
                'nilai_maksimal' => 100,
                'deskripsi' => 'Perlombaan pendirian tapak kemah, gapura pionering, ketahanan bivak, tata ruang tenda, dan sanitasi perkemahan.',
                'petunjuk_teknis' => 'Setiap regu mendirikan tenda dan tapak perkemahan sesuai area kavling yang telah ditentukan panitia dengan menerapkan simpul pionering yang kokoh.',
                'ketentuan_pelaksanaan' => 'Dikerjakan oleh seluruh anggota regu dalam waktu yang ditentukan saat hari pertama perkemahan.',
                'kriteria_penilaian' => 'Kekuatan Simpul & Pasak (30%), Sanitasi & Kerapian (30%), Estetika Gapura & Pionering (25%), Administrasi Regu (15%).',
            ],
            [
                'season_id' => $seasonId,
                'nama' => 'PBB Precision & Dynamic Drill',
                'slug' => 'lkbb',
                'kode' => 'LKB',
                'kategori' => 'lomba',
                'is_scored' => true,
                'urutan' => 2,
                'nilai_maksimal' => 100,
                'deskripsi' => 'Perlombaan baris-berbaris presisi menggunakan tongkat pramuka dengan ketegasan aba-aba, kerapian saf/banjar, dan variasi formasi dinamis.',
                'petunjuk_teknis' => 'Regu menampilkan gerakan PBB dasar bertongkat serta formasi kreasi dalam batas lapangan dan durasi waktu yang telah ditentukan.',
                'ketentuan_pelaksanaan' => 'Diikuti oleh regu lengkap dipimpin langsung oleh Pemimpin Regu (Pinru).',
                'kriteria_penilaian' => 'Ketepatan PBB Dasar (35%), Kelantangan Aba-aba Pinru (25%), Kreativitas Formasi (25%), Kerapian Seragam (15%).',
            ],
            [
                'season_id' => $seasonId,
                'nama' => 'Scout Cyber Quiz Arena',
                'slug' => 'cerdas-cermat',
                'kode' => 'CCQ',
                'kategori' => 'lomba',
                'is_scored' => true,
                'urutan' => 3,
                'nilai_maksimal' => 100,
                'deskripsi' => 'Kompetisi cerdas cermat kepramukaan dan wawasan umum berbasis Computer Based Test (CBT) online multi-round terproteksi anti-cheat.',
                'petunjuk_teknis' => 'Terdiri dari Babak 1 (Pilihan Ganda Auto), Babak 2 (Isian Terstruktur), dan Babak 3 (Rebutan Final).',
                'ketentuan_pelaksanaan' => 'Diwakili oleh perwakilan anggota regu yang ditunjuk menggunakan perangkat yang telah diverifikasi.',
                'kriteria_penilaian' => 'Babak 1 PG Auto (40%), Babak 2 Isian Terstruktur (30%), Babak 3 Rebutan Final (30%).',
            ],
            [
                'season_id' => $seasonId,
                'nama' => "Scout Masterchef 'Isi Piringku'",
                'slug' => 'masak-rimba',
                'kode' => 'MSK',
                'kategori' => 'lomba',
                'is_scored' => true,
                'urutan' => 4,
                'nilai_maksimal' => 100,
                'deskripsi' => 'Kompetisi memasak rimba/konvensional dengan panduan nutrisi seimbang "Isi Piringku", higienitas dapur rimba, dan estetika penyajian plating.',
                'petunjuk_teknis' => 'Peserta mengolah bahan masakan segar di dapur perkemahan dengan alat masak lapangan konvensional.',
                'ketentuan_pelaksanaan' => 'Wajib menyajikan komposisi makanan bergizi seimbang (Karbohidrat, Protein, Sayur, dan Buah).',
                'kriteria_penilaian' => 'Cita Rasa (35%), Standar Gizi "Isi Piringku" (30%), Plating & Estetika (20%), Higienitas Dapur (15%).',
            ],
            [
                'season_id' => $seasonId,
                'nama' => 'Scout Craftsmanship: Karsa Pandu Nusantara',
                'slug' => 'hasta-karya',
                'kode' => 'HKR',
                'kategori' => 'lomba',
                'is_scored' => true,
                'urutan' => 5,
                'nilai_maksimal' => 100,
                'deskripsi' => 'Perlombaan hasta karya dan kerajinan tangan kepramukaan berbasis kearifan lokal, ketahanan bahan, dan nilai fungsi praktis.',
                'petunjuk_teknis' => 'Peserta membuat karya kerajinan tangan inovatif dengan menyertakan deskripsi filosofi dan fungsi karya.',
                'ketentuan_pelaksanaan' => 'Karya orisinal buatan regu, memadukan bahan alami/daur ulang ramah lingkungan.',
                'kriteria_penilaian' => 'Orisinalitas & Kreativitas Ide (30%), Nilai Guna & Fungsionalitas (30%), Kerapian & Ketahanan Fisik (25%), Deskripsi & Filosofi Karya (15%).',
            ],
        ];

        foreach ($competitions as $item) {
            MataLomba::updateOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }

        // Master Kategori Kegiatan Pendukung Non-Skor
        $activities = [
            [
                'season_id' => $seasonId,
                'nama' => 'Wide Game & Jungle Tracking',
                'slug' => 'wide-game',
                'kode' => 'WGM',
                'kategori' => 'giat_alam',
                'is_scored' => false,
                'urutan' => 6,
                'nilai_maksimal' => 0,
                'deskripsi' => 'Kegiatan penjelajahan alam terbuka, navigasi darat, sandi jejak, dan pos ketangkasan kepramukaan.',
                'petunjuk_teknis' => 'Seluruh regu mengikuti rute penjelajahan sesuai peta kompas dan instruksi tiap pos giat.',
                'ketentuan_pelaksanaan' => 'Kegiatan edukatif tanpa penilaian skor juara umum.',
                'kriteria_penilaian' => 'Non-skor (Kegiatan partisipatif).',
            ],
            [
                'season_id' => $seasonId,
                'nama' => 'Operasi Semut & Eco-Stewardship',
                'slug' => 'operasi-semut',
                'kode' => 'OPS',
                'kategori' => 'giat_alam',
                'is_scored' => false,
                'urutan' => 7,
                'nilai_maksimal' => 0,
                'deskripsi' => 'Aksi bakti lingkungan, pembersihan bumi perkemahan, pemilahan sampah organik dan anorganik.',
                'petunjuk_teknis' => 'Dilakukan secara gotong royong oleh seluruh peserta dan pembina perkemahan.',
                'ketentuan_pelaksanaan' => 'Wajib diikuti seluruh regu sebagai wujud Dasa Darma Pramuka kedua.',
                'kriteria_penilaian' => 'Non-skor (Bakti sosial & kelestarian alam).',
            ],
            [
                'season_id' => $seasonId,
                'nama' => 'Malam Api Unggun & Gita Pandu',
                'slug' => 'api-unggun',
                'kode' => 'APU',
                'kategori' => 'giat_seni',
                'is_scored' => false,
                'urutan' => 8,
                'nilai_maksimal' => 0,
                'deskripsi' => 'Upacara sakral penyalaan api unggun perkemahan dan pentas kreasi seni gita pandu nusantara.',
                'petunjuk_teknis' => 'Masing-masing pangkalan menampilkan atraksi kreasi seni atau lagu kepanduan di hadapan dewan kehormatan.',
                'ketentuan_pelaksanaan' => 'Dilaksanakan pada malam kedua perkemahan dalam suasana khidmat dan gembira.',
                'kriteria_penilaian' => 'Non-skor (Apresiasi seni & keakraban persaudaraan).',
            ],
        ];

        foreach ($activities as $act) {
            MataLomba::updateOrCreate(
                ['slug' => $act['slug']],
                $act
            );
        }
    }
}
