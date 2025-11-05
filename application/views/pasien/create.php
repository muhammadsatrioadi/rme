<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<style>
    /* Warna ungu header, sesuaikan dengan warna header utama jika berbeda */
    .card-header.custom-purple {
        background-color: #6f42c1 !important; /* Bootstrap purple, ganti jika header utama berbeda */
        color: #fff !important;
        border-radius: 0.25rem 0.25rem 0 0;
    }
    .card-header.custom-purple h4,
    .card-header.custom-purple i {
        color: #fff !important;
    }
</style>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Pendaftaran Pasien Baru</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Pendaftaran</div>
                <div class="breadcrumb-item">Tambah Pasien</div>
            </div>
        </div>

        <div class="section-body">
            <form id="formPendaftaran" method="POST" action="<?= base_url('pendaftaran/simpan'); ?>" enctype="multipart/form-data">
                <!-- Data Identitas Umum Pasien -->
                <div class="card">
                    <div class="card-header custom-purple">
                        <h4><i class="fas fa-user"></i> Identitas Umum Pasien</h4>
                    </div>
                    <div class="card-body">
                        <!-- ... (tidak diubah, isi form tetap) ... -->
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="no_rekam_medis">Nomor Rekam Medis <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="no_rekam_medis" id="no_rekam_medis" readonly value="<?= isset($no_rm) ? htmlspecialchars($no_rm) : '' ?>">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="no_ktp">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="no_ktp" id="no_ktp" maxlength="16" pattern="\d{16}" placeholder="16 digit NIK atau 9999999999999999" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="no_identitas_lain">Nomor Identitas Lain (Paspor/KITAS, WNA)</label>
                                <input type="text" class="form-control" name="no_identitas_lain" id="no_identitas_lain" placeholder="Nomor Paspor/KITAS">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="nama_ibu_kandung">Nama Ibu Kandung <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_ibu_kandung" id="nama_ibu_kandung" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="berkas_ktp">Upload KTP</label>
                                <input type="file" class="form-control-file" name="berkas_ktp" id="berkas_ktp" accept="image/*,application/pdf">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" required onchange="hitungUmur()">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="1">Laki-laki</option>
                                    <option value="2">Perempuan</option>
                                    <option value="0">Tidak diketahui</option>
                                    <option value="3">Tidak dapat ditentukan</option>
                                    <option value="4">Tidak mengisi</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="agama">Agama <span class="text-danger">*</span></label>
                                <select class="form-control" name="agama" id="agama" required>
                                    <option value="">Pilih Agama</option>
                                    <option value="1">Islam</option>
                                    <option value="2">Kristen (Protestan)</option>
                                    <option value="3">Katolik</option>
                                    <option value="4">Hindu</option>
                                    <option value="5">Buddha</option>
                                    <option value="6">Konghucu</option>
                                    <option value="7">Penghayat</option>
                                    <option value="8">Lain-lain</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="agama_lain" id="agama_lain" placeholder="Isi jika Lain-lain" style="display:none;">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="suku">Suku</label>
                                <input type="text" class="form-control" name="suku" id="suku">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="bahasa">Bahasa yang Dikuasai</label>
                                <input type="text" class="form-control" name="bahasa" id="bahasa">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="gelar_depan">Gelar Depan</label>
                                <input type="text" class="form-control" name="gelar_depan" id="gelar_depan">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="gelar_belakang">Gelar Belakang</label>
                                <input type="text" class="form-control" name="gelar_belakang" id="gelar_belakang">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat Sesuai Identitas -->
                <div class="card mt-4">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-map-marker-alt"></i> Alamat Sesuai Identitas</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="alamat_identitas">Alamat Lengkap</label>
                                <input type="text" class="form-control" name="alamat_identitas" id="alamat_identitas">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="rt_identitas">RT</label>
                                <input type="text" class="form-control" name="rt_identitas" id="rt_identitas" maxlength="3">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="rw_identitas">RW</label>
                                <input type="text" class="form-control" name="rw_identitas" id="rw_identitas" maxlength="3">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="kode_pos_identitas">Kode Pos</label>
                                <input type="text" class="form-control" name="kode_pos_identitas" id="kode_pos_identitas" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="kelurahan_identitas">Kelurahan/Desa</label>
                                <select class="form-control" name="kelurahan_identitas" id="kelurahan_identitas" required>
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="kecamatan_identitas">Kecamatan</label>
                                <select class="form-control" name="kecamatan_identitas" id="kecamatan_identitas" required>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="kabupaten_identitas">Kota/Kabupaten</label>
                                <select class="form-control" name="kabupaten_identitas" id="kabupaten_identitas" required>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="provinsi_identitas">Provinsi</label>
                                <select class="form-control" name="provinsi_identitas" id="provinsi_identitas" required>
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="negara_identitas">Negara</label>
                                <select class="form-control" name="negara_identitas" id="negara_identitas" required>
                                    <option value="ID" selected>Indonesia</option>
                                    <!-- Option negara lain dapat ditambahkan di sini jika perlu -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat Domisili -->
                <div class="card mt-4">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-map-marker-alt"></i> Alamat Domisili</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="alamat_domisili">Alamat Domisili</label>
                                <input type="text" class="form-control" name="alamat_domisili" id="alamat_domisili">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="rt_domisili">RT</label>
                                <input type="text" class="form-control" name="rt_domisili" id="rt_domisili" maxlength="3">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="rw_domisili">RW</label>
                                <input type="text" class="form-control" name="rw_domisili" id="rw_domisili" maxlength="3">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="kode_pos_domisili">Kode Pos</label>
                                <input type="text" class="form-control" name="kode_pos_domisili" id="kode_pos_domisili" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="kelurahan_domisili">Kelurahan/Desa</label>
                                <select class="form-control" name="kelurahan_domisili" id="kelurahan_domisili">
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="kecamatan_domisili">Kecamatan</label>
                                <select class="form-control" name="kecamatan_domisili" id="kecamatan_domisili">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="kabupaten_domisili">Kota/Kabupaten</label>
                                <select class="form-control" name="kabupaten_domisili" id="kabupaten_domisili">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="provinsi_domisili">Provinsi</label>
                                <select class="form-control" name="provinsi_domisili" id="provinsi_domisili">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="negara_domisili">Negara</label>
                                <select class="form-control" name="negara_domisili" id="negara_domisili">
                                    <option value="ID" selected>Indonesia</option>
                                    <!-- Option negara lain dapat ditambahkan di sini jika perlu -->
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="card mt-4">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-phone"></i> Kontak</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="telepon_rumah">Nomor Telepon Rumah/Tempat Tinggal</label>
                                <input type="text" class="form-control" name="telepon_rumah" id="telepon_rumah">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="telepon_seluler">Nomor Telepon Seluler Pasien</label>
                                <input type="text" class="form-control" name="telepon_seluler" id="telepon_seluler">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pendidikan, Pekerjaan, Status Pernikahan -->
                <div class="card mt-4">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-graduation-cap"></i> Pendidikan, Pekerjaan, Status Pernikahan</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="pendidikan">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                <select class="form-control" name="pendidikan" id="pendidikan" required>
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="0">Tidak Sekolah</option>
                                    <option value="1">SD</option>
                                    <option value="2">SLTP sederajat</option>
                                    <option value="3">SLTA sederajat</option>
                                    <option value="4">D1-D3 sederajat</option>
                                    <option value="5">D4</option>
                                    <option value="6">S1</option>
                                    <option value="7">S2</option>
                                    <option value="8">S3</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="pekerjaan">Pekerjaan <span class="text-danger">*</span></label>
                                <select class="form-control" name="pekerjaan" id="pekerjaan" required>
                                    <option value="">Pilih Pekerjaan</option>
                                    <option value="0">Tidak Bekerja</option>
                                    <option value="1">PNS</option>
                                    <option value="2">TNI/POLRI</option>
                                    <option value="3">BUMN</option>
                                    <option value="4">Pegawai Swasta/Wirausaha</option>
                                    <option value="5">Lain-lain</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="pekerjaan_lain" id="pekerjaan_lain" placeholder="Isi jika Lain-lain" style="display:none;">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="status_perkawinan">Status Pernikahan <span class="text-danger">*</span></label>
                                <select class="form-control" name="status_perkawinan" id="status_perkawinan" required>
                                    <option value="">Pilih Status</option>
                                    <option value="1">Belum Kawin</option>
                                    <option value="2">Kawin</option>
                                    <option value="3">Cerai Hidup</option>
                                    <option value="4">Cerai Mati</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Identitas Pasien Tidak Dikenal -->
                <div class="card mt-4" id="section_pasien_tidak_dikenal" style="display:none;">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-question-circle"></i> Identitas Pasien Tidak Dikenal</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="perkiraan_umur">Perkiraan Umur</label>
                                <select class="form-control" name="perkiraan_umur" id="perkiraan_umur">
                                    <option value="">Pilih Perkiraan Umur</option>
                                    <option value="1">0 - 5</option>
                                    <option value="2">6 - 11</option>
                                    <option value="3">12 - 17</option>
                                    <option value="4">18 - 40</option>
                                    <option value="5">41 - 65</option>
                                    <option value="6">&gt; 65</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="lokasi_ditemukan">Lokasi Ditemukan</label>
                                <input type="text" class="form-control" name="lokasi_ditemukan" id="lokasi_ditemukan">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="tanggal_ditemukan">Tanggal Ditemukan</label>
                                <input type="date" class="form-control" name="tanggal_ditemukan" id="tanggal_ditemukan">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Penanggung Jawab Pasien -->
                <div class="card mt-4">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-user"></i> Identitas Penanggung Jawab Pasien</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="nama_penanggung_jawab">Nama Penanggung Jawab Pasien</label>
                                <input type="text" class="form-control" name="nama_penanggung_jawab" id="nama_penanggung_jawab">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="telepon_penanggung_jawab">Nomor Telepon Seluler Penanggung Jawab</label>
                                <input type="text" class="form-control" name="telepon_penanggung_jawab" id="telepon_penanggung_jawab">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="hubungan_penanggung_jawab">Hubungan dengan Pasien</label>
                                <select class="form-control" name="hubungan_penanggung_jawab" id="hubungan_penanggung_jawab">
                                    <option value="">Pilih Hubungan</option>
                                    <option value="1">Diri Sendiri</option>
                                    <option value="2">Orang Tua</option>
                                    <option value="3">Anak</option>
                                    <option value="4">Suami/Istri</option>
                                    <option value="5">Kerabat/Saudara</option>
                                    <option value="6">Lain-lain</option>
                                </select>
                                <input type="text" class="form-control mt-2" name="hubungan_penanggung_jawab_lain" id="hubungan_penanggung_jawab_lain" placeholder="Isi jika Lain-lain" style="display:none;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pengantar Pasien -->
                <div class="card mt-4">
                <div class="card-header custom-purple">
                        <h4><i class="fas fa-user"></i> Identitas Pengantar Pasien</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nama_pengantar">Nama Pengantar Pasien</label>
                                <input type="text" class="form-control" name="nama_pengantar" id="nama_pengantar">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="telepon_pengantar">Nomor Telepon Seluler Pengantar Pasien</label>
                                <input type="text" class="form-control" name="telepon_pengantar" id="telepon_pengantar">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg" name="simpan" id="simpan" accesskey="s">
                            <i class="fas fa-save"></i> Simpan [ALT+S]
                        </button>
                        <button type="button" class="btn btn-success btn-lg" name="simpan_cetak" id="simpan_cetak" accesskey="c" onclick="submitAndPrint()">
                            <i class="fas fa-print"></i> Simpan & Cetak Kartu [ALT+C]
                        </button>
                        <button type="reset" class="btn btn-warning btn-lg" name="reset" id="reset" accesskey="r">
                            <i class="fas fa-undo"></i> Reset [ALT+R]
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
<?php $this->load->view('dist/_partials/footer'); ?>

