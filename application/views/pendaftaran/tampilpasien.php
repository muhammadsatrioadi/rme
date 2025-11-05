<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard Sistem RME</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
            </div>
        </div>

        <div class="section-body">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pasien</h4>
                            </div>
                            <div class="card-body">
                                1,234
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-file-medical"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Rekam Medis</h4>
                            </div>
                            <div class="card-body">
                                5,678
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Pegawai</h4>
                            </div>
                            <div class="card-body">
                                89
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-pills"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total Obat</h4>
                            </div>
                            <div class="card-body">
                                456
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Statistics -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Kunjungan Hari Ini</h4>
                            </div>
                            <div class="card-body">
                                45
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-secondary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Kunjungan Bulan Ini</h4>
                            </div>
                            <div class="card-body">
                                1,234
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Stok Rendah</h4>
                            </div>
                            <div class="card-body">
                                12
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Obat Expired</h4>
                            </div>
                            <div class="card-body">
                                3
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Aksi Cepat</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-primary">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Daftar Pasien Baru</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="#" class="btn btn-primary btn-block">
                                                    <i class="fas fa-plus"></i> Tambah Pasien
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-danger">
                                            <i class="fas fa-file-medical-alt"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Rekam Medis Baru</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="#" class="btn btn-danger btn-block">
                                                    <i class="fas fa-plus"></i> Buat Rekam Medis
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-success">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Resep Obat</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="#" class="btn btn-success btn-block">
                                                    <i class="fas fa-plus"></i> Buat Resep
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-info">
                                            <i class="fas fa-chart-bar"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Laporan</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="#" class="btn btn-info btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rekam Medis Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pasien</th>
                                            <th>Poliklinik</th>
                                            <th>Dokter</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Ahmad Fauzi</td>
                                            <td>Poli Umum</td>
                                            <td>Dr. Siti Aminah</td>
                                            <td>15/01/2024 10:30</td>
                                            <td><span class="badge badge-success">Selesai</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Budi Santoso</td>
                                            <td>Poli Anak</td>
                                            <td>Dr. Rina Wati</td>
                                            <td>15/01/2024 09:15</td>
                                            <td><span class="badge badge-warning">Draft</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Citra Dewi</td>
                                            <td>Poli Kandungan</td>
                                            <td>Dr. Maya Sari</td>
                                            <td>15/01/2024 08:45</td>
                                            <td><span class="badge badge-success">Selesai</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pasien Baru</h4>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-primary text-white">
                                    A
                                </div>
                                <div class="ml-3">
                                    <div class="font-weight-bold">Ahmad Fauzi</div>
                                    <div class="text-muted small">RM-2024-000001</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-primary text-white">
                                    B
                                </div>
                                <div class="ml-3">
                                    <div class="font-weight-bold">Budi Santoso</div>
                                    <div class="text-muted small">RM-2024-000002</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar bg-primary text-white">
                                    C
                                </div>
                                <div class="ml-3">
                                    <div class="font-weight-bold">Citra Dewi</div>
                                    <div class="text-muted small">RM-2024-000003</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php $this->load->view('dist/_partials/footer'); ?>
