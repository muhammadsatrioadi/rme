<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<style>
/* Enhanced CSS untuk tampilan yang lebih baik */
.form-row-flex {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  margin-bottom: 1.5rem;
  align-items: flex-end;
  clear: both;
}

.form-row-flex > .form-group {
  flex: 1 1 0;
  min-width: 220px;
  margin-bottom: 0;
  position: relative;
  z-index: 1;
}

.form-row-flex > .form-group label {
  font-weight: 600;
  color: #495057;
  margin-bottom: 5px;
}

.form-row-flex > .form-group .form-control {
  border-radius: 8px;
  border: 1px solid #ced4da;
  padding: 10px 12px;
  transition: all 0.3s ease;
  font-size: 14px;
  width: 100%;
  box-sizing: border-box;
  position: relative;
  z-index: 2;
}

.form-row-flex > .form-group .form-control:focus {
  border-color: #007bff;
  box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  outline: none;
}

.form-row-flex > .form-group .form-control:read-only {
  background-color: #f8f9fa;
  color: #6c757d;
}

.card {
  border: none;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 30px;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.card-header {
  border-radius: 12px 12px 0 0 !important;
  border-bottom: 1px solid #e9ecef;
  padding: 20px 25px;
}

.card-header h4 {
  margin: 0;
  font-weight: 600;
  color: #fff;
}

.card-body {
  padding: 25px;
  position: relative;
  z-index: 1;
  overflow: visible;
}

.btn {
  border-radius: 8px;
  padding: 10px 20px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.table {
  border-radius: 8px;
  overflow: hidden;
}

.table thead th {
  background-color: #f8f9fa;
  border-bottom: 2px solid #dee2e6;
  font-weight: 600;
  color: #495057;
  padding: 15px 12px;
}

.table tbody td {
  padding: 12px;
  vertical-align: middle;
}

.table tfoot td {
  background-color: #f8f9fa;
  font-weight: 600;
  padding: 15px 12px;
}

.badge {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: 500;
}

.section-header {
  margin-bottom: 30px;
}

.section-header h1 {
  color: #2c3e50;
  font-weight: 700;
  margin-bottom: 10px;
}

.section-header-breadcrumb {
  font-size: 14px;
}

.section-header-breadcrumb .breadcrumb-item a {
  color: #6c757d;
  text-decoration: none;
}

.section-header-breadcrumb .breadcrumb-item.active {
  color: #007bff;
  font-weight: 500;
}

/* Fix untuk mencegah tumpang tindih */
.section-body {
  position: relative;
  z-index: 1;
  overflow: visible;
}

.section {
  position: relative;
  z-index: 1;
  overflow: visible;
}

/* Responsive adjustments */
@media (max-width: 1199.98px) {
  .form-row-flex > .form-group {
    min-width: 200px;
  }
}

@media (max-width: 991.98px) {
  .form-row-flex {
    flex-direction: column;
    gap: 15px;
  }
  
  .form-row-flex > .form-group {
    width: 100%;
    min-width: 0;
    margin-bottom: 0;
  }
  
  .card-body {
    padding: 20px;
}
}

@media (max-width: 575.98px) {
  .form-row-flex > .form-group {
    width: 100%;
    min-width: 0;
    margin-bottom: 0;
  }
  
  .form-row-flex {
    gap: 15px;
  }
  
  .card-body {
    padding: 15px;
  }
  
  .section-header h1 {
    font-size: 24px;
  }
}

/* Fix untuk input group */
.input-group {
  position: relative;
  z-index: 2;
}

.input-group .form-control {
  position: relative;
  z-index: 2;
}

.input-group-append {
  position: relative;
  z-index: 3;
}

/* Fix untuk select2 */
.select2-container {
  position: relative;
  z-index: 2;
}

.select2-dropdown {
  z-index: 9999 !important;
}

/* Fix untuk modal */
.modal {
  z-index: 1050;
}

.modal-backdrop {
  z-index: 1040;
}

/* Custom select2 styling */
.select2-container--default .select2-selection--single {
  height: 42px;
  border: 1px solid #ced4da;
  border-radius: 8px;
  position: relative;
  z-index: 2;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 40px;
  padding-left: 12px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 40px;
}

/* Fix untuk dropdown yang tumpang tindih */
.select2-container--open .select2-dropdown {
  z-index: 9999 !important;
  position: absolute !important;
}

/* Fix untuk form control yang tumpang tindih */
.form-control:focus {
  position: relative;
  z-index: 10;
}

/* Fix untuk button yang tumpang tindih */
.btn {
  position: relative;
  z-index: 2;
}

/* Fix untuk table yang tumpang tindih */
.table-responsive {
  position: relative;
  z-index: 1;
  overflow-x: auto;
  overflow-y: visible;
}

/* Fix untuk alert yang tumpang tindih */
.alert {
  position: relative;
  z-index: 10;
  margin-bottom: 15px;
}

/* Form validation styling */
.is-invalid {
  border-color: #dc3545 !important;
}

.invalid-feedback {
  display: block;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #dc3545;
}

/* Loading state */
.loading {
  opacity: 0.6;
  pointer-events: none;
}

/* Success message */
.alert-success {
  background-color: #d4edda;
  border-color: #c3e6cb;
  color: #155724;
  border-radius: 8px;
}

/* Error message */
.alert-danger {
  background-color: #f8d7da;
  border-color: #f5c6cb;
  color: #721c24;
  border-radius: 8px;
}

/* Additional fixes untuk mencegah tumpang tindih */
* {
  box-sizing: border-box;
}

.form-group {
  position: relative;
  z-index: 1;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  position: relative;
  z-index: 1;
}

/* Fix untuk input yang tumpang tindih */
input[type="text"], 
input[type="date"], 
input[type="number"], 
select, 
textarea {
  position: relative;
  z-index: 2;
  display: block;
  width: 100%;
}

/* Fix untuk wrapper form */
.form-wrapper {
  position: relative;
  z-index: 1;
  overflow: visible;
}

/* Fix untuk card yang tumpang tindih */
.card + .card {
  margin-top: 20px;
}

/* Fix untuk section yang tumpang tindih */
.section + .section {
  margin-top: 30px;
}

/* Fix khusus untuk card yang bertumpuk */
.card {
  clear: both;
  margin-bottom: 30px !important;
  position: relative;
  z-index: auto;
}

.card:not(:last-child) {
  margin-bottom: 30px;
}

/* Fix untuk form row yang bertumpuk */
.form-row-flex {
  clear: both;
  margin-bottom: 20px !important;
  position: relative;
  z-index: 1;
}

.form-row-flex:not(:last-child) {
  margin-bottom: 20px;
}

/* Fix untuk card body yang bertumpuk */
.card-body {
  clear: both;
  position: relative;
  z-index: 1;
  overflow: visible;
  padding: 25px !important;
}

/* Fix untuk form yang bertumpuk */
form {
  position: relative;
  z-index: 1;
  clear: both;
}

/* Fix untuk input group yang bertumpuk */
.input-group {
  position: relative;
  z-index: 2;
  clear: both;
  margin-bottom: 0;
}

/* Fix untuk select yang bertumpuk */
select.form-control {
  position: relative;
  z-index: 2;
  clear: both;
}

/* Fix untuk input yang bertumpuk */
input.form-control {
  position: relative;
  z-index: 2;
  clear: both;
}

/* Fix untuk textarea yang bertumpuk */
textarea.form-control {
  position: relative;
  z-index: 2;
  clear: both;
}

/* Fix agresif untuk mencegah tumpang tindih */
.section-body > * {
  clear: both !important;
  margin-bottom: 30px !important;
  position: relative !important;
  z-index: 1 !important;
}

.section-body > .card {
  clear: both !important;
  margin-bottom: 30px !important;
  position: relative !important;
  z-index: 1 !important;
  overflow: visible !important;
}

.section-body > .card > .card-body {
  clear: both !important;
  position: relative !important;
  z-index: 1 !important;
  overflow: visible !important;
  padding: 25px !important;
}

.section-body > .card > .card-body > form {
  clear: both !important;
  position: relative !important;
  z-index: 1 !important;
  overflow: visible !important;
}

.section-body > .card > .card-body > form > .form-row-flex {
  clear: both !important;
  margin-bottom: 20px !important;
  position: relative !important;
  z-index: 1 !important;
  overflow: visible !important;
}

.section-body > .card > .card-body > form > .form-row-flex > .form-group {
  clear: both !important;
  position: relative !important;
  z-index: 1 !important;
  overflow: visible !important;
}

.section-body > .card > .card-body > form > .form-row-flex > .form-group > .form-control {
  clear: both !important;
  position: relative !important;
  z-index: 2 !important;
  overflow: visible !important;
}

/* Fix untuk mencegah float issues */
.section-body::after {
  content: "";
  display: table;
  clear: both;
}

.card::after {
  content: "";
  display: table;
  clear: both;
}

.form-row-flex::after {
  content: "";
  display: table;
  clear: both;
}
</style>

<section class="section">
  <div class="section-header">
    <h1>Pendaftaran Purchase Request Baru</h1>
    <div class="section-header-breadcrumb">
      <div class="breadcrumb-item"><a href="#">Dashboard</a></div>
      <div class="breadcrumb-item"><a href="#">Pembelian</a></div>
      <div class="breadcrumb-item active">Purchase Request</div>
    </div>
  </div>

  <div class="section-body">
    <!-- Alert Messages -->
    <div id="alert-container" class="mb-3"></div>
    
    <!-- Card Identitas Utama PR -->
    <div class="card" style="margin-bottom: 30px; clear: both;">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
          <i class="fas fa-file-alt mr-2"></i>
          Identitas Utama Purchase Request
        </h4>
      </div>
      <div class="card-body" style="clear: both; position: relative; z-index: 1;">
        <form id="prForm" method="post" action="">
          <div class="form-row-flex">
            <div class="form-group">
              <label for="pr_no">
                <i class="fas fa-hashtag mr-1"></i>
                No PR
              </label>
              <input type="text" class="form-control" id="pr_no" name="pr_no" placeholder="Otomatis" readonly>
            </div>
            <div class="form-group">
              <label for="pr_date">
                <i class="fas fa-calendar mr-1"></i>
                Tanggal PR
              </label>
              <input type="date" class="form-control" id="pr_date" name="pr_date" required>
            </div>
            <div class="form-group">
              <label for="requester_id">
                <i class="fas fa-user mr-1"></i>
                Pemohon
              </label>
              <select class="form-control select2" id="requester_id" name="requester_id" required>
                <option value="">-- Pilih Pemohon --</option>
                <option value="1">John Doe</option>
                <option value="2">Jane Smith</option>
                <option value="3">Ahmad Fauzi</option>
              </select>
            </div>
          </div>
          
          <div class="form-row-flex">
            <div class="form-group">
              <label for="dept_id">
                <i class="fas fa-building mr-1"></i>
                Departemen
              </label>
              <select class="form-control select2" id="dept_id" name="dept_id" required>
                <option value="">-- Pilih Departemen --</option>
                <option value="1">IT Department</option>
                <option value="2">HR Department</option>
                <option value="3">Finance Department</option>
                <option value="4">Operations</option>
              </select>
            </div>
            <div class="form-group">
              <label for="need_by_date">
                <i class="fas fa-clock mr-1"></i>
                Tanggal Dibutuhkan
              </label>
              <input type="date" class="form-control" id="need_by_date" name="need_by_date">
            </div>
            <div class="form-group">
              <label for="priority">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Prioritas
              </label>
              <select class="form-control" id="priority" name="priority">
                <option value="LOW">🟢 RENDAH</option>
                <option value="MEDIUM" selected>🟡 SEDANG</option>
                <option value="HIGH">🟠 TINGGI</option>
                <option value="URGENT">🔴 MENDESAK</option>
              </select>
            </div>
          </div>
          
          <div class="form-row-flex">
            <div class="form-group">
              <label for="pr_type">
                <i class="fas fa-tags mr-1"></i>
                Tipe PR
              </label>
              <select class="form-control" id="pr_type" name="pr_type">
                <option value="OPEX">OPEX</option>
                <option value="CAPEX">CAPEX</option>
                <option value="SERVICE">JASA</option>
                <option value="GENERAL" selected>UMUM</option>
              </select>
            </div>
            <div class="form-group">
              <label for="currency_pr">
                <i class="fas fa-dollar-sign mr-1"></i>
                Mata Uang
              </label>
              <input type="text" class="form-control" id="currency_pr" name="currency_pr" value="IDR" required>
            </div>
            <div class="form-group">
              <label for="exch_rate_pr">
                <i class="fas fa-exchange-alt mr-1"></i>
                Kurs
              </label>
              <input type="number" step="0.000001" class="form-control" id="exch_rate_pr" name="exch_rate_pr" value="1.000000" required>
            </div>
            <div class="form-group">
              <label for="status">
                <i class="fas fa-info-circle mr-1"></i>
                Status
              </label>
              <input type="text" class="form-control" id="status" name="status" value="Draft" readonly>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Form Purchase Order -->
    <div class="card" style="margin-bottom: 30px; clear: both;">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0">
          <i class="fas fa-shopping-cart mr-2"></i>
          Form Purchase Order
        </h4>
          </div>
      <div class="card-body" style="clear: both; position: relative; z-index: 1;">
            <form id="poForm" method="post" action="">
          <div class="form-row-flex">
            <div class="form-group">
              <label for="po_no">
                <i class="fas fa-hashtag mr-1"></i>
                No Purchase Order
              </label>
                  <input type="text" class="form-control" id="po_no" name="po_no" placeholder="Otomatis" readonly>
                </div>
            <div class="form-group">
              <label for="vendor">
                <i class="fas fa-store mr-1"></i>
                Vendor
              </label>
                  <select class="form-control select2" id="vendor" name="vendor" required>
                    <option value="">Pilih Vendor</option>
                <option value="1">PT. Supplier ABC</option>
                <option value="2">CV. Distributor XYZ</option>
                <option value="3">PT. Trading 123</option>
                  </select>
                </div>
            <div class="form-group">
              <label for="po_date">
                <i class="fas fa-calendar mr-1"></i>
                Tanggal PO
              </label>
                  <input type="date" class="form-control" id="po_date" name="po_date" required>
                </div>
            <div class="form-group">
              <label for="due_date">
                <i class="fas fa-calendar-times mr-1"></i>
                Jatuh Tempo
              </label>
                  <input type="date" class="form-control" id="due_date" name="due_date">
                </div>
              </div>
          
          <div class="form-row-flex">
            <div class="form-group">
              <label for="contact">
                <i class="fas fa-phone mr-1"></i>
                Kontak Vendor
              </label>
              <input type="text" class="form-control" id="contact" name="contact" placeholder="Nama kontak vendor">
                </div>
            <div class="form-group">
              <label for="delivery_date">
                <i class="fas fa-truck mr-1"></i>
                Tanggal Kirim
              </label>
                  <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                </div>
            <div class="form-group">
              <label for="currency_po">
                <i class="fas fa-dollar-sign mr-1"></i>
                Mata Uang
              </label>
                  <select class="form-control" id="currency_po" name="currency">
                <option value="IDR" selected>IDR - Rupiah</option>
                <option value="USD">USD - Dollar</option>
                <option value="EUR">EUR - Euro</option>
                <option value="SGD">SGD - Singapore Dollar</option>
                  </select>
                </div>
            <div class="form-group">
              <label for="exch_rate_po">
                <i class="fas fa-exchange-alt mr-1"></i>
                Kurs
              </label>
                  <input type="number" step="0.000001" class="form-control" id="exch_rate_po" name="exch_rate" value="1.000000" required>
                </div>
              </div>
          
          <div class="form-row-flex">
            <div class="form-group">
              <label for="po_type">
                <i class="fas fa-tags mr-1"></i>
                Tipe PO
              </label>
                  <select class="form-control" id="po_type" name="po_type">
                <option value="GOODS">📦 Barang</option>
                <option value="SERVICE">🔧 Jasa</option>
                  </select>
                </div>
            <div class="form-group">
              <label for="status_po">
                <i class="fas fa-info-circle mr-1"></i>
                Status
              </label>
              <input type="text" class="form-control" id="status_po" name="status_po" value="Draft" readonly>
                </div>
            <div class="form-group">
              <label for="pr_ref">
                <i class="fas fa-link mr-1"></i>
                No PR Terkait
              </label>
              <input type="text" class="form-control" id="pr_ref" name="pr_ref" placeholder="Referensi PR">
                </div>
              </div>
            </form>
      </div>
    </div>

    <!-- Purchase Request Detail Section -->
    <div class="card" style="margin-bottom: 30px; clear: both;">
      <div class="card-header bg-info text-white">
        <h4 class="mb-0">
          <i class="fas fa-list-alt mr-2"></i>
          Detail Purchase Request
        </h4>
          </div>
      <div class="card-body" style="clear: both; position: relative; z-index: 1;">
        <form id="prDetailForm" method="post" action="">
          <div class="form-row-flex">
            <div class="form-group">
              <label for="justification">
                <i class="fas fa-clipboard-list mr-1"></i>
                Justifikasi
              </label>
              <textarea class="form-control" id="justification" name="justification" rows="3" placeholder="Alasan pembelian barang/jasa ini..."></textarea>
                </div>
            <div class="form-group">
              <label for="remarks">
                <i class="fas fa-sticky-note mr-1"></i>
                Catatan
              </label>
              <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Catatan tambahan..."></textarea>
                </div>
              </div>

              <!-- Tabel Detail PR -->
          <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0">
                <i class="fas fa-table mr-2"></i>
                Detail Barang/Jasa
              </h5>
              <button type="button" class="btn btn-primary" onclick="addDetailRow()">
                <i class="fas fa-plus mr-1"></i>
                Tambah Barang
              </button>
                </div>
            
                  <div class="table-responsive">
              <table class="table table-bordered table-hover" id="prDetailTable">
                <thead class="thead-light">
                  <tr>
                    <th style="width:50px;" class="text-center">#</th>
                    <th style="min-width:150px;">Barang/Jasa</th>
                    <th style="min-width:200px;">Deskripsi</th>
                    <th style="width:100px;">Satuan</th>
                    <th style="width:100px;">Qty Diminta</th>
                    <th style="width:130px;">Harga Satuan</th>
                    <th style="width:130px;">Total Baris</th>
                          <th style="min-width:120px;">Gudang</th>
                          <th style="min-width:120px;">Cost Center</th>
                          <th style="min-width:120px;">Proyek</th>
                    <th style="width:130px;">Tanggal Dibutuhkan</th>
                          <th style="min-width:120px;">Catatan</th>
                    <th style="width:80px;" class="text-center">Aksi</th>
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

              <div class="form-group mt-4 text-right">
            <button type="button" class="btn btn-secondary mr-2" onclick="resetForm()">
              <i class="fas fa-undo mr-1"></i>
              Reset Form
            </button>
            <button type="button" class="btn btn-warning mr-2" onclick="saveDraft()">
              <i class="fas fa-save mr-1"></i>
              Simpan Draft
            </button>
            <button type="submit" class="btn btn-success" onclick="submitForm()">
              <i class="fas fa-paper-plane mr-1"></i>
              Kirim Purchase Request
            </button>
              </div>
            </form>
      </div>
    </div>

    <!-- Modal untuk memilih barang -->
    <div class="modal fade" id="itemSelectModal" tabindex="-1" aria-labelledby="itemSelectModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="itemSelectModalLabel">
              <i class="fas fa-search mr-2"></i>
              Pilih Barang/Jasa
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
          </div>
                  <input type="text" class="form-control" id="searchItem" placeholder="Cari barang/jasa...">
                </div>
              </div>
              <div class="col-md-6">
                <select class="form-control" id="filterCategory">
                  <option value="">Semua Kategori</option>
                  <option value="goods">Barang</option>
                  <option value="service">Jasa</option>
                </select>
              </div>
            </div>
            
            <div class="table-responsive">
              <table class="table table-hover" id="itemTable">
                <thead class="thead-light">
                  <tr>
                    <th width="50">Pilih</th>
                    <th>Kode</th>
                    <th>Nama Barang/Jasa</th>
                    <th>Kategori</th>
                    <th>Satuan</th>
                    <th>Harga Estimasi</th>
                    <th>Stok</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="radio" name="selectedItem" value="1"></td>
                    <td>BRG001</td>
                    <td>Laptop Dell Inspiron 15</td>
                    <td><span class="badge badge-primary">Barang</span></td>
                    <td>Unit</td>
                    <td>Rp 8.500.000</td>
                    <td>15</td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="selectedItem" value="2"></td>
                    <td>BRG002</td>
                    <td>Printer HP LaserJet</td>
                    <td><span class="badge badge-primary">Barang</span></td>
                    <td>Unit</td>
                    <td>Rp 2.500.000</td>
                    <td>8</td>
                  </tr>
                  <tr>
                    <td><input type="radio" name="selectedItem" value="3"></td>
                    <td>JSA001</td>
                    <td>Jasa Maintenance Server</td>
                    <td><span class="badge badge-success">Jasa</span></td>
                    <td>Bulan</td>
                    <td>Rp 1.500.000</td>
                    <td>-</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <i class="fas fa-times mr-1"></i>
              Batal
            </button>
            <button type="button" class="btn btn-primary" onclick="selectItem()">
              <i class="fas fa-check mr-1"></i>
              Pilih
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php $this->load->view('dist/_partials/footer'); ?>
<!-- Enhanced Script untuk Purchase Request -->
<script>
let detailRow = 0;
let selectedItemData = null;

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    // Set today's date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('pr_date').value = today;
    document.getElementById('po_date').value = today;
    
    // Initialize Select2
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Pilih...'
        });
    }
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
                <div class="input-group">
                    <select class="form-control" name="detail[${detailRow}][item_id]" onchange="loadItemData(this, ${detailRow})">
          <option value="">-- Pilih Barang --</option>
                        <option value="1">Laptop Dell Inspiron 15</option>
                        <option value="2">Printer HP LaserJet</option>
                        <option value="3">Jasa Maintenance Server</option>
        </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="openItemModal(${detailRow})">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
      </td>
      <td>
                <input type="text" class="form-control" name="detail[${detailRow}][item_desc]" maxlength="500" placeholder="Deskripsi barang/jasa">
      </td>
      <td>
        <select class="form-control" name="detail[${detailRow}][uom_id]" required>
          <option value="">-- Satuan --</option>
                    <option value="unit">Unit</option>
                    <option value="pcs">Pcs</option>
                    <option value="box">Box</option>
                    <option value="kg">Kg</option>
                    <option value="meter">Meter</option>
                    <option value="bulan">Bulan</option>
                    <option value="hari">Hari</option>
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
        '1': { name: 'Laptop Dell Inspiron 15', price: 8500000, uom: 'unit' },
        '2': { name: 'Printer HP LaserJet', price: 2500000, uom: 'unit' },
        '3': { name: 'Jasa Maintenance Server', price: 1500000, uom: 'bulan' }
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

