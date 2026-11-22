<?php

namespace App\Support;

use App\Models\MataLomba;

/**
 * Data bawaan Lembar Penilaian PBB (Kejuaraan Cabang FORBASI Barito Kuala - U-16 PBB & Komandan Pasukan).
 * Admin dapat mengubah semua poin & nilai melalui menu Lembar Penilaian PBB.
 */
class PbbRubrikDefault
{
    public const LEVEL_LABELS = [
        'Kosong', 'Minimal', 'Kurang (K1)', 'Kurang (K2)', 'Cukup (C1)',
        'Cukup (C2)', 'Baik (B1)', 'Baik (B2)', 'Sangat Baik',
    ];

    /**
     * Mata lomba PBB (slug lkbb / lkbb-tongkat, atau nama mengandung "PBB").
     */
    public static function findLomba(): ?MataLomba
    {
        return MataLomba::whereIn('slug', ['lkbb', 'lkbb-tongkat'])->orderByRaw("slug = 'lkbb' desc")->first()
            ?? MataLomba::where('nama', 'like', '%PBB%')->first();
    }

    /** @return array<int, array{kelompok:string, nama:string, rubrik:array<int,int>}> */
    public static function items(): array
    {
        return [
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Kumpul', 'rubrik' => [0, 16, 22, 25, 28, 31, 34, 37, 41]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Sikap Sempurna', 'rubrik' => [0, 9, 15, 18, 21, 24, 27, 30, 34]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Berhitung', 'rubrik' => [0, 7, 13, 16, 19, 22, 25, 28, 32]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Istirahat', 'rubrik' => [0, 9, 15, 18, 21, 24, 27, 30, 34]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Parade Istirahat', 'rubrik' => [0, 9, 15, 18, 21, 24, 27, 30, 34]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Periksa Kerapian', 'rubrik' => [0, 16, 22, 25, 28, 31, 34, 37, 41]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Hadap Serong Kanan', 'rubrik' => [0, 10, 16, 19, 22, 25, 28, 31, 35]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => '3 Langkah ke Depan', 'rubrik' => [0, 11, 17, 20, 23, 26, 29, 32, 36]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => '3 Langkah ke Belakang', 'rubrik' => [0, 13, 19, 22, 25, 28, 31, 34, 38]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Hadap Serong Kiri', 'rubrik' => [0, 10, 16, 19, 22, 25, 28, 31, 35]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Hormat', 'rubrik' => [0, 9, 15, 18, 21, 24, 27, 30, 34]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Setengah Lencang Kiri', 'rubrik' => [0, 11, 17, 20, 23, 26, 29, 32, 36]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Lencang Kanan', 'rubrik' => [0, 11, 17, 20, 23, 26, 29, 32, 36]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Hadap Kanan', 'rubrik' => [0, 13, 19, 22, 25, 28, 31, 34, 38]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Lencang Depan', 'rubrik' => [0, 11, 17, 20, 23, 26, 29, 32, 36]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Hadap Kiri', 'rubrik' => [0, 13, 19, 22, 25, 28, 31, 34, 38]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => '3 Langkah ke Kanan', 'rubrik' => [0, 12, 18, 21, 24, 27, 30, 33, 37]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => '3 Langkah ke Kiri', 'rubrik' => [0, 13, 19, 22, 25, 28, 31, 34, 38]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Jalan di Tempat', 'rubrik' => [0, 13, 19, 22, 25, 28, 31, 34, 38]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Haluan Kiri', 'rubrik' => [0, 18, 24, 27, 30, 33, 36, 39, 43]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Langkah Biasa', 'rubrik' => [0, 16, 22, 25, 28, 31, 34, 37, 41]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => '2 x Belok Kiri', 'rubrik' => [0, 18, 24, 27, 30, 33, 36, 39, 43]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Langkah Tegap', 'rubrik' => [0, 15, 21, 24, 27, 30, 33, 36, 40]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Langkah Biasa', 'rubrik' => [0, 16, 22, 25, 28, 31, 34, 37, 41]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Tiap-tiap 2 x Belok Kanan', 'rubrik' => [0, 15, 21, 24, 27, 30, 33, 36, 40]],
            ['kelompok' => 'I. MATERI PENILAIAN PBB', 'nama' => 'Haluan Kanan', 'rubrik' => [0, 18, 24, 27, 30, 33, 36, 39, 43]],
            ['kelompok' => 'II. KOMANDAN PASUKAN', 'nama' => 'Sikap Badan', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
            ['kelompok' => 'II. KOMANDAN PASUKAN', 'nama' => 'Volume Suara', 'rubrik' => [0, 3, 5, 7, 9, 11, 13, 15, 17]],
            ['kelompok' => 'II. KOMANDAN PASUKAN', 'nama' => 'Artikulasi/Intonasi Suara', 'rubrik' => [0, 3, 5, 7, 9, 11, 13, 15, 17]],
            ['kelompok' => 'II. KOMANDAN PASUKAN', 'nama' => 'Penguasaan Materi', 'rubrik' => [0, 4, 6, 8, 10, 12, 14, 16, 18]],
            ['kelompok' => 'II. KOMANDAN PASUKAN', 'nama' => 'Penguasaan Pasukan', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
            ['kelompok' => 'II. KOMANDAN PASUKAN', 'nama' => 'Penguasaan Lapangan', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Opening', 'rubrik' => [0, 25, 30, 35, 40, 45, 50, 55, 60]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Kualitas Gerakan', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 55]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Ragam Gerak Statis & Dinamis', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 55]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Unsur PBB', 'rubrik' => [0, 15, 20, 25, 30, 35, 40, 45, 50]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Konsep Tema, Kreativitas, & Korelasi Gerakan', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 55]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Jelajah Lapangan', 'rubrik' => [0, 15, 20, 25, 30, 35, 40, 45, 50]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Nilai Etika & Estetika', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 55]],
            ['kelompok' => 'III-A. VARIASI', 'nama' => 'Ending Variasi', 'rubrik' => [0, 25, 30, 35, 40, 45, 50, 55, 60]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Proses Membuka', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 56]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Keindahan Pola, Alur, & Kualitas Gerakan (Buka)', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 56]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Detail Bentuk Akhir Formasi', 'rubrik' => [0, 25, 30, 35, 40, 45, 50, 55, 61]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Selebrasi Tema', 'rubrik' => [0, 25, 30, 35, 40, 45, 50, 55, 61]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Proses Menutup', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 56]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Keindahan Pola, Alur, & Kualitas Gerakan (Tutup)', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 56]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Unsur PBB Tambahan', 'rubrik' => [0, 15, 20, 25, 30, 35, 40, 45, 51]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Konsep Tema & Kreativitas', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 56]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Etika & Estetika Penampilan', 'rubrik' => [0, 15, 20, 25, 30, 35, 40, 45, 51]],
            ['kelompok' => 'III-B. FORMASI', 'nama' => 'Ending Celebration', 'rubrik' => [0, 20, 25, 30, 35, 40, 45, 50, 56]],
            ['kelompok' => 'III-C. PENILAIAN DANTON SAAT VARIASI & FORMASI', 'nama' => 'Aba-Aba Variasi', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
            ['kelompok' => 'III-C. PENILAIAN DANTON SAAT VARIASI & FORMASI', 'nama' => 'Aba-Aba Buka Formasi', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
            ['kelompok' => 'III-C. PENILAIAN DANTON SAAT VARIASI & FORMASI', 'nama' => 'Aba-Aba Tutup Formasi', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
            ['kelompok' => 'III-C. PENILAIAN DANTON SAAT VARIASI & FORMASI', 'nama' => 'Artikulasi & Intonasi', 'rubrik' => [0, 4, 6, 8, 10, 12, 14, 16, 18]],
            ['kelompok' => 'III-C. PENILAIAN DANTON SAAT VARIASI & FORMASI', 'nama' => 'Penampilan & Penghayatan', 'rubrik' => [0, 4, 6, 8, 10, 12, 14, 16, 18]],
            ['kelompok' => 'III-C. PENILAIAN DANTON SAAT VARIASI & FORMASI', 'nama' => 'Sinergitas Dengan Pasukan', 'rubrik' => [0, 2, 4, 6, 8, 10, 12, 14, 16]],
        ];
    }
}
