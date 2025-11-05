<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('dist/_partials/header'); ?>

<div class="main-content">
  <section class="section">
    <div class="section-header">
      <h1><?php echo isset($title) ? $title : 'Rekam Medis'; ?></h1>
    </div>

    <div class="section-body">
      <div class="card">
        <div class="card-header">
          <h4>Daftar Rekam Medis</h4>
          <div class="card-header-action">
            <a href="<?php echo base_url('rekam-medis/create'); ?>" class="btn btn-primary">Tambah</a>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID RM</th>
                  <th>Pasien</th>
                  <th>Tanggal</th>
                  <th>Poliklinik</th>
                  <th>Dokter</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($rekam_medis)) { foreach ($rekam_medis as $rm) { ?>
                <tr>
                  <td><?php echo htmlspecialchars($rm->id_rm); ?></td>
                  <td><?php echo htmlspecialchars(isset($rm->nama_lengkap) ? $rm->nama_lengkap : ''); ?></td>
                  <td><?php echo htmlspecialchars(isset($rm->tanggal_kunjungan) ? $rm->tanggal_kunjungan : ''); ?></td>
                  <td><?php echo htmlspecialchars(isset($rm->nama_poliklinik) ? $rm->nama_poliklinik : ''); ?></td>
                  <td><?php echo htmlspecialchars(isset($rm->nama_dokter) ? $rm->nama_dokter : ''); ?></td>
                  <td><?php echo htmlspecialchars(isset($rm->status_rm) ? $rm->status_rm : ''); ?></td>
                  <td>
                    <a href="<?php echo site_url('rekam-medis/view/' . $rm->id_rm); ?>" class="btn btn-sm btn-info">View</a>
                  </td>
                </tr>
                <?php } } else { ?>
                <tr>
                  <td colspan="7" class="text-center">Belum ada data</td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->load->view('dist/_partials/footer'); ?>

