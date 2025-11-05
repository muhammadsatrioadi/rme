<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_medis extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Rekam_medis_model');
        $this->load->model('Pasien_model');
        $this->load->model('Pegawai_model');
        $this->load->model('Poliklinik_model');
        $this->load->model('Obat_model');
        $this->load->helper('url');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Rekam Medis - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get all medical records
        $data['rekam_medis'] = $this->Rekam_medis_model->get_all_rekam_medis();
        
        $this->load->view('rekam_medis/index', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Rekam Medis - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get dropdown data
        $data['pasien'] = $this->Pasien_model->get_all_active();
        $data['pegawai'] = $this->Pegawai_model->get_all_doctors();
        $data['poliklinik'] = $this->Poliklinik_model->get_all_active();
        
        $this->load->view('rekam_medis/create', $data);
    }

    public function edit($id) {
        $data['title'] = 'Edit Rekam Medis - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get medical record data
        $data['rekam_medis'] = $this->Rekam_medis_model->get_rekam_medis_by_id($id);
        
        if (!$data['rekam_medis']) {
            show_404();
        }
        
        // Get dropdown data
        $data['pasien'] = $this->Pasien_model->get_all_active();
        $data['pegawai'] = $this->Pegawai_model->get_all_doctors();
        $data['poliklinik'] = $this->Poliklinik_model->get_all_active();
        
        $this->load->view('rekam_medis/edit', $data);
    }

    public function store() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_pasien', 'Pasien', 'required');
        $this->form_validation->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'required');
        $this->form_validation->set_rules('id_dokter', 'Dokter', 'required');
        $this->form_validation->set_rules('id_poliklinik', 'Poliklinik', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('rekam-medis/create');
        }
        
        $data = array(
            'id_pasien' => $this->input->post('id_pasien'),
            'tanggal_kunjungan' => $this->input->post('tanggal_kunjungan'),
            'id_dokter' => $this->input->post('id_dokter'),
            'id_poliklinik' => $this->input->post('id_poliklinik'),
            'keluhan_utama' => $this->input->post('keluhan_utama'),
            'riwayat_penyakit' => $this->input->post('riwayat_penyakit'),
            'pemeriksaan_fisik' => $this->input->post('pemeriksaan_fisik'),
            'diagnosis_utama' => $this->input->post('diagnosis_utama'),
            'kode_icd10_utama' => $this->input->post('kode_icd10_utama'),
            'tindakan' => $this->input->post('tindakan'),
            'catatan_dokter' => $this->input->post('catatan_dokter'),
            'status_rm' => 'Aktif',
            'created_by' => $this->session->userdata('user_id'),
            'created_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->Rekam_medis_model->insert_rekam_medis($data)) {
            $this->session->set_flashdata('success', 'Rekam medis berhasil ditambahkan!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan rekam medis!');
        }
        
        redirect('rekam-medis');
    }

    public function update($id) {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('id_pasien', 'Pasien', 'required');
        $this->form_validation->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'required');
        $this->form_validation->set_rules('id_dokter', 'Dokter', 'required');
        $this->form_validation->set_rules('id_poliklinik', 'Poliklinik', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('rekam-medis/edit/' . $id);
        }
        
        $data = array(
            'id_pasien' => $this->input->post('id_pasien'),
            'tanggal_kunjungan' => $this->input->post('tanggal_kunjungan'),
            'id_dokter' => $this->input->post('id_dokter'),
            'id_poliklinik' => $this->input->post('id_poliklinik'),
            'keluhan_utama' => $this->input->post('keluhan_utama'),
            'riwayat_penyakit' => $this->input->post('riwayat_penyakit'),
            'pemeriksaan_fisik' => $this->input->post('pemeriksaan_fisik'),
            'diagnosis_utama' => $this->input->post('diagnosis_utama'),
            'kode_icd10_utama' => $this->input->post('kode_icd10_utama'),
            'tindakan' => $this->input->post('tindakan'),
            'catatan_dokter' => $this->input->post('catatan_dokter'),
            'updated_by' => $this->session->userdata('user_id'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        if ($this->Rekam_medis_model->update_rekam_medis($id, $data)) {
            $this->session->set_flashdata('success', 'Rekam medis berhasil diupdate!');
        } else {
            $this->session->set_flashdata('error', 'Gagal update rekam medis!');
        }
        
        redirect('rekam-medis');
    }

    public function delete($id) {
        if ($this->Rekam_medis_model->delete_rekam_medis($id)) {
            $this->session->set_flashdata('success', 'Rekam medis berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus rekam medis!');
        }
        
        redirect('rekam-medis');
    }

    public function view($id) {
        $data['title'] = 'Detail Rekam Medis - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get medical record data
        $data['rekam_medis'] = $this->Rekam_medis_model->get_rekam_medis_by_id($id);
        
        if (!$data['rekam_medis']) {
            show_404();
        }
        
        $this->load->view('rekam_medis/view', $data);
    }

    public function get_data() {
        // For AJAX requests
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        
        $all = $this->Rekam_medis_model->get_all_rekam_medis($length, $start);
        $data = array();
        
        foreach ($all as $row) {
            $data[] = array(
                $row->id_rm,
                $row->nama_lengkap,
                $row->tanggal_kunjungan,
                $row->nama_poliklinik,
                $row->nama_dokter,
                $row->diagnosis_utama,
                $row->status_rm,
                '<a href="' . site_url('rekam-medis/view/' . $row->id_rm) . '" class="btn btn-sm btn-info">View</a>' .
                '<a href="' . site_url('rekam-medis/edit/' . $row->id_rm) . '" class="btn btn-sm btn-warning">Edit</a>' .
                '<a href="' . site_url('rekam-medis/delete/' . $row->id_rm) . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus?\')">Delete</a>'
            );
        }
        
        $output = array(
            "draw" => $draw,
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        );
        
        echo json_encode($output);
    }
}

