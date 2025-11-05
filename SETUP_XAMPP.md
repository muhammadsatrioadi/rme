# Panduan Setup Aplikasi RME di XAMPP (Windows)

## Prerequisites
- XAMPP sudah terinstall dan berjalan
- Apache dan MySQL services berjalan

## Langkah-langkah Setup

### 1. Pastikan XAMPP Services Berjalan

Buka **XAMPP Control Panel** dan pastikan:
- ✅ **Apache** berjalan (status: Running)
- ✅ **MySQL** berjalan (status: Running)

Jika belum berjalan, klik tombol **Start** untuk Apache dan MySQL.

### 2. Buat Database

Ada 2 cara untuk membuat database:

#### Cara 1: Menggunakan phpMyAdmin (Disarankan)

1. Buka browser dan akses: `http://localhost/phpmyadmin`
2. Klik **New** untuk membuat database baru
3. Masukkan nama database: `rme_system`
4. Pilih **Collation**: `utf8mb4_unicode_ci`
5. Klik **Create**

#### Cara 2: Menggunakan Command Line

1. Buka Command Prompt
2. Masuk ke folder MySQL di XAMPP:
   ```bash
   cd C:\xampp\mysql\bin
   ```
3. Login ke MySQL:
   ```bash
   mysql -u root
   ```
4. Jalankan command untuk buat database:
   ```sql
   CREATE DATABASE rme_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   ```

### 3. Import Database Schema

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pilih database **rme_system** di sidebar kiri
3. Klik tab **Import**
4. Klik tombol **Choose File**
5. Pilih file `database_schema.sql` dari folder project
6. Klik **Go** untuk import
7. Tunggu sampai import selesai

### 4. Import Data Default

Setelah schema berhasil diimport:

1. Masih di phpMyAdmin, pilih database **rme_system**
2. Klik tab **SQL**
3. Copy dan paste isi file `setup_database.sql`
4. Klik **Go**

Ini akan membuat user default:
- **Username**: admin
- **Password**: admin123

### 5. Verifikasi Konfigurasi

1. Buka `application/config/database.php`
2. Pastikan konfigurasi sudah benar:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => '',
   'database' => 'rme_system',
   ```

3. Buka `application/config/config.php`
4. Pastikan:
   ```php
   $config['base_url'] = 'http://localhost:8000/';
   $config['index_page'] = '';
   $config['encryption_key'] = 'd41d8cd98f00b204e9800998ecf8427e';
   ```

### 6. Set Permissions untuk Folder

Folder berikut harus writable:
- `application/cache/`
- `application/cache/sessions/`
- `application/logs/`

**Untuk Windows**, biasanya tidak perlu set permissions, tapi pastikan folder-folder tersebut tidak diproteksi oleh antivirus.

### 7. Test Aplikasi

1. Pastikan Apache dan MySQL masih berjalan
2. Buka browser
3. Akses: `http://localhost:8000/`
4. Seharusnya muncul halaman login
5. Login dengan:
   - **Username**: admin
   - **Password**: admin123

## Troubleshooting

### Error: "Access denied for user 'root'@'localhost'"

**Solusi:**
1. Buka XAMPP Control Panel
2. Stop MySQL
3. Start MySQL
4. Atau restart komputer

### Error: "Database 'rme_system' doesn't exist"

**Solusi:**
1. Pastikan sudah membuat database `rme_system`
2. Import ulang `database_schema.sql`

### Error: "Call to undefined function mysqli_connect()"

**Solusi:**
1. Buka `php.ini` di XAMPP
2. Cari `;extension=mysqli`
3. Hilangkan tanda `;` menjadi `extension=mysqli`
4. Restart Apache

### Error: "The page you requested was not found"

**Solusi:**
1. Pastikan mod_rewrite sudah enable di Apache
2. Cek file `.htaccess` sudah ada di root directory
3. Restart Apache

### Port 8000 already in use

**Solusi:**
1. Ganti port di `application/config/config.php`:
   ```php
   $config['base_url'] = 'http://localhost/';
   ```
2. Atau hentikan aplikasi yang menggunakan port 8000

### Cannot write to cache or logs directory

**Solusi untuk Windows:**
1. Klik kanan pada folder `application/cache` dan `application/logs`
2. Pilih **Properties**
3. Tab **Security**
4. Klik **Edit**
5. Pilih user yang menjalankan Apache (biasanya Users atau Everyone)
6. Berikan **Full control**
7. Klik **OK**

## Default Credentials

### Admin User
- **Username**: admin
- **Password**: admin123

**PENTING**: Setelah login pertama kali, segera ganti password!

### Database
- **Host**: localhost
- **Database**: rme_system
- **Username**: root
- **Password**: (kosong)

## Next Steps

Setelah aplikasi berjalan:

1. ✅ Login dengan user default
2. ✅ Ganti password admin
3. ✅ Setup data master (Pasien, Pegawai, Poliklinik, dll)
4. ✅ Mulai input data

## Support

Jika mengalami masalah:
1. Cek error log di `application/logs/`
2. Cek Apache error log di XAMPP
3. Pastikan semua requirements terpenuhi

