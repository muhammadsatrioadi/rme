<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('dist/_partials/header');
?>

<div class="main-content">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="card">
                        <div class="card-header">
                            <h4>Simpan Pasien</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?= site_url('pendaftaran/simpanpasien') ?>" method="post">
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 col-form-label">Nama Pasien</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?= site_url('pendaftaran/listpasien') ?>" class="btn btn-secondary">Batal</a>
                    </div>
            </div>
        </div>
    </section>                                                                                                                                                          
</div>
<?php $this->load->view('dist/_partials/footer'); ?>