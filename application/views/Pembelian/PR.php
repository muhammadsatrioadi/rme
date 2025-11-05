<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Purchase Request</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= site_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('pembelian/list_pr'); ?>">List PR</a></div>
                <div class="breadcrumb-item">Purchase Request</div>
            </div>
        </div>

        <div class="section-body">
            <form id="prForm" method="POST" action="<?= base_url('pembelian/simpan_pr'); ?>">
                <!-- Data Purchase Request -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-alt"></i> Data Purchase Request</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pr_no">No. PR <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pr_no" id="pr_no" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pr_date">Tanggal PR <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="pr_date" id="pr_date" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="requester_id">Pemohon <span class="text-danger">*</span></label>
                                <select class="form-control" name="requester_id" id="requester_id" required>
                                    <option value="">Pilih Pemohon</option>
                                    <option value="1">John Doe</option>
                                    <option value="2">Jane Smith</option>
                                    <option value="3">Ahmad Fauzi</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="dept_id">Departemen <span class="text-danger">*</span></label>
                                <select class="form-control" name="dept_id" id="dept_id" required>
                                    <option value="">Pilih Departemen</option>
                                    <option value="1">IT Department</option>
                                    <option value="2">HR Department</option>
                                    <option value="3">Finance Department</option>
                                    <option value="4">Operations</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="need_by_date">Tanggal Dibutuhkan</label>
                                <input type="date" class="form-control" name="need_by_date" id="need_by_date">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="priority">Prioritas <span class="text-danger">*</span></label>
                                <select class="form-control" name="priority" id="priority" required>
                                    <option value="">Pilih Prioritas</option>
                                    <option value="LOW">RENDAH</option>
                                    <option value="MEDIUM" selected>SEDANG</option>
                                    <option value="HIGH">TINGGI</option>
                                    <option value="URGENT">MENDESAK</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="pr_type">Tipe PR <span class="text-danger">*</span></label>
                                <select class="form-control" name="pr_type" id="pr_type" required>
                                    <option value="">Pilih Tipe PR</option>
                                    <option value="OPEX">OPEX</option>
                                    <option value="CAPEX">CAPEX</option>
                                    <option value="SERVICE">JASA</option>
                                    <option value="GENERAL" selected>UMUM</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="currency">Mata Uang</label>
                                <input type="text" class="form-control" name="currency" id="currency" value="IDR" readonly>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="exch_rate">Kurs</label>
                                <input type="number" step="0.000001" class="form-control" name="exch_rate" id="exch_rate" value="1.000000" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="status">Status</label>
                                <input type="text" class="form-control" name="status" id="status" value="Draft" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="approval_level">Level Approval</label>
                                <input type="number" class="form-control" name="approval_level" id="approval_level" value="0" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="approval_final_level">Total Level</label>
                                <input type="number" class="form-control" name="approval_final_level" id="approval_final_level" value="1" readonly>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="justification">Justifikasi</label>
                                <textarea class="form-control" name="justification" id="justification" rows="3" placeholder="Alasan pembelian barang/jasa ini..."></textarea>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="remarks">Catatan</label>
                                <textarea class="form-control" name="remarks" id="remarks" rows="3" placeholder="Catatan tambahan..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Barang/Jasa -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-list-alt"></i> Detail Barang/Jasa</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Item</h5>
                            <button type="button" class="btn btn-primary btn-sm" onclick="addDetailRow()">
                                <i class="fas fa-plus mr-1"></i>
                                Tambah Item
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="prDetailTable">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="50" class="text-center">#</th>
                                        <th width="150">Barang/Jasa</th>
                                        <th width="200">Deskripsi</th>
                                        <th width="100">Satuan</th>
                                        <th width="100">Qty Diminta</th>
                                        <th width="120">Harga Satuan</th>
                                        <th width="120">Total Baris</th>
                                        <th width="120">Gudang</th>
                                        <th width="120">Cost Center</th>
                                        <th width="120">Proyek</th>
                                        <th width="120">Tanggal Dibutuhkan</th>
                                        <th width="150">Catatan</th>
                                        <th width="80" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="prDetailBody">
                                    <!-- Baris detail PR akan ditambahkan di sini -->
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="6" class="text-right font-weight-bold">
                                            <i class="fas fa-calculator mr-1"></i>
                                            Subtotal Estimasi
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right font-weight-bold" id="subtotal_est" name="subtotal_est" readonly value="0.00">
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-right font-weight-bold">
                                            <i class="fas fa-percentage mr-1"></i>
                                            Pajak (11%)
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right font-weight-bold" id="tax_est" name="tax_est" readonly value="0.00">
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                    <tr class="table-success">
                                        <td colspan="6" class="text-right font-weight-bold h5">
                                            <i class="fas fa-money-bill-wave mr-1"></i>
                                            Total Estimasi
                                        </td>
                                        <td>
                                            <input type="text" class="form-control text-right font-weight-bold h5 text-success" id="total_est" name="total_est" readonly value="0.00">
                                        </td>
                                        <td colspan="6"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="card">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-primary btn-lg" name="simpan" id="simpan" accesskey="s">
                            <i class="fas fa-save"></i> Simpan [ALT+S]
                        </button>
                        <button type="button" class="btn btn-success btn-lg" name="simpan_submit" id="simpan_submit" accesskey="c" onclick="submitForm()">
                            <i class="fas fa-paper-plane"></i> Submit PR [ALT+C]
                        </button>
                        <button type="reset" class="btn btn-warning btn-lg" name="reset" id="reset" accesskey="r">
                            <i class="fas fa-undo"></i> Reset [ALT+R]
                        </button>
                        <a href="<?= base_url('pembelian/list_pr'); ?>" class="btn btn-info btn-lg" accesskey="l">
                            <i class="fas fa-list"></i> Lihat List PR [ALT+L]
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php $this->load->view('dist/_partials/footer'); ?>

