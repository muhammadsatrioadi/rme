<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Pasien</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?php echo base_url('pasien'); ?>">Data Pasien</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Informasi Pasien</h4>
                            <div class="card-header-action">
                                <a href="<?php echo base_url('pasien/edit/' . $pasien->id_pasien); ?>" class="btn btn-warning">Edit</a>
                                <a href="<?php echo base_url('pasien'); ?>" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No. RM</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->no_rm); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>NIK</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->nik); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->nama_lengkap); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Jenis Kelamin</label>
                                        <input type="text" class="form-control" value="<?php echo $pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tempat, Tanggal Lahir</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->tempat_lahir . ', ' . $pasien->tanggal_lahir); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Golongan Darah / Rhesus</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->golongan_darah . ' ' . $pasien->rhesus); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Alamat KTP</label>
                                <textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($pasien->alamat_ktp); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No Telepon</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->no_telepon); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>No HP</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien->no_hp); ?>" readonly>
                                    </div>
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


