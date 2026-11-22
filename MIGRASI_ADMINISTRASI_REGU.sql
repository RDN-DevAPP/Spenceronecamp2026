-- ==============================================================
-- SQL MIGRATION: SISTEM ADMINISTRASI REGU (6 BUKU PRAMUKA)
-- Spencerone Camp Season II
-- Jalankan script ini di phpMyAdmin pada database website Anda
-- ==============================================================

-- 1. Tambah Kolom NTA & Biodata Anggota Pramuka ke tabel anggota_regu
ALTER TABLE `anggota_regu` 
    ADD COLUMN IF NOT EXISTS `nta` VARCHAR(50) NULL AFTER `nisn`,
    ADD COLUMN IF NOT EXISTS `tempat_lahir` VARCHAR(100) NULL AFTER `kelas`,
    ADD COLUMN IF NOT EXISTS `tanggal_lahir` DATE NULL AFTER `tempat_lahir`,
    ADD COLUMN IF NOT EXISTS `agama` VARCHAR(50) NULL AFTER `tanggal_lahir`,
    ADD COLUMN IF NOT EXISTS `alamat` TEXT NULL AFTER `agama`,
    ADD COLUMN IF NOT EXISTS `tkk` TEXT NULL AFTER `tingkatan_tku`;

-- Tambah Kolom NTA ke tabel siswas (jika belum ada)
ALTER TABLE `siswas`
    ADD COLUMN IF NOT EXISTS `nta` VARCHAR(50) NULL AFTER `nisn`;

-- 2. Buat Tabel Buku Keuangan Kas Regu (regu_finances)
CREATE TABLE IF NOT EXISTS `regu_finances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `regu_profile_id` BIGINT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `uraian` VARCHAR(255) NOT NULL,
    `jenis` ENUM('masuk', 'keluar') NOT NULL,
    `nominal` DECIMAL(15, 2) NOT NULL DEFAULT '0.00',
    `kategori` VARCHAR(255) NULL,
    `bukti_path` VARCHAR(255) NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_regu_finances_profile` FOREIGN KEY (`regu_profile_id`) REFERENCES `regu_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Buat Tabel Buku Catatan Kegiatan / Logbook Regu (regu_logbooks)
CREATE TABLE IF NOT EXISTS `regu_logbooks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `regu_profile_id` BIGINT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `waktu_mulai` VARCHAR(20) NULL,
    `waktu_selesai` VARCHAR(20) NULL,
    `nama_kegiatan` VARCHAR(255) NOT NULL,
    `tempat` VARCHAR(255) NULL,
    `pemimpin_kegiatan` VARCHAR(255) NULL,
    `ringkasan_materi` TEXT NULL,
    `hasil_kegiatan` TEXT NULL,
    `foto_kegiatan_path` VARCHAR(255) NULL,
    `catatan_evaluasi` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_regu_logbooks_profile` FOREIGN KEY (`regu_profile_id`) REFERENCES `regu_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Buat Tabel Buku Notulen Rapat Regu (regu_meeting_notes)
