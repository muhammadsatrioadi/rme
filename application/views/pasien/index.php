<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Data Pasien</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item active">Data Pasien</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Pasien</h4>
                            <div class="card-header-action">
                                <?php if ($this->User_model->has_permission($user['role'], 'pasien', 'create')): ?>
                                    <a href="<?php echo base_url('pasien/create'); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Tambah Pasien
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo base_url('pasien/export'); ?>" class="btn btn-success">
                                    <i class="fas fa-download"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Search and Filter -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchInput" placeholder="Cari pasien...">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="searchBtn">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped" id="pasienTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No RM</th>
                                            <th>NIK</th>
                                            <th>Nama Lengkap</th>
                                            <th>Jenis Kelamin</th>
                                            <th>Tanggal Lahir</th>
                                            <th>Alamat</th>
                                            <th>No Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pasien)): ?>
                                            <?php $no = 1; foreach ($pasien as $p): ?>
                                                <tr>
                                                    <td><?php echo $no++; ?></td>
                                                    <td><?php echo $p->no_rm; ?></td>
                                                    <td><?php echo $p->nik; ?></td>
                                                    <td><?php echo $p->nama_lengkap; ?></td>
                                                    <td><?php echo $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($p->tanggal_lahir)); ?></td>
                                                    <td><?php echo substr($p->alamat_ktp, 0, 50) . '...'; ?></td>
                                                    <td><?php echo $p->no_telepon; ?></td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?php echo base_url('pasien/view/' . $p->id_pasien); ?>" 
                                                               class="btn btn-info btn-sm" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <?php if ($this->User_model->has_permission($user['role'], 'pasien', 'update')): ?>
                                                                <a href="<?php echo base_url('pasien/edit/' . $p->id_pasien); ?>" 
                                                                   class="btn btn-warning btn-sm" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <?php if ($this->User_model->has_permission($user['role'], 'pasien', 'delete')): ?>
                                                                <a href="<?php echo base_url('pasien/delete/' . $p->id_pasien); ?>" 
                                                                   class="btn btn-danger btn-sm" 
                                                                   title="Hapus"
                                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data pasien ini?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada data pasien</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if (isset($pagination)): ?>
                                <div class="d-flex justify-content-center">
                                    <?php echo $pagination; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Search functionality
    $('#searchBtn').click(function() {
        var search = $('#searchInput').val();
        if (search) {
            window.location.href = '<?php echo base_url('pasien'); ?>?search=' + encodeURIComponent(search);
        }
    });

    $('#searchInput').keypress(function(e) {
        if (e.which == 13) {
            $('#searchBtn').click();
        }
    });

    // Auto-hide alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>

<?php $this->load->view('dist/_partials/footer'); ?>

