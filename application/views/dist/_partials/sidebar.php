<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?php echo base_url('dashboard'); ?>">Sistem RME</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?php echo base_url('dashboard'); ?>">RME</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="<?php echo $this->uri->segment(1) == '' || $this->uri->segment(1) == 'dashboard' ? 'active' : ''; ?>">
              <a class="nav-link" href="<?php echo base_url('dashboard'); ?>"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>
            <li class="<?php echo $this->uri->segment(1) == 'pasien' ? 'active' : ''; ?>">
              <a class="nav-link" href="<?php echo base_url('pasien'); ?>"><i class="fas fa-users"></i> <span>Pasien</span></a>
            </li>
            <li class="<?php echo $this->uri->segment(1) == 'pendaftaran' ? 'active' : ''; ?>">
              <a class="nav-link" href="<?php echo base_url('pendaftaran'); ?>"><i class="fas fa-notes-medical"></i> <span>Pendaftaran</span></a>
            </li>
            <li class="<?php echo $this->uri->segment(1) == 'rekam-medis' || $this->uri->segment(1) == 'rekam_medis' ? 'active' : ''; ?>">
              <a class="nav-link" href="<?php echo base_url('rekam-medis'); ?>"><i class="fas fa-file-medical"></i> <span>Rekam Medis</span></a>
            </li>
            <li class="<?php echo $this->uri->segment(1) == 'laporan' ? 'active' : ''; ?>">
              <a class="nav-link" href="<?php echo base_url('laporan'); ?>"><i class="fas fa-chart-bar"></i> <span>Laporan</span></a>
            </li>
            <li class="menu-header">Akun</li>
            <li>
              <a class="nav-link" href="<?php echo base_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            </li>
          </ul>
        </aside>
      </div>
