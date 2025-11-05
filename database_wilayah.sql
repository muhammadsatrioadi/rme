-- Database Schema untuk Data Wilayah Indonesia
-- Tabel Provinsi
CREATE TABLE IF NOT EXISTS `mst_provinsi` (
  `id_provinsi` varchar(2) NOT NULL PRIMARY KEY,
  `nama_provinsi` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Kabupaten/Kota
CREATE TABLE IF NOT EXISTS `mst_kabupaten` (
  `id_kabupaten` varchar(4) NOT NULL PRIMARY KEY,
  `id_provinsi` varchar(2) NOT NULL,
  `nama_kabupaten` varchar(100) NOT NULL,
  `jenis` enum('Kabupaten','Kota') DEFAULT 'Kabupaten',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_provinsi`) REFERENCES `mst_provinsi`(`id_provinsi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Kecamatan
CREATE TABLE IF NOT EXISTS `mst_kecamatan` (
  `id_kecamatan` varchar(7) NOT NULL PRIMARY KEY,
  `id_kabupaten` varchar(4) NOT NULL,
  `nama_kecamatan` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_kabupaten`) REFERENCES `mst_kabupaten`(`id_kabupaten`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Kelurahan/Desa
CREATE TABLE IF NOT EXISTS `mst_kelurahan` (
  `id_kelurahan` varchar(10) NOT NULL PRIMARY KEY,
  `id_kecamatan` varchar(7) NOT NULL,
  `nama_kelurahan` varchar(100) NOT NULL,
  `kode_pos` varchar(5) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_kecamatan`) REFERENCES `mst_kecamatan`(`id_kecamatan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Index untuk optimasi query
CREATE INDEX idx_kabupaten_provinsi ON mst_kabupaten(id_provinsi);
CREATE INDEX idx_kecamatan_kabupaten ON mst_kecamatan(id_kabupaten);
CREATE INDEX idx_kelurahan_kecamatan ON mst_kelurahan(id_kecamatan);
CREATE INDEX idx_kelurahan_kodepos ON mst_kelurahan(kode_pos);

-- Data Provinsi (34 Provinsi di Indonesia)
INSERT INTO `mst_provinsi` (`id_provinsi`, `nama_provinsi`) VALUES
('11', 'Aceh'),
('12', 'Sumatera Utara'),
('13', 'Sumatera Barat'),
('14', 'Riau'),
('15', 'Jambi'),
('16', 'Sumatera Selatan'),
('17', 'Bengkulu'),
('18', 'Lampung'),
('19', 'Kepulauan Bangka Belitung'),
('21', 'Kepulauan Riau'),
('31', 'DKI Jakarta'),
('32', 'Jawa Barat'),
('33', 'Jawa Tengah'),
('34', 'DI Yogyakarta'),
('35', 'Jawa Timur'),
('36', 'Banten'),
('51', 'Bali'),
('52', 'Nusa Tenggara Barat'),
('53', 'Nusa Tenggara Timur'),
('61', 'Kalimantan Barat'),
('62', 'Kalimantan Tengah'),
('63', 'Kalimantan Selatan'),
('64', 'Kalimantan Timur'),
('65', 'Kalimantan Utara'),
('71', 'Sulawesi Utara'),
('72', 'Sulawesi Tengah'),
('73', 'Sulawesi Selatan'),
('74', 'Sulawesi Tenggara'),
('75', 'Gorontalo'),
('76', 'Sulawesi Barat'),
('81', 'Maluku'),
('82', 'Maluku Utara'),
('91', 'Papua Barat'),
('94', 'Papua');

-- Data Kabupaten/Kota untuk DKI Jakarta (contoh)
INSERT INTO `mst_kabupaten` (`id_kabupaten`, `id_provinsi`, `nama_kabupaten`, `jenis`) VALUES
('3171', '31', 'Jakarta Selatan', 'Kota'),
('3172', '31', 'Jakarta Timur', 'Kota'),
('3173', '31', 'Jakarta Pusat', 'Kota'),
('3174', '31', 'Jakarta Barat', 'Kota'),
('3175', '31', 'Jakarta Utara', 'Kota');

-- Data Kecamatan untuk Jakarta Selatan (contoh)
INSERT INTO `mst_kecamatan` (`id_kecamatan`, `id_kabupaten`, `nama_kecamatan`) VALUES
('317101', '3171', 'Kebayoran Baru'),
('317102', '3171', 'Kebayoran Lama'),
('317103', '3171', 'Pesanggrahan'),
('317104', '3171', 'Cilandak'),
('317105', '3171', 'Pasar Minggu'),
('317106', '3171', 'Jagakarsa'),
('317107', '3171', 'Mampang Prapatan'),
('317108', '3171', 'Pancoran'),
('317109', '3171', 'Tebet'),
('317110', '3171', 'Setiabudi');

-- Data Kelurahan untuk Kebayoran Baru (contoh)
INSERT INTO `mst_kelurahan` (`id_kelurahan`, `id_kecamatan`, `nama_kelurahan`, `kode_pos`) VALUES
('31710101', '317101', 'Senayan', '12190'),
('31710102', '317101', 'Gunung', '12120'),
('31710103', '317101', 'Kramat Pela', '12130'),
('31710104', '317101', 'Gandaria Utara', '12140'),
('31710105', '317101', 'Cipete Utara', '12150'),
('31710106', '317101', 'Pulo', '12160'),
('31710107', '317101', 'Petogogan', '12170'),
('31710108', '317101', 'Rawa Barat', '12180'),
('31710109', '317101', 'Selong', '12190'),
('31710110', '317101', 'Cipete Selatan', '12110');

-- Note: Untuk data lengkap seluruh Indonesia, Anda perlu menggunakan data dari:
-- - https://github.com/cahyadsn/wilayah (REST API)
-- - https://github.com/edwardsamuel/Wilayah-Administratif-Indonesia (CSV/SQL)
-- - https://www.nomor.net/_kodepos.php (Kode Pos)
-- Data di atas hanya contoh untuk DKI Jakarta saja

