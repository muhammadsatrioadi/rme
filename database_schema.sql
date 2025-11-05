-- Database Schema untuk Aplikasi Rumah Sakit RME
-- Sesuai dengan Keputusan Menteri Kesehatan Nomor HK.01.07/MENKES/1423/2022

-- Tabel Master Data
CREATE TABLE IF NOT EXISTS `mst_pasien` (
  `id_pasien` varchar(20) NOT NULL PRIMARY KEY,
  `no_rm` varchar(20) NOT NULL UNIQUE,
  `nik` varchar(16) NOT NULL UNIQUE,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_panggilan` varchar(50),
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(20),
  `suku_bangsa` varchar(30),
  `status_perkawinan` enum('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') NOT NULL,
  `pendidikan` varchar(30),
  `pekerjaan` varchar(50),
  `alamat_ktp` text NOT NULL,
  `rt_ktp` varchar(3),
  `rw_ktp` varchar(3),
  `kelurahan_ktp` varchar(50),
  `kecamatan_ktp` varchar(50),
  `kota_ktp` varchar(50),
  `provinsi_ktp` varchar(50),
  `kode_pos_ktp` varchar(5),
  `alamat_domisili` text,
  `rt_domisili` varchar(3),
  `rw_domisili` varchar(3),
  `kelurahan_domisili` varchar(50),
  `kecamatan_domisili` varchar(50),
  `kota_domisili` varchar(50),
  `provinsi_domisili` varchar(50),
  `kode_pos_domisili` varchar(5),
  `no_telepon` varchar(15),
  `no_hp` varchar(15),
  `email` varchar(100),
  `nama_ayah` varchar(100),
  `nama_ibu` varchar(100),
  `nama_suami_istri` varchar(100),
  `golongan_darah` enum('A','B','AB','O') NOT NULL,
  `rhesus` enum('+','-') NOT NULL,
  `alergi` text,
  `penyakit_kronis` text,
  `asuransi` varchar(50),
  `no_asuransi` varchar(50),
  `status_pasien` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20)
);

-- Tabel Master Pegawai/Dokter
CREATE TABLE IF NOT EXISTS `mst_pegawai` (
  `id_pegawai` varchar(20) NOT NULL PRIMARY KEY,
  `nik` varchar(16) NOT NULL UNIQUE,
  `nama_lengkap` varchar(100) NOT NULL,
  `nama_panggilan` varchar(50),
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `agama` varchar(20),
  `alamat` text NOT NULL,
  `no_telepon` varchar(15),
  `no_hp` varchar(15),
  `email` varchar(100),
  `jabatan` varchar(50) NOT NULL,
  `spesialisasi` varchar(50),
  `no_sip` varchar(20),
  `no_str` varchar(20),
  `status_pegawai` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20)
);

-- Tabel Master Poliklinik
CREATE TABLE IF NOT EXISTS `mst_poliklinik` (
  `id_poliklinik` varchar(10) NOT NULL PRIMARY KEY,
  `nama_poliklinik` varchar(50) NOT NULL,
  `kode_poliklinik` varchar(10) NOT NULL UNIQUE,
  `kepala_poliklinik` varchar(20),
  `status_poliklinik` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20),
  FOREIGN KEY (`kepala_poliklinik`) REFERENCES `mst_pegawai`(`id_pegawai`)
);

-- Tabel Master Kamar
CREATE TABLE IF NOT EXISTS `mst_kamar` (
  `id_kamar` varchar(10) NOT NULL PRIMARY KEY,
  `no_kamar` varchar(10) NOT NULL,
  `nama_kamar` varchar(50) NOT NULL,
  `kelas_kamar` enum('VIP','Kelas 1','Kelas 2','Kelas 3','ICU','NICU','PICU') NOT NULL,
  `kapasitas` int NOT NULL,
  `status_kamar` enum('Tersedia','Terisi','Maintenance') DEFAULT 'Tersedia',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20)
);

-- Tabel Master Obat
CREATE TABLE IF NOT EXISTS `mst_obat` (
  `id_obat` varchar(20) NOT NULL PRIMARY KEY,
  `kode_obat` varchar(20) NOT NULL UNIQUE,
  `nama_obat` varchar(100) NOT NULL,
  `nama_generik` varchar(100),
  `kategori_obat` varchar(50) NOT NULL,
  `satuan` varchar(20) NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `harga_jual` decimal(15,2) NOT NULL,
  `stok_minimal` int DEFAULT 0,
  `stok_aktual` int DEFAULT 0,
  `expired_date` date,
  `status_obat` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20)
);

-- Tabel Rekam Medis
CREATE TABLE IF NOT EXISTS `rekam_medis` (
  `id_rm` varchar(30) NOT NULL PRIMARY KEY,
  `id_pasien` varchar(20) NOT NULL,
  `no_rm` varchar(20) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `jam_kunjungan` time NOT NULL,
  `jenis_kunjungan` enum('Baru','Lama','Rujukan','Emergency') NOT NULL,
  `id_poliklinik` varchar(10) NOT NULL,
  `id_dokter` varchar(20) NOT NULL,
  `keluhan_utama` text NOT NULL,
  `riwayat_penyakit` text,
  `riwayat_alergi` text,
  `pemeriksaan_fisik` text,
  `diagnosis_utama` text NOT NULL,
  `diagnosis_tambahan` text,
  `kode_icd10_utama` varchar(10),
  `kode_icd10_tambahan` varchar(10),
  `tindakan` text,
  `resep_obat` text,
  `instruksi_khusus` text,
  `rencana_tindak_lanjut` text,
  `status_rm` enum('Draft','Selesai','Batal') DEFAULT 'Draft',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20),
  FOREIGN KEY (`id_pasien`) REFERENCES `mst_pasien`(`id_pasien`),
  FOREIGN KEY (`id_poliklinik`) REFERENCES `mst_poliklinik`(`id_poliklinik`),
  FOREIGN KEY (`id_dokter`) REFERENCES `mst_pegawai`(`id_pegawai`)
);

-- Tabel Rawat Inap
CREATE TABLE IF NOT EXISTS `rawat_inap` (
  `id_rawat_inap` varchar(30) NOT NULL PRIMARY KEY,
  `id_pasien` varchar(20) NOT NULL,
  `no_rm` varchar(20) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `tanggal_keluar` date,
  `jam_keluar` time,
  `id_kamar` varchar(10) NOT NULL,
  `id_dokter` varchar(20) NOT NULL,
  `diagnosis_masuk` text NOT NULL,
  `diagnosis_keluar` text,
  `tindakan_medis` text,
  `status_pulang` enum('Sembuh','Membaik','Belum Sembuh','Meninggal','Pindah','Lari') DEFAULT 'Belum Sembuh',
  `status_rawat` enum('Aktif','Selesai','Batal') DEFAULT 'Aktif',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20),
  FOREIGN KEY (`id_pasien`) REFERENCES `mst_pasien`(`id_pasien`),
  FOREIGN KEY (`id_kamar`) REFERENCES `mst_kamar`(`id_kamar`),
  FOREIGN KEY (`id_dokter`) REFERENCES `mst_pegawai`(`id_pegawai`)
);

-- Tabel Resep Obat
CREATE TABLE IF NOT EXISTS `resep_obat` (
  `id_resep` varchar(30) NOT NULL PRIMARY KEY,
  `id_rm` varchar(30) NOT NULL,
  `id_pasien` varchar(20) NOT NULL,
  `tanggal_resep` date NOT NULL,
  `id_dokter` varchar(20) NOT NULL,
  `total_harga` decimal(15,2) DEFAULT 0,
  `status_resep` enum('Draft','Selesai','Batal') DEFAULT 'Draft',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20),
  FOREIGN KEY (`id_rm`) REFERENCES `rekam_medis`(`id_rm`),
  FOREIGN KEY (`id_pasien`) REFERENCES `mst_pasien`(`id_pasien`),
  FOREIGN KEY (`id_dokter`) REFERENCES `mst_pegawai`(`id_pegawai`)
);

-- Tabel Detail Resep
CREATE TABLE IF NOT EXISTS `detail_resep` (
  `id_detail_resep` varchar(30) NOT NULL PRIMARY KEY,
  `id_resep` varchar(30) NOT NULL,
  `id_obat` varchar(20) NOT NULL,
  `jumlah` int NOT NULL,
  `dosis` varchar(50) NOT NULL,
  `aturan_pakai` varchar(100) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_resep`) REFERENCES `resep_obat`(`id_resep`),
  FOREIGN KEY (`id_obat`) REFERENCES `mst_obat`(`id_obat`)
);