CREATE TABLE IF NOT EXISTS `regu_meeting_notes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `regu_profile_id` BIGINT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `waktu` VARCHAR(30) NULL,
    `tempat` VARCHAR(255) NULL,
    `agenda` VARCHAR(255) NOT NULL,
    `pemimpin_rapat` VARCHAR(255) NULL,
    `notulis` VARCHAR(255) NULL,
    `jumlah_hadir` INT NOT NULL DEFAULT '0',
    `daftar_hadir_teks` TEXT NULL,
    `jalannya_rapat` TEXT NULL,
    `keputusan` TEXT NOT NULL,
    `catatan_tambahan` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_regu_meeting_notes_profile` FOREIGN KEY (`regu_profile_id`) REFERENCES `regu_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Buat Tabel Buku Presensi / Daftar Hadir Regu (regu_attendances & regu_attendance_details)
CREATE TABLE IF NOT EXISTS `regu_attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `regu_profile_id` BIGINT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `nama_kegiatan` VARCHAR(255) NOT NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_regu_attendances_profile` FOREIGN KEY (`regu_profile_id`) REFERENCES `regu_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `regu_attendance_details` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `regu_attendance_id` BIGINT UNSIGNED NOT NULL,
    `anggota_regu_id` BIGINT UNSIGNED NOT NULL,
    `status` ENUM('hadir', 'izin', 'sakit', 'alpa') NOT NULL DEFAULT 'hadir',
    `keterangan` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_regu_att_details_master` FOREIGN KEY (`regu_attendance_id`) REFERENCES `regu_attendances` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_regu_att_details_anggota` FOREIGN KEY (`anggota_regu_id`) REFERENCES `anggota_regu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Buat Tabel Buku Inventaris Perlengkapan Regu (regu_inventories)
CREATE TABLE IF NOT EXISTS `regu_inventories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `regu_profile_id` BIGINT UNSIGNED NOT NULL,
    `kode_barang` VARCHAR(50) NULL,
    `nama_barang` VARCHAR(255) NOT NULL,
    `jumlah` INT NOT NULL DEFAULT '1',
    `satuan` VARCHAR(50) NOT NULL DEFAULT 'Buah',
    `kondisi` ENUM('baik', 'rusak_ringan', 'rusak_berat') NOT NULL DEFAULT 'baik',
    `asal_barang` VARCHAR(255) NULL,
    `tanggal_perolehan` DATE NULL,
    `lokasi_penyimpanan` VARCHAR(255) NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_regu_inventories_profile` FOREIGN KEY (`regu_profile_id`) REFERENCES `regu_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Pengaturan Resmi Format Nomor & Pejabat Sertifikat
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('cert_number_format_peserta', '{NOMOR}/SC-II/SERTIFIKAT/{BULAN}/{TAHUN}', NOW(), NOW()),
('cert_number_format_juara', '{NOMOR}/SC-II/PIAGAM-JUARA/{BULAN}/{TAHUN}', NOW(), NOW()),
('cert_counter_peserta', '1', NOW(), NOW()),
('cert_counter_juara', '1', NOW(), NOW()),
('cert_signer_name', 'H. AKHMAD BAHRAN, M.Pd.', NOW(), NOW()),
('cert_signer_title', 'Ka Mabigus SMPN 1 Cerbon', NOW(), NOW()),
('cert_signer_nip', 'NIP. 19790705 200604 2 031', NOW(), NOW()),
('cert_signer_sub_name', 'M. RAFLI AL-FARIZI', NOW(), NOW()),
('cert_signer_sub_title', 'Ketua Pelaksana Season II', NOW(), NOW()),
('cert_signer_sub_nip', 'NIP. 19940412 202221 2 003', NOW(), NOW()),
('cert_location_date', 'Cerbon, 22 October 2026', NOW(), NOW())
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = NOW();

-- 8. Tabel Sertifikat Peserta & Token Verifikasi QR Online
CREATE TABLE IF NOT EXISTS `sertifikat_peserta` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `anggota_regu_id` BIGINT UNSIGNED NOT NULL,
    `nomor_sertifikat` VARCHAR(150) NOT NULL,
    `nomor_urut` INT UNSIGNED NOT NULL DEFAULT '1',
    `verify_token` VARCHAR(64) NOT NULL UNIQUE,
    `diterbitkan_pada` DATE NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    CONSTRAINT `fk_sertifikat_peserta_anggota` FOREIGN KEY (`anggota_regu_id`) REFERENCES `anggota_regu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- LEMBAR PENILAIAN PBB: kolom kelompok & rubrik pada scoring_criteria
-- (Jalankan sekali. Jika kolom sudah ada, abaikan error 'Duplicate column')
-- ============================================================
ALTER TABLE `scoring_criteria` ADD COLUMN `kelompok` VARCHAR(255) NULL AFTER `nama_kriteria`;
ALTER TABLE `scoring_criteria` ADD COLUMN `rubrik` JSON NULL AFTER `kelompok`;

