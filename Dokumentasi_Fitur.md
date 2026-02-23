# Dokumentasi Fitur Aplikasi LT-I Spencerone Camp 2026

Dokumen ini berisi daftar dan penjelasan seluruh fitur yang ada pada aplikasi sistem informasi dan penilaian lomba LT-I Spencerone Camp 2026 berdasarkan pemetaan rute (routes) dan akses perannya.

---

## 1. Fitur Publik (Guest / Tanpa Login)
Pengguna umum dapat mengakses halaman-halaman berikut tanpa perlu login:

- **Halaman Utama (Landing Page) (`/`)**: Menampilkan _hero section_, _countdown timer_, serta klasemen Juara Umum (dengan sensor nama regu untuk peringkat 3 besar jika belum di-_reveal_). Serta menampilkan daftar sponsorship (Platinum, Gold, Silver).
- **Informasi Lomba (`/informasi-lomba`)**: Menampilkan panduan dan informasi lengkap mengenai mata lomba yang diselenggarakan.
- **Jadwal Lomba (`/jadwal`)**: Menampilkan jadwal rangkaian acara dan lomba.
- **Pendaftaran Sponsorship (`/sponsorship/daftar`)**: Form bagi pihak luar yang ingin mendaftar sebagai sponsor acara.
- **Otentikasi (`/login`, `/logout`)**: Halaman untuk masuk ke dalam sistem bagi Peserta (Regu), Juri, dan Admin.

---

## 2. Fitur Peserta (Peran: Regu)
Akses khusus untuk pengguna yang login sebagai perwakilan Regu (Peserta).

- **Dashboard Peserta (`/peserta/dashboard`)**: Halaman utama peserta yang merangkum data regu, daftar anggota, galeri foto, rekap nilai lomba keseluruhan, dan klasemen (jika diizinkan).
- **Manajemen Anggota Regu (`/peserta/anggota/*`)**: 
  - Tambah Anggota
  - Edit Anggota
  - Hapus Anggota
- **Galeri & Upload Karya (`/peserta/upload-photo`, `/peserta/poster`)**: Fitur untuk mengunggah dan menghapus dokumen foto untuk evaluasi, seperti:
  - Foto Tapak Kemah
  - Foto Masak Konvensional
  - Foto Upcycle Art
  - Desain Poster Digital
- **Panel Cerdas Cermat (`/peserta/cerdas-cermat/*`)**:
  - Konfirmasi kehadiran / Pendaftaran awal sesi cerdas cermat.
  - Mengakses dan mengikuti **Round 1**, **Round 2**, dan **Round 3** saat sesi diaktifkan oleh Admin/Juri.

---

## 3. Fitur Juri (Peran: Juri)
Akses khusus untuk pengguna yang bertugas sebagai penilai (Juri).

- **Dashboard Juri (`/juri/dashboard`)**: Halaman utama yang menampilkan daftar mata lomba yang harus dinilai.
- **Penilaian Lomba Regu (`/juri/lomba/{slug}`)**: Memasukkan nilai dan catatan evaluasi untuk spesifik mata lomba ke spesifik regu.
- **Manajemen Penilaian (`/juri/scores/*`)**: 
  - Submit nilai individual atau borongan (_bulk_).
  - Melakukan konfirmasi (Approve/Reject) jika ada mekanisme verifikasi silang (tergantung alur).
- **Penilaian Cerdas Cermat (`/juri/cerdas-cermat/*`)**:
  - Memantau kualifikasi dan sesi Cerdas Cermat secara real-time.
  - Melakukan verifikasi dan memberikan poin secara langsung (`grade` / `verify`) pada jawaban peserta (khususnya untuk Round 2 & Round 3 yang membutuhkan juri interaktif).

---

## 4. Fitur Admin (Peran: Admin)
Akses tertinggi untuk mengelola keseluruhan aplikasi dan mengatur jalannya kompetisi.

- **Dashboard Admin (`/admin/dashboard`)**: Menampilkan ringkasan statistik aplikasi, jumlah peserta, jumlah pengguna, rekap nilai, dll.
- **Manajemen Pengguna (`/admin/users/*`)**: CRUD (Create, Read, Update, Delete) data akun untuk Admin, Juri, dan Regu (termasuk reset password regu).
- **Manajemen Kriteria & Mata Lomba (`/admin/kriteria/*`, `/admin/mata-lomba/*`)**: 
  - Membuat dan mengedit panduan kriteria penilaian untuk setiap mata lomba.
- **Manajemen Sponsorship (`/admin/sponsorships/*`)**: Menyetujui (_Approve_), menolak, mengedit, atau menghapus data sponsor yang mendaftar atau yang dimasukkan secara manual.
- **Manajemen Informasi Lomba (`/admin/informasi-lomba/*`)**: Mengelola konten yang tampil di halaman "Informasi Lomba" publik.
- **Manajemen Jadwal (`/admin/jadwal/*`)**: Mengelola (CRUD) entri jadwal acara dan lomba beserta seting tampilan tanggal acara.
- **Manajemen Cerdas Cermat (`/admin/cerdas-cermat/*`)**:
  - Membuat sesi cerdas cermat, mengimpor soal (_import_), dan mengatur babak (Round).
  - Mereset (_Reset_) sesi cerdas cermat jika ada kesalahan teknis (`/admin/cerdas-cermat/sessions/{id}`).
  - Mengelola pengaturan umum (_settings_) Cerdas Cermat.
- **Verifikasi Seluruh Nilai (`/admin/scores/*`)**: Melihat rekap seluruh nilai yang di-_submit_ oleh seluruh juri dan membatalkan/mengahpus nilai jika terjadi kesalahan.
- **Manajemen Juara Umum (`/admin/toggle-reveal-juara-umum`)**: Fitur toogle (hidup/mati) untuk membuka sensor nama 3 besar klasemen Juara Umum di halaman depan.
