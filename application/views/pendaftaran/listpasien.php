<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>List Pasien</h4>
                        </div>
                        <div class="card-body">
                            <!-- Filter Pencarian Pasien -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <input type="text" id="searchPasien" class="form-control" placeholder="Cari nama pasien, alamat, atau telepon...">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-pasien">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Pasien</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Umur</th>
                                            <th>Alamat</th>
                                            <th>No. Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Contoh data statis, ganti dengan data dinamis dari database -->
                                        <tr>
                                            <td>1</td>
                                            <td>Ahmad Fauzi</td>
                                            <td>Laki-laki</td>
                                            <td>30</td>
                                            <td>Jl. Merdeka No. 10</td>
                                            <td>08123456789</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm">Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Siti Aminah</td>
                                            <td>Perempuan</td>
                                            <td>25</td>
                                            <td>Jl. Sudirman No. 20</td>
                                            <td>08234567890</td>
                                            <td>
                                                <a href="#" class="btn btn-primary btn-sm">Edit</a>
                                                <a href="#" class="btn btn-danger btn-sm">Hapus</a>
                                            </td>
                                        </tr>
                                        <!-- Data pasien lain bisa ditambahkan di sini -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    // Filter pencarian pasien
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchPasien');
        const table = document.getElementById('table-pasien');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            for (let i = 0; i < rows.length; i++) {
                let rowText = rows[i].textContent.toLowerCase();
                if (rowText.indexOf(filter) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    });
</script>
<?php $this->load->view('dist/_partials/footer'); ?>?>
