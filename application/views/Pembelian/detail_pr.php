<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row">
    <div class="col-md-6">
        <h6 class="text-primary"><i class="fas fa-file-alt mr-2"></i>Informasi Purchase Request</h6>
        <table class="table table-sm table-borderless">
            <tr>
                <td width="40%"><strong>No. PR:</strong></td>
                <td><?= $pr['pr_no']; ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal PR:</strong></td>
                <td><?= date('d/m/Y', strtotime($pr['pr_date'])); ?></td>
            </tr>
            <tr>
                <td><strong>Pemohon:</strong></td>
                <td><?= $pr['requester_name']; ?></td>
            </tr>
            <tr>
                <td><strong>Departemen:</strong></td>
                <td><?= $pr['dept_name']; ?></td>
            </tr>
            <tr>
                <td><strong>Tanggal Dibutuhkan:</strong></td>
                <td><?= $pr['need_by_date'] ? date('d/m/Y', strtotime($pr['need_by_date'])) : '-'; ?></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="text-primary"><i class="fas fa-info-circle mr-2"></i>Status & Prioritas</h6>
        <table class="table table-sm table-borderless">
            <tr>
                <td width="40%"><strong>Status:</strong></td>
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
            </tr>
            <tr>
                <td><strong>Prioritas:</strong></td>
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
            </tr>
            <tr>
                <td><strong>Tipe PR:</strong></td>
                <td><span class="badge badge-info"><?= $pr['pr_type']; ?></span></td>
            </tr>
            <tr>
                <td><strong>Mata Uang:</strong></td>
                <td><?= $pr['currency']; ?></td>
            </tr>
            <tr>
                <td><strong>Level Approval:</strong></td>
                <td><?= $pr['approval_level']; ?>/<?= $pr['approval_final_level']; ?></td>
            </tr>
        </table>
    </div>
</div>

<?php if (!empty($pr['justification'])): ?>
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary"><i class="fas fa-comment mr-2"></i>Justifikasi</h6>
        <div class="alert alert-light">
            <?= nl2br($pr['justification']); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($pr['remarks'])): ?>
<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary"><i class="fas fa-sticky-note mr-2"></i>Catatan</h6>
        <div class="alert alert-light">
            <?= nl2br($pr['remarks']); ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-12">
        <h6 class="text-primary"><i class="fas fa-list-alt mr-2"></i>Detail Barang/Jasa</h6>
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="thead-light">
                    <tr>
                        <th width="50" class="text-center">#</th>
                        <th>Barang/Jasa</th>
                        <th>Deskripsi</th>
                        <th width="80">Satuan</th>
                        <th width="80" class="text-center">Qty</th>
                        <th width="100" class="text-right">Harga Satuan</th>
                        <th width="100" class="text-right">Total</th>
                        <th width="100">Gudang</th>
                        <th width="100">Cost Center</th>
                        <th width="100">Proyek</th>
                        <th width="100">Tgl Dibutuhkan</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pr['details'])): ?>
                        <?php foreach ($pr['details'] as $detail): ?>
                            <tr>
                                <td class="text-center"><?= $detail['line_no']; ?></td>
                                <td><?= $detail['item_name'] ?: '-'; ?></td>
                                <td><?= $detail['item_desc'] ?: '-'; ?></td>
                                <td><?= $detail['uom_name'] ?: '-'; ?></td>
                                <td class="text-center"><?= number_format($detail['qty_req'], 0, ',', '.'); ?></td>
                                <td class="text-right">Rp <?= number_format($detail['est_unit_price'], 0, ',', '.'); ?></td>
                                <td class="text-right"><strong>Rp <?= number_format($detail['est_line_total'], 0, ',', '.'); ?></strong></td>
                                <td><?= $detail['warehouse_name'] ?: '-'; ?></td>
                                <td><?= $detail['cc_name'] ?: '-'; ?></td>
                                <td><?= $detail['project_name'] ?: '-'; ?></td>
                                <td><?= $detail['need_by_date'] ? date('d/m/Y', strtotime($detail['need_by_date'])) : '-'; ?></td>
                                <td><?= $detail['note'] ?: '-'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted">Tidak ada detail barang/jasa</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <?php
                    $subtotal = 0;
                    if (!empty($pr['details'])) {
                        foreach ($pr['details'] as $detail) {
                            $subtotal += $detail['est_line_total'];
                        }
                    }
                    $tax = $subtotal * 0.11;
                    $total = $subtotal + $tax;
                    ?>
                    <tr>
                        <td colspan="6" class="text-right font-weight-bold">Subtotal Estimasi</td>
                        <td class="text-right font-weight-bold">Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <td colspan="6" class="text-right font-weight-bold">Pajak (11%)</td>
                        <td class="text-right font-weight-bold">Rp <?= number_format($tax, 0, ',', '.'); ?></td>
                        <td colspan="5"></td>
                    </tr>
                    <tr class="table-success">
                        <td colspan="6" class="text-right font-weight-bold h5">Total Estimasi</td>
                        <td class="text-right font-weight-bold h5 text-success">Rp <?= number_format($total, 0, ',', '.'); ?></td>
                        <td colspan="5"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <small class="text-muted">
            <i class="fas fa-clock mr-1"></i>
            Dibuat: <?= date('d/m/Y H:i', strtotime($pr['created_at'])); ?>
            <?php if ($pr['updated_at']): ?>
                | Diupdate: <?= date('d/m/Y H:i', strtotime($pr['updated_at'])); ?>
            <?php endif; ?>
        </small>
    </div>
</div>
