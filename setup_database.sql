-- Setup Database untuk Aplikasi RME pada XAMPP
-- Jalankan script ini di phpMyAdmin atau MySQL command line

-- 1. Buat database
CREATE DATABASE IF NOT EXISTS rme_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 2. Gunakan database
USE rme_system;

-- 3. Import schema dari database_schema.sql (sudah ada di file database_schema.sql)
-- Untuk lebih mudah, import langsung file database_schema.sql di phpMyAdmin

-- 4. Buat user default untuk testing (opsional)
-- CREATE USER 'rme_user'@'localhost' IDENTIFIED BY '';
-- GRANT ALL PRIVILEGES ON rme_system.* TO 'rme_user'@'localhost';
-- FLUSH PRIVILEGES;

-- 5. Insert user default untuk login
-- Username: admin
-- Password: admin123
INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `id_pegawai`, `role`, `status_user`) VALUES 
('USR001', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@rme.local', NULL, 'Admin', 'Aktif')
ON DUPLICATE KEY UPDATE username=username;

-- Note: Default password untuk user 'admin' adalah 'admin123'
-- Password sudah di-hash menggunakan bcrypt

