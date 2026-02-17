<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataLomba;
use App\Models\ScoringCriteria;

class ScoringCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define criteria for each competition
        // Name => [Criteria List]
        $competitions = [
            'Tapak Kemah' => [
                [
                    'nama_kriteria' => 'Teknis Tenda (Kekokohan, kerapian, patok)',
                    'nilai_max' => 30, // 30%
                    'nilai_min' => 0,
                    'urutan' => 1
                ],
                [
                    'nama_kriteria' => 'Tata Letak (Ergonomis, barang pribadi, dapur)',
                    'nilai_max' => 25, // 25%
                    'nilai_min' => 0,
                    'urutan' => 2
                ],
                [
                    'nama_kriteria' => 'Kebersihan & Zero Waste (Tempat sampah terpilah)',
                    'nilai_max' => 25, // 25%
                    'nilai_min' => 0,
                    'urutan' => 3
                ],
                [
                    'nama_kriteria' => 'Administrasi Regu (Daftar hadir, jadwal piket, struktur)',
                    'nilai_max' => 20, // 20%
                    'nilai_min' => 0,
                    'urutan' => 4
                ],
            ],
            'Masak Konvensional' => [
                [
                    'nama_kriteria' => 'Rasa / Taste (Kelezatan, kematangan, keaslian)',
                    'nilai_max' => 40, // 40%
                    'nilai_min' => 0,
                    'urutan' => 1
                ],
                [
                    'nama_kriteria' => 'Kandungan Gizi (Isi Piringku - Karbo, Protein, Sayur, Buah)',
                    'nilai_max' => 20, // 20%
                    'nilai_min' => 0,
                    'urutan' => 2
                ],
                [
                    'nama_kriteria' => 'Kreativitas Penyajian (Plating, Estetika)',
                    'nilai_max' => 20, // 20%
                    'nilai_min' => 0,
                    'urutan' => 3
                ],
                [
                    'nama_kriteria' => 'Kebersihan & Proses (Hygiene, manajemen limbah)',
                    'nilai_max' => 20, // 20%
                    'nilai_min' => 0,
                    'urutan' => 4
                ],
            ],
            'Upcycle Art' => [
                [
                    'nama_kriteria' => 'Narasi & Filosofi (Kedalaman makna, pesan lingkungan)',
                    'nilai_max' => 35, // 35%
                    'nilai_min' => 0,
                    'urutan' => 1
                ],
                [
                    'nama_kriteria' => 'Kreativitas Bahan (Inovasi limbah anorganik)',
                    'nilai_max' => 25, // 25%
                    'nilai_min' => 0,
                    'urutan' => 2
                ],
                [
                    'nama_kriteria' => 'Estetika Visual (Harmoni warna, teknik, komposisi)',
                    'nilai_max' => 25, // 25%
                    'nilai_min' => 0,
                    'urutan' => 3
                ],
                [
                    'nama_kriteria' => 'Ketahanan Karya (Kekuatan, fisik kokoh)',
                    'nilai_max' => 15, // 15%
                    'nilai_min' => 0,
                    'urutan' => 4
                ],
            ],
            'Desain Poster Digital' => [
                [
                    'nama_kriteria' => 'Orisinalitas Ide (Keaslian konsep, unik)',
                    'nilai_max' => 30, // 30%
                    'nilai_min' => 0,
                    'urutan' => 1
                ],
                [
                    'nama_kriteria' => 'Efektivitas Pesan (Kejelasan, keterbacaan)',
                    'nilai_max' => 30, // 30%
                    'nilai_min' => 0,
                    'urutan' => 2
                ],
                [
                    'nama_kriteria' => 'Komposisi & Estetika (Warna, layout, tipografi)',
                    'nilai_max' => 25, // 25%
                    'nilai_min' => 0,
                    'urutan' => 3
                ],
                [
                    'nama_kriteria' => 'Kualitas Teknis (Ketajaman, kerapian digital)',
                    'nilai_max' => 15, // 15% (Assuming remaining 15% to total 100%, user text cut off but total should be 100)
                    'nilai_min' => 0,
                    'urutan' => 4
                ],
            ],
        ];

        foreach ($competitions as $lombaName => $criteriaList) {
            $lomba = MataLomba::where('nama', $lombaName)->first();

            if ($lomba) {
                $this->command->info("Seeding criteria for: {$lombaName}");

                // Delete existing criteria to avoid duplicates/conflicts
                ScoringCriteria::where('mata_lomba_id', $lomba->id)->delete();

                foreach ($criteriaList as $criteria) {
                    ScoringCriteria::create([
                        'mata_lomba_id' => $lomba->id,
                        'nama_kriteria' => $criteria['nama_kriteria'],
                        'nilai_min' => $criteria['nilai_min'],
                        'nilai_max' => $criteria['nilai_max'],
                        'urutan' => $criteria['urutan'],
                    ]);
                }
            } else {
                $this->command->warn("Mata Lomba not found: {$lombaName}");
            }
        }
    }
}
