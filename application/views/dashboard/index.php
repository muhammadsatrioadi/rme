<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
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
                                <?php echo number_format($stats['total_pasien']); ?>
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
                                <?php echo number_format($stats['total_rm']); ?>
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
                                <?php echo number_format($stats['total_pegawai']); ?>
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
                                <?php echo number_format($stats['total_obat']); ?>
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
                                <?php echo number_format($stats['rm_hari_ini']); ?>
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
                                <?php echo number_format($stats['rm_bulan_ini']); ?>
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
                                <?php echo number_format($stats['obat_stok_rendah']); ?>
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
                                <?php echo number_format($stats['obat_expired']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kunjungan Pasien Bulanan</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="kunjunganChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Kunjungan per Poliklinik</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="poliklinikChart" height="200"></canvas>
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
                                        <?php if (!empty($recent_activities['rekam_medis'])): ?>
                                            <?php $no = 1; foreach ($recent_activities['rekam_medis'] as $rm): ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $rm->nama_lengkap; ?></td>
                                                    <td><?php echo $rm->nama_poliklinik; ?></td>
                                                    <td><?php echo $rm->nama_dokter; ?></td>
                                                    <td><?php echo date('d/m/Y H:i', strtotime($rm->tanggal_kunjungan . ' ' . $rm->jam_kunjungan)); ?></td>
                                                    <td>
                                                        <?php
                                                        $badge_class = '';
                                                        switch ($rm->status_rm) {
                                                            case 'Selesai':
                                                                $badge_class = 'badge-success';
                                                                break;
                                                            case 'Draft':
                                                                $badge_class = 'badge-warning';
                                                                break;
                                                            case 'Batal':
                                                                $badge_class = 'badge-danger';
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="badge <?php echo $badge_class; ?>"><?php echo $rm->status_rm; ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">Tidak ada data</td>
                                            </tr>
                                        <?php endif; ?>
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
                            <?php if (!empty($recent_activities['pasien'])): ?>
                                <?php foreach ($recent_activities['pasien'] as $pasien): ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar bg-primary text-white">
                                            <?php echo strtoupper(substr($pasien->nama_lengkap, 0, 1)); ?>
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-weight-bold"><?php echo $pasien->nama_lengkap; ?></div>
                                            <div class="text-muted small"><?php echo $pasien->no_rm; ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted">Tidak ada data</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="<?php echo base_url('assets/modules/chart.min.js'); ?>"></script>
<script>
    // Kunjungan Chart
    const kunjunganCtx = document.getElementById('kunjunganChart').getContext('2d');
    const kunjunganData = <?php echo json_encode($charts['kunjungan_bulanan']); ?>;
    
    new Chart(kunjunganCtx, {
        type: 'line',
        data: {
            labels: kunjunganData.map(item => item.bulan),
            datasets: [{
                label: 'Kunjungan',
                data: kunjunganData.map(item => item.total),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Poliklinik Chart
    const poliklinikCtx = document.getElementById('poliklinikChart').getContext('2d');
    const poliklinikData = <?php echo json_encode($charts['kunjungan_poliklinik']); ?>;
    
    new Chart(poliklinikCtx, {
        type: 'doughnut',
        data: {
            labels: poliklinikData.map(item => item.nama_poliklinik),
            datasets: [{
                data: poliklinikData.map(item => item.total),
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
                    '#f093fb',
                    '#f5576c',
                    '#4facfe',
                    '#00f2fe'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?php $this->load->view('dist/_partials/footer'); ?>