-- Tabel Audit Trail
CREATE TABLE IF NOT EXISTS `audit_trail` (
  `id_audit` bigint AUTO_INCREMENT PRIMARY KEY,
  `table_name` varchar(50) NOT NULL,
  `record_id` varchar(30) NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `old_values` longtext,
  `new_values` longtext,
  `user_id` varchar(20) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
);

-- Tabel User Management
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` varchar(20) NOT NULL PRIMARY KEY,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `id_pegawai` varchar(20),
  `role` enum('Admin','Dokter','Perawat','Apoteker','Kasir','Manager') NOT NULL,
  `status_user` enum('Aktif','Non Aktif') DEFAULT 'Aktif',
  `last_login` timestamp NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` varchar(20),
  `updated_by` varchar(20),
  FOREIGN KEY (`id_pegawai`) REFERENCES `mst_pegawai`(`id_pegawai`)
);

-- Tabel Session
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
);

-- Index untuk optimasi query
CREATE INDEX idx_pasien_nik ON mst_pasien(nik);
CREATE INDEX idx_pasien_no_rm ON mst_pasien(no_rm);
CREATE INDEX idx_rm_pasien ON rekam_medis(id_pasien);
CREATE INDEX idx_rm_tanggal ON rekam_medis(tanggal_kunjungan);
CREATE INDEX idx_rm_dokter ON rekam_medis(id_dokter);
CREATE INDEX idx_audit_table ON audit_trail(table_name);
CREATE INDEX idx_audit_record ON audit_trail(record_id);
CREATE INDEX idx_audit_user ON audit_trail(user_id);

