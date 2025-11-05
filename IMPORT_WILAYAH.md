# Panduan Import Data Wilayah Indonesia

## 1. Import Schema Database

Import file `database_wilayah.sql` ke database `rme_system`:

```bash
mysql -u root -p rme_system < database_wilayah.sql
```

Atau melalui phpMyAdmin:
1. Buka phpMyAdmin
2. Pilih database `rme_system`
3. Klik tab "Import"
4. Pilih file `database_wilayah.sql`
5. Klik "Go"

## 2. Data Wilayah Lengkap Indonesia

File `database_wilayah.sql` hanya berisi contoh data untuk DKI Jakarta. Untuk mendapatkan data lengkap seluruh Indonesia (34 Provinsi, 514 Kabupaten/Kota, 7.000+ Kecamatan, 80.000+ Kelurahan/Desa), Anda bisa menggunakan:

### Opsi 1: Menggunakan Data dari GitHub (Recommended)

1. **Data dari edwardsamuel/Wilayah-Administratif-Indonesia:**
   - URL: https://github.com/edwardsamuel/Wilayah-Administratif-Indonesia
   - Download file SQL lengkap
   - Import ke database `rme_system`

2. **Data dari cahyadsn/wilayah:**
   - URL: https://github.com/cahyadsn/wilayah
   - Tersedia dalam format CSV dan SQL

### Opsi 2: Menggunakan API Eksternal (Sementara)

Jika data lengkap belum tersedia, Anda bisa menggunakan API eksternal seperti:
- https://dev.farizdotid.com/api/daerahindonesia/provinsi
- https://api.binderbyte.com/v1/wilayah (memerlukan API key)

### Opsi 3: Import Manual dari BPS

Data resmi dari Badan Pusat Statistik (BPS) Indonesia:
- https://www.bps.go.id/id/statistics-table/2/indonesia.html

## 3. Struktur Tabel

Tabel yang dibuat:
- `mst_provinsi` - Data 34 Provinsi
- `mst_kabupaten` - Data 514 Kabupaten/Kota
- `mst_kecamatan` - Data 7.000+ Kecamatan
- `mst_kelurahan` - Data 80.000+ Kelurahan/Desa (dengan kode pos)

## 4. Testing API

Setelah import data, test endpoint API:

```bash
# Test Provinsi
curl https://rme.yhotech.id/api/wilayah/provinsi

# Test Kabupaten (contoh: DKI Jakarta = 31)
curl https://rme.yhotech.id/api/wilayah/kabupaten?provinsi=31

# Test Kecamatan (contoh: Jakarta Selatan = 3171)
curl https://rme.yhotech.id/api/wilayah/kecamatan?kabupaten=3171

# Test Kelurahan (contoh: Kebayoran Baru = 317101)
curl https://rme.yhotech.id/api/wilayah/kelurahan?kecamatan=317101

# Test Kode Pos
curl https://rme.yhotech.id/api/wilayah/kodepos?kelurahan=31710101
```

## 5. Catatan Penting

- Pastikan foreign key constraints sudah aktif
- Data lengkap Indonesia bisa berukuran 50-100 MB
- Index sudah dibuat untuk optimasi query
- Format ID menggunakan kode wilayah resmi (BPS)

## 6. Troubleshooting

Jika dropdown tidak muncul:
1. Buka browser console (F12)
2. Cek error di tab Console
3. Pastikan API endpoint bisa diakses
4. Pastikan database sudah terisi data
5. Pastikan `base_url` di `config.php` sudah benar