function openItemModal(rowNum) {
    $('#itemSelectModal').modal('show');
    selectedItemData = { rowNum: rowNum };
}

function selectItem() {
    const selected = document.querySelector('input[name="selectedItem"]:checked');
    if (selected) {
        const row = selected.closest('tr');
        const itemId = selected.value;
        const itemName = row.cells[2].textContent;
        const itemPrice = parseFloat(row.cells[5].textContent.replace(/[^\d.-]/g, ''));
        const itemUom = row.cells[4].textContent;
        
        // Update the form
        const targetRow = document.querySelector(`input[name="detail[${selectedItemData.rowNum}][line_no]"]`).closest('tr');
        targetRow.querySelector('select[name*="[item_id]"]').value = itemId;
        targetRow.querySelector('input[name*="[item_desc]"]').value = itemName;
        targetRow.querySelector('input[name*="[est_unit_price]"]').value = itemPrice;
        targetRow.querySelector('select[name*="[uom_id]"]').value = itemUom.toLowerCase();
        
        updateLineTotal(targetRow);
        updateTotals();
        
        $('#itemSelectModal').modal('hide');
    } else {
        alert('Pilih barang/jasa terlebih dahulu!');
    }
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua data form?')) {
        document.getElementById('prForm').reset();
        document.getElementById('poForm').reset();
        document.getElementById('prDetailForm').reset();
        document.getElementById('prDetailBody').innerHTML = '';
        detailRow = 0;
        updateTotals();
        showAlert('Form telah direset', 'success');
    }
}

function saveDraft() {
    showAlert('Draft berhasil disimpan', 'success');
}

function submitForm() {
    if (validateForm()) {
        if (confirm('Apakah Anda yakin ingin mengirim Purchase Request ini?')) {
            showAlert('Purchase Request berhasil dikirim!', 'success');
            // Here you would typically submit the form via AJAX
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

// Search functionality for item modal
document.getElementById('searchItem').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#itemTable tbody tr');
    
    rows.forEach(row => {
        const itemName = row.cells[2].textContent.toLowerCase();
        const itemCode = row.cells[1].textContent.toLowerCase();
        
        if (itemName.includes(searchTerm) || itemCode.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Filter by category
document.getElementById('filterCategory').addEventListener('change', function() {
    const filterValue = this.value;
    const rows = document.querySelectorAll('#itemTable tbody tr');
    
    rows.forEach(row => {
        if (filterValue === '') {
            row.style.display = '';
        } else {
            const category = row.cells[3].textContent.toLowerCase();
            if (category.includes(filterValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });
});
</script>