<script>
(function() {
    'use strict';
    
    // Base URL untuk API
    var baseUrl = '<?php echo base_url(); ?>';
    
    // Helper function untuk menghitung umur
    function hitungUmur() {
        var tanggalLahir = document.getElementById('tanggal_lahir');
        if (tanggalLahir && tanggalLahir.value) {
            var today = new Date();
            var birthDate = new Date(tanggalLahir.value);
            var age = today.getFullYear() - birthDate.getFullYear();
            var monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            // Bisa ditampilkan di field umur jika ada
            console.log('Umur:', age);
        }
    }
    
    // Function untuk submit dan print
    function submitAndPrint() {
        var form = document.getElementById('formPendaftaran');
        if (form) {
            form.submit();
            // Setelah submit, akan redirect atau bisa buka print window
            // window.open('', '_blank', 'width=800,height=600').document.write(form);
        }
    }
    
    // Expose functions to global scope
    window.hitungUmur = hitungUmur;
    window.submitAndPrint = submitAndPrint;
    
    // Helper to populate dropdowns
    function populateDropdown(dropdown, data, placeholder) {
        if (!dropdown) return;
        dropdown.innerHTML = '<option value="">' + placeholder + '</option>';
        if (data && Array.isArray(data)) {
            data.forEach(function(item) {
                if (item && item.id && item.nama) {
                    var option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.nama;
                    dropdown.appendChild(option);
                }
            });
        }
    }
    
    // Fetch and initialize chained dropdowns for a given prefix (identitas/domisili)
    function initAlamat(prefix) {
        var provinsi = document.getElementById('provinsi_' + prefix);
        var kabupaten = document.getElementById('kabupaten_' + prefix);
        var kecamatan = document.getElementById('kecamatan_' + prefix);
        var kelurahan = document.getElementById('kelurahan_' + prefix);
        var kode_pos = document.getElementById('kode_pos_' + prefix);
        
        if (!provinsi || !kabupaten || !kecamatan || !kelurahan || !kode_pos) {
            console.warn('Element tidak ditemukan untuk prefix:', prefix);
            return;
        }
        
        // Fetch provinsi on load
        fetch(baseUrl + 'api/wilayah/provinsi')
            .then(function(res) {
                if (!res.ok) {
                    throw new Error('HTTP error! status: ' + res.status);
                }
                return res.json();
            })
            .then(function(data) {
                populateDropdown(provinsi, data, 'Pilih Provinsi');
            })
            .catch(function(error) {
                console.error('Error loading provinsi:', error);
                provinsi.innerHTML = '<option value="">Error loading provinsi</option>';
            });
        
        // Provinsi change handler
        provinsi.addEventListener('change', function() {
            var provinsiId = this.value;
            populateDropdown(kabupaten, [], 'Pilih Kota/Kabupaten');
            populateDropdown(kecamatan, [], 'Pilih Kecamatan');
            populateDropdown(kelurahan, [], 'Pilih Kelurahan/Desa');
            kode_pos.value = '';
            
            if (provinsiId) {
                kabupaten.disabled = true;
                fetch(baseUrl + 'api/wilayah/kabupaten?provinsi=' + encodeURIComponent(provinsiId))
                    .then(function(res) {
                        if (!res.ok) throw new Error('HTTP error! status: ' + res.status);
                        return res.json();
                    })
                    .then(function(data) {
                        populateDropdown(kabupaten, data, 'Pilih Kota/Kabupaten');
                        kabupaten.disabled = false;
                    })
                    .catch(function(error) {
                        console.error('Error loading kabupaten:', error);
                        kabupaten.innerHTML = '<option value="">Error loading kabupaten</option>';
                        kabupaten.disabled = false;
                    });
            }
        });
        
        // Kabupaten change handler
        kabupaten.addEventListener('change', function() {
            var kabupatenId = this.value;
            populateDropdown(kecamatan, [], 'Pilih Kecamatan');
            populateDropdown(kelurahan, [], 'Pilih Kelurahan/Desa');
            kode_pos.value = '';
            
            if (kabupatenId) {
                kecamatan.disabled = true;
                fetch(baseUrl + 'api/wilayah/kecamatan?kabupaten=' + encodeURIComponent(kabupatenId))
                    .then(function(res) {
                        if (!res.ok) throw new Error('HTTP error! status: ' + res.status);
                        return res.json();
                    })
                    .then(function(data) {
                        populateDropdown(kecamatan, data, 'Pilih Kecamatan');
                        kecamatan.disabled = false;
                    })
                    .catch(function(error) {
                        console.error('Error loading kecamatan:', error);
                        kecamatan.innerHTML = '<option value="">Error loading kecamatan</option>';
                        kecamatan.disabled = false;
                    });
            }
        });
        
        // Kecamatan change handler
        kecamatan.addEventListener('change', function() {
            var kecamatanId = this.value;
            populateDropdown(kelurahan, [], 'Pilih Kelurahan/Desa');
            kode_pos.value = '';
            
            if (kecamatanId) {
                kelurahan.disabled = true;
                fetch(baseUrl + 'api/wilayah/kelurahan?kecamatan=' + encodeURIComponent(kecamatanId))
                    .then(function(res) {
                        if (!res.ok) throw new Error('HTTP error! status: ' + res.status);
                        return res.json();
                    })
                    .then(function(data) {
                        populateDropdown(kelurahan, data, 'Pilih Kelurahan/Desa');
                        kelurahan.disabled = false;
                    })
                    .catch(function(error) {
                        console.error('Error loading kelurahan:', error);
                        kelurahan.innerHTML = '<option value="">Error loading kelurahan</option>';
                        kelurahan.disabled = false;
                    });
            }
        });
        
        // Kelurahan change handler
        kelurahan.addEventListener('change', function() {
            var kelurahanId = this.value;
            kode_pos.value = '';
            
            if (kelurahanId) {
                fetch(baseUrl + 'api/wilayah/kodepos?kelurahan=' + encodeURIComponent(kelurahanId))
                    .then(function(res) {
                        if (!res.ok) throw new Error('HTTP error! status: ' + res.status);
                        return res.json();
                    })
                    .then(function(data) {
                        if (data && data.kode_pos) {
                            kode_pos.value = data.kode_pos;
                        }
                    })
                    .catch(function(error) {
                        console.error('Error loading kodepos:', error);
                    });
            }
        });
    }
    
    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize address dropdowns
        initAlamat('identitas');
        initAlamat('domisili');
        
        // Tampilkan field lain jika agama/pekerjaan/hubungan "Lain-lain"
        var agama = document.getElementById('agama');
        var agamaLain = document.getElementById('agama_lain');
        if (agama && agamaLain) {
            agama.addEventListener('change', function() {
                agamaLain.style.display = (this.value == '8') ? 'block' : 'none';
                if (this.value != '8') {
                    agamaLain.value = '';
                }
            });
        }
        
        var pekerjaan = document.getElementById('pekerjaan');
        var pekerjaanLain = document.getElementById('pekerjaan_lain');
        if (pekerjaan && pekerjaanLain) {
            pekerjaan.addEventListener('change', function() {
                pekerjaanLain.style.display = (this.value == '5') ? 'block' : 'none';
                if (this.value != '5') {
                    pekerjaanLain.value = '';
                }
            });
        }
        
        var hubungan = document.getElementById('hubungan_penanggung_jawab');
        var hubunganLain = document.getElementById('hubungan_penanggung_jawab_lain');
        if (hubungan && hubunganLain) {
            hubungan.addEventListener('change', function() {
                hubunganLain.style.display = (this.value == '6') ? 'block' : 'none';
                if (this.value != '6') {
                    hubunganLain.value = '';
                }
            });
        }
        
        var pasienTidakDikenal = document.getElementById('pasien_tidak_dikenal');
        var sectionPasienTidakDikenal = document.getElementById('section_pasien_tidak_dikenal');
        if (pasienTidakDikenal && sectionPasienTidakDikenal) {
            pasienTidakDikenal.addEventListener('change', function() {
                sectionPasienTidakDikenal.style.display = this.checked ? 'block' : 'none';
            });
        }
    });
})();
</script>