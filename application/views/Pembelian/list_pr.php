<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Daftar Purchase Request</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Pembelian</div>
                <div class="breadcrumb-item">List PR</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Filter dan Search -->
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filter & Pencarian</h4>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= base_url('pembelian/list_pr'); ?>">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="search">Pencarian</label>
                                <input type="text" class="form-control" name="search" id="search" placeholder="No PR, Pemohon, atau Departemen" value="<?= $this->input->get('search'); ?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="status">Status</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="">Semua Status</option>
                                    <option value="Draft" <?= $this->input->get('status') == 'Draft' ? 'selected' : ''; ?>>Draft</option>
                                    <option value="Submitted" <?= $this->input->get('status') == 'Submitted' ? 'selected' : ''; ?>>Submitted</option>
                                    <option value="Approved" <?= $this->input->get('status') == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="Rejected" <?= $this->input->get('status') == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="priority">Prioritas</label>
                                <select class="form-control" name="priority" id="priority">
                                    <option value="">Semua Prioritas</option>
                                    <option value="LOW" <?= $this->input->get('priority') == 'LOW' ? 'selected' : ''; ?>>RENDAH</option>
                                    <option value="MEDIUM" <?= $this->input->get('priority') == 'MEDIUM' ? 'selected' : ''; ?>>SEDANG</option>
                                    <option value="HIGH" <?= $this->input->get('priority') == 'HIGH' ? 'selected' : ''; ?>>TINGGI</option>
                                    <option value="URGENT" <?= $this->input->get('priority') == 'URGENT' ? 'selected' : ''; ?>>MENDESAK</option>
                                </select>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="date_from">Tanggal Dari</label>
                                <input type="date" class="form-control" name="date_from" id="date_from" value="<?= $this->input->get('date_from'); ?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="date_to">Tanggal Sampai</label>
                                <input type="date" class="form-control" name="date_to" id="date_to" value="<?= $this->input->get('date_to'); ?>">
                            </div>
                            <div class="form-group col-md-1 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List PR -->
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-list"></i> Daftar Purchase Request</h4>
                    <div class="card-header-action">
                        <a href="<?= base_url('pembelian/pr'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah PR Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="prTable">
                            <thead>
                                <tr>
                                    <th width="50" class="text-center">#</th>
                                    <th width="120">No. PR</th>
                                    <th width="100">Tanggal</th>
                                    <th width="150">Pemohon</th>
                                    <th width="120">Departemen</th>
                                    <th width="100">Prioritas</th>
                                    <th width="100">Tipe</th>
                                    <th width="120">Total Estimasi</th>
                                    <th width="100">Status</th>
                                    <th width="100">Level Approval</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($pr_list)): ?>
                                    <?php $no = 1; foreach ($pr_list as $pr): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td>
                                                <strong><?= $pr['pr_no']; ?></strong>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($pr['pr_date'])); ?></td>
                                            <td><?= $pr['requester_name']; ?></td>
                                            <td><?= $pr['dept_name']; ?></td>
                                            <td>
                                                <?php
                                                $priority_colors = [
                                                    'LOW' => 'success',
                                                    'MEDIUM' => 'warning', 
                                                    'HIGH' => 'danger',
                                                    'URGENT' => 'dark'
                                                ];
                                                $priority_labels = [
                                                    'LOW' => 'RENDAH',
                                                    'MEDIUM' => 'SEDANG',
                                                    'HIGH' => 'TINGGI', 
                                                    'URGENT' => 'MENDESAK'
                                                ];
                                                $color = $priority_colors[$pr['priority']] ?? 'secondary';
                                                $label = $priority_labels[$pr['priority']] ?? $pr['priority'];
                                                ?>
                                                <span class="badge badge-<?= $color; ?>"><?= $label; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info"><?= $pr['pr_type']; ?></span>
                                            </td>
                                            <td class="text-right">
                                                <strong>Rp <?= number_format($pr['total_estimation'], 0, ',', '.'); ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                $status_colors = [
                                                    'Draft' => 'secondary',
                                                    'Submitted' => 'primary',
                                                    'Approved' => 'success',
                                                    'Rejected' => 'danger'
                                                ];
                                                $color = $status_colors[$pr['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?= $color; ?>"><?= $pr['status']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-light"><?= $pr['approval_level']; ?>/<?= $pr['approval_final_level']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-info" onclick="viewPR('<?= $pr['pr_no']; ?>')" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <?php if ($pr['status'] == 'Draft'): ?>
                                                        <button type="button" class="btn btn-sm btn-warning" onclick="editPR('<?= $pr['pr_no']; ?>')" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    <button type="button" class="btn btn-sm btn-success" onclick="printPR('<?= $pr['pr_no']; ?>')" title="Cetak">
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                    <?php if ($pr['status'] == 'Draft'): ?>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="deletePR('<?= $pr['pr_no']; ?>')" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11" class="text-center">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">Tidak ada data Purchase Request</h5>
                                                <p class="text-muted">Belum ada Purchase Request yang dibuat atau data tidak ditemukan.</p>
                                                <a href="<?= base_url('pembelian/pr'); ?>" class="btn btn-primary">
                                                    <i class="fas fa-plus"></i> Buat PR Pertama
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($pagination)): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan <?= $pagination['start']; ?> - <?= $pagination['end']; ?> dari <?= $pagination['total']; ?> data
                            </div>
                            <nav>
                                <?= $pagination['links']; ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail PR -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">
                    <i class="fas fa-file-alt mr-2"></i>Detail Purchase Request
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Detail content will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="printPR()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('dist/_partials/footer'); ?>

<script>
$(document).ready(function() {
    // Initialize DataTable if needed
    $('#prTable').DataTable({
        "pageLength": 25,
        "order": [[ 1, "desc" ]], // Sort by PR No descending
        "columnDefs": [
            { "orderable": false, "targets": [0, 10] } // Disable sorting for # and Action columns
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});

function viewPR(prNo) {
    // Load PR detail via AJAX
    $.ajax({
        url: '<?= base_url('pembelian/detail_pr/'); ?>' + prNo,
        type: 'GET',
        dataType: 'html',
        beforeSend: function() {
            $('#detailModalBody').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Loading...</div>');
        },
        success: function(data) {
            $('#detailModalBody').html(data);
            $('#detailModal').modal('show');
        },
        error: function() {
            $('#detailModalBody').html('<div class="alert alert-danger">Gagal memuat detail PR</div>');
            $('#detailModal').modal('show');
        }
    });
}

function editPR(prNo) {
    if (confirm('Apakah Anda yakin ingin mengedit PR ini?')) {
        window.location.href = '<?= base_url('pembelian/edit_pr/'); ?>' + prNo;
    }
}

function printPR(prNo) {
    if (prNo) {
        window.open('<?= base_url('pembelian/print_pr/'); ?>' + prNo, '_blank');
    } else {
        // Print from modal
        window.print();
    }
}

function deletePR(prNo) {
    if (confirm('Apakah Anda yakin ingin menghapus PR ini? Tindakan ini tidak dapat dibatalkan.')) {
        $.ajax({
            url: '<?= base_url('pembelian/delete_pr'); ?>',
            type: 'POST',
            data: {
                pr_no: prNo,
                <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showAlert('PR berhasil dihapus', 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    showAlert(response.message || 'Gagal menghapus PR', 'danger');
                }
            },
            error: function() {
                showAlert('Terjadi kesalahan saat menghapus PR', 'danger');
            }
        });
    }
}

function showAlert(message, type) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    // Insert alert at the top of section-body
    $('.section-body').prepend(alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// Auto refresh every 30 seconds for real-time updates
setInterval(function() {
    // Only refresh if no modal is open
    if (!$('.modal').hasClass('show')) {
        location.reload();
    }
}, 30000);
</script>
