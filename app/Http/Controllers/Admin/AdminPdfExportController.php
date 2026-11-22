<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReguProfile;
use App\Models\MataLomba;
use App\Models\Score;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminPdfExportController extends Controller
{
    /**
     * Cetak Berita Acara Rekapitulasi Nilai & Pengesahan Juara Umum (A4 Portrait)
     */
    public function beritaAcara()
    {
        // Ambil data mata lomba
        $mataLombaList = MataLomba::orderBy('urutan')->get();

        // Rekapitulasi Skor Regu Putra
        $rekapPutra = $this->getRekapNilaiByGender('putra', $mataLombaList);
        
        // Rekapitulasi Skor Regu Putri
        $rekapPutri = $this->getRekapNilaiByGender('putri', $mataLombaList);

        $data = [
            'tanggal' => now()->translatedFormat('l, d F Y'),
            'mataLombaList' => $mataLombaList,
            'rekapPutra' => $rekapPutra,
            'rekapPutri' => $rekapPutri,
            'juaraUmumPutra' => $rekapPutra[0] ?? null,
            'juaraUmumPutri' => $rekapPutri[0] ?? null,
        ];

        $pdf = Pdf::loadView('pdf.berita-acara', $data)
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Berita-Acara-Rekapitulasi-SpenceroneCamp-Season2.pdf');
    }

    /**
     * Cetak Piagam Penghargaan Kejuaraan (A4 Landscape)
     * Dapat mencetak single regu (via param reguId) atau seluruh juara (mass print)
     */
    public function piagamPenghargaan($reguId = null)
    {
        $regus = [];
        $mataLombaList = MataLomba::orderBy('urutan')->get();

        if ($reguId) {
            $regu = ReguProfile::with('karyaLombas', 'anggotaRegu')->findOrFail($reguId);
            $regus[] = $this->formatPiagamData($regu, $mataLombaList);
        } else {
            // Mass print for top 3 Putra & top 3 Putri
            $rekapPutra = $this->getRekapNilaiByGender('putra', $mataLombaList);
            $rekapPutri = $this->getRekapNilaiByGender('putri', $mataLombaList);

            $ranks = ['Juara Umum 1', 'Runner-up 1', 'Runner-up 2'];
            
            foreach (array_slice($rekapPutra, 0, 3) as $idx => $item) {
                $reguModel = ReguProfile::with('anggotaRegu')->find($item['regu_id']);
                if ($reguModel) {
                    $regus[] = [
                        'regu' => $reguModel,
                        'kategori' => 'Regu Putra (Gudep 03.059)',
                        'peringkat' => $ranks[$idx] ?? 'Juara Harapan',
                        'total_skor' => $item['total_skor'],
                        'nomor_piagam' => '00' . ($idx + 1) . '/SC-II/PIAGAM/PA/' . date('Y'),
                    ];
                }
            }

            foreach (array_slice($rekapPutri, 0, 3) as $idx => $item) {
                $reguModel = ReguProfile::with('anggotaRegu')->find($item['regu_id']);
                if ($reguModel) {
                    $regus[] = [
                        'regu' => $reguModel,
                        'kategori' => 'Regu Putri (Gudep 03.060)',
                        'peringkat' => $ranks[$idx] ?? 'Juara Harapan',
                        'total_skor' => $item['total_skor'],
                        'nomor_piagam' => '00' . ($idx + 1) . '/SC-II/PIAGAM/PI/' . date('Y'),
                    ];
                }
            }
        }

        $pdf = Pdf::loadView('pdf.piagam-penghargaan', ['certificates' => $regus])
            ->setPaper('a4', 'landscape');

        return $pdf->stream('Piagam-Penghargaan-SpenceroneCamp-Season2.pdf');
    }

    private function getRekapNilaiByGender($gender, $mataLombaList)
    {
        $regus = ReguProfile::where('jenis', $gender)->get();
        $results = [];

        foreach ($regus as $regu) {
            $scoresByLomba = [];
            $totalSkor = 0;

            foreach ($mataLombaList as $ml) {
                // Rata-rata skor lomba jika dinilai oleh beberapa juri
                $avgScore = Score::where('regu_profile_id', $regu->id)
                    ->where('mata_lomba_id', $ml->id)
                    ->where('status', '!=', 'draft')
                    ->avg('nilai');

                $scoreVal = $avgScore ? round($avgScore, 2) : 0;
                $scoresByLomba[$ml->id] = $scoreVal;
                $totalSkor += $scoreVal;
            }

            $results[] = [
                'regu_id' => $regu->id,
                'nama_regu' => $regu->nama_regu,
                'pangkalan' => $regu->sekolah ?? 'Gudep SMPN 1 Cerbon',
                'scores' => $scoresByLomba,
                'total_skor' => $totalSkor,
            ];
        }

        // Urutkan dari nilai total tertinggi
        usort($results, fn($a, $b) => $b['total_skor'] <=> $a['total_skor']);

        return $results;
    }

    private function formatPiagamData(ReguProfile $regu, $mataLombaList)
    {
        $totalSkor = Score::where('regu_profile_id', $regu->id)
            ->where('status', '!=', 'draft')
            ->sum('nilai');

        return [
            'regu' => $regu,
            'kategori' => $regu->jenis === 'putra' ? 'Regu Putra (Gudep 03.059)' : 'Regu Putri (Gudep 03.060)',
            'peringkat' => 'Peserta Teladan & Berprestasi',
            'total_skor' => round($totalSkor, 1),
            'nomor_piagam' => '0' . $regu->id . '/SC-II/PIAGAM/' . strtoupper($regu->jenis) . '/' . date('Y'),
        ];
    }
}
