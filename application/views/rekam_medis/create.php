<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('dist/_partials/header'); ?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?php echo isset($title) ? $title : 'Tambah Rekam Medis'; ?></h1>
    </div>

    <div class="section-body">
      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
      <?php endif; ?>

      <div class="card">
        <div class="card-header"><h4>Form Rekam Medis</h4></div>
        <div class="card-body">
          <form method="post" action="<?php echo site_url('rekam-medis/store'); ?>" class="needs-validation" novalidate>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Pasien</label>
                <select name="id_pasien" class="form-control" required>
                  <option value="">Pilih pasien</option>
                  <?php if (!empty($pasien)) { foreach ($pasien as $p) { ?>
                    <option value="<?php echo htmlspecialchars($p->id_pasien); ?>"><?php echo htmlspecialchars($p->nama_lengkap . ' (' . $p->no_rm . ')'); ?></option>
                  <?php } } ?>
                </select>
                <div class="invalid-feedback">Pilih pasien</div>
              </div>
              <div class="form-group col-md-3">
                <label>Tanggal Kunjungan</label>
                <input type="date" name="tanggal_kunjungan" class="form-control" required>
                <div class="invalid-feedback">Isi tanggal</div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Dokter</label>
                <select name="id_dokter" class="form-control" required>
                  <option value="">Pilih dokter</option>
                  <?php if (!empty($pegawai)) { foreach ($pegawai as $d) { ?>
                    <option value="<?php echo htmlspecialchars($d->id_pegawai); ?>"><?php echo htmlspecialchars($d->nama_lengkap); ?></option>
                  <?php } } ?>
                </select>
                <div class="invalid-feedback">Pilih dokter</div>
              </div>
              <div class="form-group col-md-6">
                <label>Poliklinik</label>
                <select name="id_poliklinik" class="form-control" required>
                  <option value="">Pilih poliklinik</option>
                  <?php if (!empty($poliklinik)) { foreach ($poliklinik as $pol) { ?>
                    <option value="<?php echo htmlspecialchars($pol->id_poliklinik); ?>"><?php echo htmlspecialchars($pol->nama_poliklinik); ?></option>
                  <?php } } ?>
                </select>
                <div class="invalid-feedback">Pilih poliklinik</div>
              </div>
            </div>

            <div class="form-group">
              <label>Keluhan Utama</label>
              <textarea name="keluhan_utama" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label>Pemeriksaan Fisik</label>
              <textarea name="pemeriksaan_fisik" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Diagnosis Utama</label>
                <input type="text" name="diagnosis_utama" class="form-control">
              </div>
              <div class="form-group col-md-6">
                <label>Kode ICD10 Utama</label>
                <input type="text" name="kode_icd10_utama" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <label>Tindakan</label>
              <textarea name="tindakan" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
              <label>Catatan Dokter</label>
              <textarea name="catatan_dokter" class="form-control" rows="2"></textarea>
            </div>

            <div class="text-right">
              <a href="<?php echo base_url('rekam-medis'); ?>" class="btn btn-secondary">Kembali</a>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
(function(){
  'use strict';
  window.addEventListener('load', function(){
    var forms=document.getElementsByClassName('needs-validation');
    Array.prototype.forEach.call(forms,function(form){
      form.addEventListener('submit', function(e){
        if(form.checkValidity()===false){ e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php $this->load->view('dist/_partials/footer'); ?>


