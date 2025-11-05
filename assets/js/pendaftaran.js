// Generate No. Rekam Medis
function generateNoRekamMedis() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    
    const noRekamMedis = `${year}${month}${day}${random}`;
    document.getElementById('no_rekam_medis').value = noRekamMedis;
}

// Hitung Umur
function hitungUmur() {
    const tanggalLahir = document.getElementById('tanggal_lahir').value;
    if (tanggalLahir) {
        const lahir = new Date(tanggalLahir);
        const sekarang = new Date();
        
        let tahun = sekarang.getFullYear() - lahir.getFullYear();
        let bulan = sekarang.getMonth() - lahir.getMonth();
        let hari = sekarang.getDate() - lahir.getDate();
        
        if (hari < 0) {
            bulan--;
            hari += new Date(sekarang.getFullYear(), sekarang.getMonth(), 0).getDate();
        }
        
        if (bulan < 0) {
            tahun--;
            bulan += 12;
        }
        
        document.getElementById('umur_tahun').value = tahun;
        document.getElementById('umur_bulan').value = bulan;
        document.getElementById('umur_hari').value = hari;
    }
}

// Copy Alamat
function copyAlamat() {
    const checkbox = document.getElementById('sama_alamat');
    if (checkbox.checked) {
        document.getElementById('alamat_identitas_text').value = document.getElementById('alamat_sekarang').value;
        document.getElementById('rt_identitas').value = document.getElementById('rt_sekarang').value;
        document.getElementById('rw_identitas').value = document.getElementById('rw_sekarang').value;
        document.getElementById('provinsi_identitas').value = document.getElementById('provinsi_sekarang').value;
        document.getElementById('kabupaten_identitas').value = document.getElementById('kabupaten_sekarang').value;
        document.getElementById('kecamatan_identitas').value = document.getElementById('kecamatan_sekarang').value;
        document.getElementById('kelurahan_identitas').value = document.getElementById('kelurahan_sekarang').value;
        document.getElementById('kode_pos_identitas').value = document.getElementById('kode_pos_sekarang').value;
    }
}

// Load Kabupaten
function loadKabupaten(type) {
    const provinsi = document.getElementById(`provinsi_${type}`).value;
    const kabupatenSelect = document.getElementById(`kabupaten_${type}`);
    
    kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
    
    if (provinsi === 'DAERAH ISTIMEWA YOGYAKARTA') {
        const kabupaten = ['SLEMAN', 'BANTUL', 'GUNUNG KIDUL', 'KULON PROGO', 'YOGYAKARTA'];
        kabupaten.forEach(kab => {
            const option = document.createElement('option');
            option.value = kab;
            option.textContent = kab;
            kabupatenSelect.appendChild(option);
        });
    }
}

// Load Kecamatan
function loadKecamatan(type) {
    const kabupaten = document.getElementById(`kabupaten_${type}`).value;
    const kecamatanSelect = document.getElementById(`kecamatan_${type}`);
    
    kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
    
    if (kabupaten === 'SLEMAN') {
        const kecamatan = ['Mlati', 'Depok', 'Berbah', 'Prambanan', 'Kalasan', 'Ngaglik', 'Ngemplak', 'Pakem', 'Cangkringan', 'Turi', 'Seyegan', 'Gamping', 'Moyudan', 'Minggir', 'Tempel'];
        kecamatan.forEach(kec => {
            const option = document.createElement('option');
            option.value = kec;
            option.textContent = kec;
            kecamatanSelect.appendChild(option);
        });
    }
}

// Load Kelurahan
function loadKelurahan(type) {
    const kecamatan = document.getElementById(`kecamatan_${type}`).value;
    const kelurahanSelect = document.getElementById(`kelurahan_${type}`);
    
    kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
    
    if (kecamatan === 'Mlati') {
        const kelurahan = ['Sendangadi', 'Sinduadi', 'Tlogoadi', 'Sumberadi', 'Tirtoadi'];
        kelurahan.forEach(kel => {
            const option = document.createElement('option');
            option.value = kel;
            option.textContent = kel;
            kelurahanSelect.appendChild(option);
        });
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (e.altKey) {
        switch(e.key) {
            case 's':
                e.preventDefault();
                document.getElementById('simpan').click();
                break;
            case 'c':
                e.preventDefault();
                document.getElementById('simpan_cetak').click();
                break;
            case 'r':
                e.preventDefault();
                document.getElementById('reset').click();
                break;
        }
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    generateNoRekamMedis();
    
    // Set default values
    document.getElementById('kewarganegaraan').value = 'INDONESIA';
    document.getElementById('status_pasien').value = 'Hidup';
    document.getElementById('jenis_telepon').value = 'Telepon Seluler';
});
