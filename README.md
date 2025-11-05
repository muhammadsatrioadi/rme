# Sistem Rekam Medis Elektronik (RME)

Sistem Rekam Medis Elektronik yang sesuai dengan standar Kementerian Kesehatan Republik Indonesia berdasarkan Keputusan Menteri Kesehatan Nomor HK.01.07/MENKES/1423/2022 tentang Pedoman Variabel dan Meta Data pada Penyelenggaraan Rekam Medis Elektronik.

## Fitur Utama

### 1. Manajemen Pasien
- Pendaftaran pasien baru dengan data lengkap sesuai standar Menkes
- Pencarian dan filter pasien
- Riwayat medis pasien
- Export data pasien ke Excel

### 2. Rekam Medis Elektronik
- Input rekam medis dengan diagnosis ICD-10
- Pemeriksaan fisik dan tindakan medis
- Resep obat elektronik
- Status rekam medis (Draft, Selesai, Batal)

### 3. Manajemen Pegawai
- Data pegawai/dokter
- Spesialisasi dokter
- SIP dan STR dokter

### 4. Manajemen Poliklinik
- Data poliklinik
- Kepala poliklinik
- Statistik kunjungan per poliklinik

### 5. Manajemen Obat
- Master data obat
- Stok obat dan alert stok rendah
- Monitoring obat expired
- Kategori obat

### 6. Keamanan dan Audit Trail
- Enkripsi data sensitif
- Audit trail untuk semua perubahan data
- Session management yang aman
- CSRF protection
- Rate limiting
- Input sanitization

### 7. Laporan
- Laporan pasien
- Laporan rekam medis
- Laporan kunjungan
- Laporan diagnosis
- Laporan obat
- Audit trail
- Export ke Excel

## Persyaratan Sistem

### Server Requirements
- PHP 7.4 atau lebih baru
- MySQL 5.7 atau lebih baru
- Apache 2.4 atau Nginx
- OpenSSL extension
- PDO extension
- MBstring extension
- GD extension

### Browser Support
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/your-repo/sistem-rme.git
cd sistem-rme
```

### 2. Konfigurasi Database
1. Buat database MySQL baru
2. Import file `database_schema.sql`
3. Konfigurasi koneksi database di `application/config/database.php`

### 3. Konfigurasi Aplikasi
1. Salin `application/config/config.php.example` ke `application/config/config.php`
2. Set `base_url` sesuai dengan domain Anda
3. Set `encryption_key` dengan key yang aman

### 4. Set Permissions
```bash
chmod 755 application/
chmod 755 assets/
chmod 777 application/logs/
chmod 777 uploads/
```

### 5. Konfigurasi Web Server

#### Apache (.htaccess)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

## Konfigurasi Keamanan

### 1. Enkripsi Data
Sistem menggunakan enkripsi untuk data sensitif seperti NIK, nomor telepon, dan email.

### 2. Session Security
- Session timeout: 2 jam
- Session regeneration
- IP binding
- Secure cookies

### 3. CSRF Protection
- CSRF token pada semua form
- Token regeneration
- Exclude URIs untuk API

### 4. Rate Limiting
- 1000 requests per jam per IP
- Login attempt limiting
- API rate limiting

## Struktur Database

### Tabel Utama
- `mst_pasien` - Data pasien
- `mst_pegawai` - Data pegawai/dokter
- `mst_poliklinik` - Data poliklinik
- `mst_obat` - Master data obat
- `rekam_medis` - Rekam medis elektronik
- `rawat_inap` - Data rawat inap
- `resep_obat` - Resep obat
- `users` - User management
- `audit_trail` - Audit trail

### Tabel Keamanan
- `security_logs` - Log keamanan
- `login_attempts` - Log percobaan login
- `user_activity_logs` - Log aktivitas user
- `rate_limits` - Rate limiting

## Penggunaan

### 1. Login
- Akses aplikasi melalui browser
- Login dengan username dan password
- Sistem akan redirect ke dashboard

### 2. Manajemen Pasien
- Tambah pasien baru
- Edit data pasien
- Lihat riwayat medis
- Export data pasien

### 3. Rekam Medis
- Buat rekam medis baru
- Input diagnosis dengan kode ICD-10
- Tindakan medis
- Resep obat
- Update status rekam medis

### 4. Laporan
- Pilih jenis laporan
- Set filter tanggal dan parameter
- Lihat laporan
- Export ke Excel

## Standar Menkes

Sistem ini mengikuti standar Kementerian Kesehatan Republik Indonesia:

### 1. Variabel Data Pasien
- NIK (16 digit)
- No RM (format tahun + 6 digit)
- Data demografi lengkap
- Alamat KTP dan domisili
- Data medis (golongan darah, alergi, penyakit kronis)

### 2. Rekam Medis
- Keluhan utama
- Riwayat penyakit
- Pemeriksaan fisik
- Diagnosis dengan kode ICD-10
- Tindakan medis
- Resep obat
- Instruksi khusus
- Rencana tindak lanjut

### 3. Audit Trail
- Log semua perubahan data
- User yang melakukan perubahan
- Timestamp
- IP address
- User agent

### 4. Keamanan Data
- Enkripsi data sensitif
- Access control
- Session management
- Input validation
- SQL injection prevention

## Troubleshooting

### 1. Error 500
- Periksa log error di `application/logs/`
- Pastikan permissions folder benar
- Periksa konfigurasi database

### 2. Session Error
- Periksa konfigurasi session
- Pastikan folder session writable
- Periksa cookie settings

### 3. Database Error
- Periksa koneksi database
- Pastikan database schema sudah diimport
- Periksa credentials database

### 4. Upload Error
- Pastikan folder uploads writable
- Periksa PHP upload settings
- Periksa file size limits

## Kontribusi

1. Fork repository
2. Buat feature branch
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Sistem ini menggunakan lisensi MIT. Lihat file LICENSE untuk detail.

## Support

Untuk pertanyaan dan support, silakan hubungi:
- Email: support@rme-system.com
- Phone: +62-xxx-xxx-xxxx
- Website: https://rme-system.com

## Changelog

### Version 1.0.0
- Initial release
- Fitur dasar RME
- Keamanan dan audit trail
- Laporan sesuai standar Menkes

## Roadmap

### Version 1.1.0
- Integrasi dengan SATUSEHAT
- Mobile responsive
- API untuk integrasi
- Backup otomatis

### Version 1.2.0
- Telemedicine
- Appointment system
- Billing system
- Advanced reporting

## Disclaimer

Sistem ini dikembangkan untuk memenuhi standar Kementerian Kesehatan Republik Indonesia. Pengguna bertanggung jawab untuk memastikan compliance dengan regulasi yang berlaku.


PS D:\xampp\htdocs\Aplikasi_Magang_2> cmd /c install_db.bat
============================================
Setup Database RME System
============================================

[1/3] Creating database rme_system...
SUCCESS: Database created!

============================================

Login credentials:
  Username: admin
  Password: admin123

Access: http://localhost:8000/