<script>
let detailRow = 0;

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    // Set today's date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('pr_date').value = today;
});

function addDetailRow() {
    detailRow++;
    let row = `
        <tr>
            <td class="text-center">
                <input type="hidden" name="detail[${detailRow}][line_no]" value="${detailRow}">
                <strong>${detailRow}</strong>
            </td>
            <td>
                <select class="form-control" name="detail[${detailRow}][item_id]" onchange="loadItemData(this, ${detailRow})">
                    <option value="">-- Pilih Barang --</option>
                    <option value="1">Laptop Dell Inspiron 15</option>
                    <option value="2">Printer HP LaserJet</option>
                    <option value="3">Jasa Maintenance Server</option>
                    <option value="4">Monitor Samsung 24"</option>
                    <option value="5">Keyboard Mechanical</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control" name="detail[${detailRow}][item_desc]" maxlength="500" placeholder="Deskripsi barang/jasa">
            </td>
            <td>
                <select class="form-control" name="detail[${detailRow}][uom_id]" required>
                    <option value="">-- Satuan --</option>
                    <option value="1">Unit</option>
                    <option value="2">Pcs</option>
                    <option value="3">Box</option>
                    <option value="4">Kg</option>
                    <option value="5">Meter</option>
                    <option value="6">Bulan</option>
                    <option value="7">Hari</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" min="0" class="form-control qty_req" name="detail[${detailRow}][qty_req]" value="1" required onchange="updateLineTotal(this.closest('tr'))">
            </td>
            <td>
                <input type="number" step="0.01" min="0" class="form-control est_unit_price" name="detail[${detailRow}][est_unit_price]" value="0" required onchange="updateLineTotal(this.closest('tr'))">
            </td>
            <td>
                <input type="text" class="form-control est_line_total text-right font-weight-bold" name="detail[${detailRow}][est_line_total]" value="0.00" readonly>
            </td>
            <td>
                <select class="form-control" name="detail[${detailRow}][warehouse_id]">
                    <option value="">-- Pilih Gudang --</option>
                    <option value="1">Gudang Utama</option>
                    <option value="2">Gudang IT</option>
                    <option value="3">Gudang Umum</option>
                </select>
            </td>
            <td>
                <select class="form-control" name="detail[${detailRow}][cc_id]">
                    <option value="">-- Pilih Cost Center --</option>
                    <option value="1">IT Department</option>
                    <option value="2">HR Department</option>
                    <option value="3">Finance Department</option>
                </select>
            </td>
            <td>
                <select class="form-control" name="detail[${detailRow}][project_id]">
                    <option value="">-- Pilih Proyek --</option>
                    <option value="1">Proyek A</option>
                    <option value="2">Proyek B</option>
                    <option value="3">Proyek C</option>
                </select>
            </td>
            <td>
                <input type="date" class="form-control" name="detail[${detailRow}][need_by_date]">
            </td>
            <td>
                <input type="text" class="form-control" name="detail[${detailRow}][note]" maxlength="300" placeholder="Catatan">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeDetailRow(this)" title="Hapus baris">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    document.getElementById('prDetailBody').insertAdjacentHTML('beforeend', row);
    updateTotals();
}

function removeDetailRow(btn) {
    if (confirm('Apakah Anda yakin ingin menghapus baris ini?')) {
        btn.closest('tr').remove();
        updateTotals();
    }
}

function updateLineTotal(row) {
    let qty = parseFloat(row.querySelector('.qty_req').value) || 0;
    let price = parseFloat(row.querySelector('.est_unit_price').value) || 0;
    let total = qty * price;
    row.querySelector('.est_line_total').value = formatCurrency(total);
}

function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('#prDetailBody tr').forEach(function(row) {
        updateLineTotal(row);
        let estLineTotal = parseFloat(row.querySelector('.est_line_total').value.replace(/[^\d.-]/g, '')) || 0;
        subtotal += estLineTotal;
    });
    
    document.getElementById('subtotal_est').value = formatCurrency(subtotal);
    
    // Pajak 11% (PPN)
    let tax = subtotal * 0.11;
    document.getElementById('tax_est').value = formatCurrency(tax);
    
    let total = subtotal + tax;
    document.getElementById('total_est').value = formatCurrency(total);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}

function loadItemData(select, rowNum) {
    const itemId = select.value;
    const row = select.closest('tr');
    
    // Sample data
    const itemData = {
        '1': { name: 'Laptop Dell Inspiron 15', price: 8500000, uom: '1' },
        '2': { name: 'Printer HP LaserJet', price: 2500000, uom: '1' },
        '3': { name: 'Jasa Maintenance Server', price: 1500000, uom: '6' },
        '4': { name: 'Monitor Samsung 24"', price: 3000000, uom: '1' },
        '5': { name: 'Keyboard Mechanical', price: 500000, uom: '1' }
    };
    
    if (itemData[itemId]) {
        const data = itemData[itemId];
        row.querySelector('input[name*="[item_desc]"]').value = data.name;
        row.querySelector('input[name*="[est_unit_price]"]').value = data.price;
        row.querySelector('select[name*="[uom_id]"]').value = data.uom;
        updateLineTotal(row);
        updateTotals();
    }
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua data form?')) {
        document.getElementById('prForm').reset();
        document.getElementById('prDetailBody').innerHTML = '';
        detailRow = 0;
        updateTotals();
        showAlert('Form telah direset', 'success');
    }
}

function saveDraft() {
    if (validateForm()) {
        showAlert('Draft berhasil disimpan', 'success');
        // Here you would typically save via AJAX
    }
}

function submitForm() {
    if (validateForm()) {
        if (confirm('Apakah Anda yakin ingin mengirim Purchase Request ini?')) {
            showAlert('Purchase Request berhasil dikirim!', 'success');
            // Here you would typically submit via AJAX
        }
    }
}

function validateForm() {
    const requiredFields = [
        'pr_date', 'requester_id', 'dept_id', 'priority', 'pr_type'
    ];
    
    for (let field of requiredFields) {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            showAlert(`Field ${element.previousElementSibling.textContent} harus diisi!`, 'danger');
            element.focus();
            return false;
        }
    }
    
    if (detailRow === 0) {
        showAlert('Minimal harus ada 1 item barang/jasa!', 'danger');
        return false;
    }
    
    return true;
}

function showAlert(message, type) {
    const alertContainer = document.getElementById('alert-container');
    const alertId = 'alert-' + Date.now();
    
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
