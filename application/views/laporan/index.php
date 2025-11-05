<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item active">Laporan</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Menu Laporan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Laporan Pasien -->
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-primary">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Laporan Pasien</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="<?php echo base_url('laporan/pasien'); ?>" class="btn btn-primary btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laporan Rekam Medis -->
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-danger">
                                            <i class="fas fa-file-medical"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Laporan Rekam Medis</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="<?php echo base_url('laporan/rekam_medis'); ?>" class="btn btn-danger btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laporan Kunjungan -->
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-warning">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Laporan Kunjungan</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="<?php echo base_url('laporan/kunjungan'); ?>" class="btn btn-warning btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laporan Diagnosis -->
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-success">
                                            <i class="fas fa-stethoscope"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Laporan Diagnosis</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="<?php echo base_url('laporan/diagnosis'); ?>" class="btn btn-success btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Laporan Obat -->
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-info">
                                            <i class="fas fa-pills"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Laporan Obat</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="<?php echo base_url('laporan/obat'); ?>" class="btn btn-info btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Audit Trail -->
                                <?php if ($this->User_model->has_permission($user['role'], 'audit_trail', 'read')): ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-secondary">
                                            <i class="fas fa-history"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Audit Trail</h4>
                                            </div>
                                            <div class="card-body">
                                                <a href="<?php echo base_url('laporan/audit_trail'); ?>" class="btn btn-secondary btn-block">
                                                    <i class="fas fa-chart-bar"></i> Lihat Laporan
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Statistik Cepat</h4>
                        </div>
                        <div class="card-body">
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
                                                <?php echo number_format($this->Pasien_model->count_pasien()); ?>
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
                                                <h4>Total Rekam Medis</h4>
                                            </div>
                                            <div class="card-body">
                                                <?php echo number_format($this->Rekam_medis_model->count_rekam_medis()); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-warning">
                                            <i class="fas fa-calendar-day"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Kunjungan Hari Ini</h4>
                                            </div>
                                            <div class="card-body">
                                                <?php 
                                                $this->db->where('tanggal_kunjungan', date('Y-m-d'));
                                                echo number_format($this->db->count_all_results('rekam_medis')); 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-success">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Kunjungan Bulan Ini</h4>
                                            </div>
                                            <div class="card-body">
                                                <?php 
                                                $this->db->where('MONTH(tanggal_kunjungan)', date('m'));
                                                $this->db->where('YEAR(tanggal_kunjungan)', date('Y'));
                                                echo number_format($this->db->count_all_results('rekam_medis')); 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Reports -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Laporan Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Jenis Laporan</th>
                                            <th>Tanggal Dibuat</th>
                                            <th>Dibuat Oleh</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada laporan yang dibuat</td>
                                        </tr>
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

<?php $this->load->view('dist/_partials/footer'); ?>

