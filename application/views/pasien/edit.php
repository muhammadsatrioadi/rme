<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit Pasien</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?php echo base_url('pasien'); ?>">Data Pasien</a></div>
                <div class="breadcrumb-item active">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Form Edit Pasien</h4>
                        </div>
                        <div class="card-body">
                            <?php if ($this->session->flashdata('error')): ?>
                                <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
                            <?php endif; ?>

                            <?php echo form_open('pasien/edit/' . $pasien->id_pasien, array('class' => 'needs-validation', 'novalidate' => '')); ?>

                            <div class="section-title">Data Pribadi</div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nik">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo form_error('nik') ? 'is-invalid' : ''; ?>" id="nik" name="nik" value="<?php echo set_value('nik', $pasien->nik); ?>" maxlength="16" required>
                                    <?php echo form_error('nik', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo form_error('nama_lengkap') ? 'is-invalid' : ''; ?>" id="nama_lengkap" name="nama_lengkap" value="<?php echo set_value('nama_lengkap', $pasien->nama_lengkap); ?>" required>
                                    <?php echo form_error('nama_lengkap', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="nama_panggilan">Nama Panggilan</label>
                                    <input type="text" class="form-control" id="nama_panggilan" name="nama_panggilan" value="<?php echo set_value('nama_panggilan', $pasien->nama_panggilan); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control <?php echo form_error('jenis_kelamin') ? 'is-invalid' : ''; ?>" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="L" <?php echo set_select('jenis_kelamin', 'L', $pasien->jenis_kelamin == 'L'); ?>>Laki-laki</option>
                                        <option value="P" <?php echo set_select('jenis_kelamin', 'P', $pasien->jenis_kelamin == 'P'); ?>>Perempuan</option>
                                    </select>
                                    <?php echo form_error('jenis_kelamin', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="tempat_lahir">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?php echo form_error('tempat_lahir') ? 'is-invalid' : ''; ?>" id="tempat_lahir" name="tempat_lahir" value="<?php echo set_value('tempat_lahir', $pasien->tempat_lahir); ?>" required>
                                    <?php echo form_error('tempat_lahir', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control <?php echo form_error('tanggal_lahir') ? 'is-invalid' : ''; ?>" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo set_value('tanggal_lahir', $pasien->tanggal_lahir); ?>" required>
                                    <?php echo form_error('tanggal_lahir', '<div class="invalid-feedback">', '</div>'); ?>
                                </div>
                            </div>

                            <div class="section-title">Alamat KTP</div>
                            <div class="form-group">
                                <label for="alamat_ktp">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control <?php echo form_error('alamat_ktp') ? 'is-invalid' : ''; ?>" id="alamat_ktp" name="alamat_ktp" rows="3" required><?php echo set_value('alamat_ktp', $pasien->alamat_ktp); ?></textarea>
                                <?php echo form_error('alamat_ktp', '<div class="invalid-feedback">', '</div>'); ?>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="rt_ktp">RT</label>
                                    <input type="text" class="form-control" id="rt_ktp" name="rt_ktp" value="<?php echo set_value('rt_ktp', $pasien->rt_ktp); ?>">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="rw_ktp">RW</label>
                                    <input type="text" class="form-control" id="rw_ktp" name="rw_ktp" value="<?php echo set_value('rw_ktp', $pasien->rw_ktp); ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="kelurahan_ktp">Kelurahan/Desa</label>
                                    <input type="text" class="form-control" id="kelurahan_ktp" name="kelurahan_ktp" value="<?php echo set_value('kelurahan_ktp', $pasien->kelurahan_ktp); ?>">
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <a href="<?php echo base_url('pasien'); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                            </div>

                            <?php echo form_close(); ?>
                        </div>
                    </div>
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
    Array.prototype.forEach.call(forms, function(form){
      form.addEventListener('submit', function(e){
        if(form.checkValidity()===false){ e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>

<?php $this->load->view('dist/_partials/footer'); ?>


