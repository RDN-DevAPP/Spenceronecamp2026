<?php

namespace Database\Seeders;

use App\Models\MataLomba;
use App\Models\ScoringCriteria;
use Illuminate\Database\Seeder;

class KriteriaSeason2Seeder extends Seeder
{
    /**
     * Run the database seeds for Season II Scoring Criteria.
     */
    public function run(): void
    {
        $criteriaMap = [
            'tapak-kemah' => [
                [
                    'nama_kriteria' => 'Kekuatan Simpul & Pasak',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 1,
                ],
                [
                    'nama_kriteria' => 'Sanitasi & Kerapian',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 2,
                ],
                [
                    'nama_kriteria' => 'Estetika Gapura & Pionering',
                    'nilai_min' => 0,
                    'nilai_max' => 25, // 25%
                    'urutan' => 3,
                ],
                [
                    'nama_kriteria' => 'Administrasi Regu',
                    'nilai_min' => 0,
                    'nilai_max' => 15, // 15%
                    'urutan' => 4,
                ],
            ],
            'lkbb' => [
                [
                    'nama_kriteria' => 'Ketepatan PBB Dasar',
                    'nilai_min' => 0,
                    'nilai_max' => 35, // 35%
                    'urutan' => 1,
                ],
                [
                    'nama_kriteria' => 'Kelantangan Aba-aba Pinru',
                    'nilai_min' => 0,
                    'nilai_max' => 25, // 25%
                    'urutan' => 2,
                ],
                [
                    'nama_kriteria' => 'Kreativitas Formasi',
                    'nilai_min' => 0,
                    'nilai_max' => 25, // 25%
                    'urutan' => 3,
                ],
                [
                    'nama_kriteria' => 'Kerapian Seragam',
                    'nilai_min' => 0,
                    'nilai_max' => 15, // 15%
                    'urutan' => 4,
                ],
            ],
            'cerdas-cermat' => [
                [
                    'nama_kriteria' => 'Babak 1 PG Auto',
                    'nilai_min' => 0,
                    'nilai_max' => 40, // 40%
                    'urutan' => 1,
                ],
                [
                    'nama_kriteria' => 'Babak 2 Isian Terstruktur',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 2,
                ],
                [
                    'nama_kriteria' => 'Babak 3 Rebutan Final',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 3,
                ],
            ],
            'masak-rimba' => [
                [
                    'nama_kriteria' => 'Cita Rasa',
                    'nilai_min' => 0,
                    'nilai_max' => 35, // 35%
                    'urutan' => 1,
                ],
                [
                    'nama_kriteria' => 'Standar Gizi "Isi Piringku"',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 2,
                ],
                [
                    'nama_kriteria' => 'Plating & Estetika',
                    'nilai_min' => 0,
                    'nilai_max' => 20, // 20%
                    'urutan' => 3,
                ],
                [
                    'nama_kriteria' => 'Higienitas Dapur',
                    'nilai_min' => 0,
                    'nilai_max' => 15, // 15%
                    'urutan' => 4,
                ],
            ],
            'hasta-karya' => [
                [
                    'nama_kriteria' => 'Orisinalitas & Kreativitas Ide',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 1,
                ],
                [
                    'nama_kriteria' => 'Nilai Guna & Fungsionalitas',
                    'nilai_min' => 0,
                    'nilai_max' => 30, // 30%
                    'urutan' => 2,
                ],
                [
                    'nama_kriteria' => 'Kerapian & Ketahanan Fisik',
                    'nilai_min' => 0,
                    'nilai_max' => 25, // 25%
                    'urutan' => 3,
                ],
                [
                    'nama_kriteria' => 'Deskripsi & Filosofi Karya',
                    'nilai_min' => 0,
                    'nilai_max' => 15, // 15%
                    'urutan' => 4,
                ],
            ],
        ];

        foreach ($criteriaMap as $slug => $criteriaList) {
            $mataLomba = MataLomba::where('slug', $slug)->first();

            if (!$mataLomba) {
                continue;
            }

            // Hapus kriteria lama untuk slug ini jika ada untuk mencegah duplikasi
            ScoringCriteria::where('mata_lomba_id', $mataLomba->id)->delete();

            foreach ($criteriaList as $crit) {
                ScoringCriteria::create([
                    'mata_lomba_id' => $mataLomba->id,
                    'nama_kriteria' => $crit['nama_kriteria'],
                    'nilai_min' => $crit['nilai_min'],
                    'nilai_max' => $crit['nilai_max'],
                    'urutan' => $crit['urutan'],
                ]);
            }
        }
    }
}
