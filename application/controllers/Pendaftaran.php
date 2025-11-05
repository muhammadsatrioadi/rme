<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendaftaran extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->model('Pasien_model');

		if (!$this->session->userdata('logged_in')) {
			redirect('auth/login');
		}
	}

	public function index() {
		$data = array(
			'title' => 'Pendaftaran - Daftar Pasien'
		);
		$this->load->view('pendaftaran/listpasien', $data);
	}

	private function generate_no_rm() {
		$prefix = 'RM';
		$date = date('Ym');
		$this->db->like('no_rm', $prefix . $date, 'after');
		$this->db->from('mst_pasien');
		$count = $this->db->count_all_results();
		$seq = $count + 1;
		return $prefix . $date . sprintf('%04d', $seq);
	}

	public function tambahpasien() {
		$data = array(
			'title' => 'Tambah Pasien',
			'no_rm' => $this->generate_no_rm()
		);
		// gunakan form pasien/create sebagai form pendaftaran
		$this->load->view('pasien/create', $data);
	}

	public function simpan() {
		$this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required|trim');
		$this->form_validation->set_rules('no_ktp', 'NIK', 'required|trim|min_length[8]');

		if ($this->form_validation->run() == FALSE) {
			$this->tambahpasien();
			return;
		}

		// Map nilai dari form ke schema mst_pasien
		$jenisKelaminOpt = $this->input->post('jenis_kelamin');
		$jenisKelamin = ($jenisKelaminOpt == '2') ? 'P' : 'L';

		$no_rm = $this->input->post('no_rekam_medis');
		if (!$no_rm) { $no_rm = $this->generate_no_rm(); }

		$pasien = array(
			'id_pasien' => uniqid('PSN'),
			'no_rm' => $no_rm,
			'nik' => $this->input->post('no_ktp'),
			'nama_lengkap' => $this->input->post('nama_lengkap'),
			'nama_panggilan' => $this->input->post('nama_panggilan'),
			'jenis_kelamin' => $jenisKelamin,
			'tempat_lahir' => $this->input->post('tempat_lahir'),
			'tanggal_lahir' => $this->input->post('tanggal_lahir'),
			'agama' => $this->input->post('agama'),
			'suku_bangsa' => $this->input->post('suku'),
			'alamat_ktp' => $this->input->post('alamat_identitas'),
			'rt_ktp' => $this->input->post('rt_identitas'),
			'rw_ktp' => $this->input->post('rw_identitas'),
			'kelurahan_ktp' => $this->input->post('kelurahan_identitas'),
			'kecamatan_ktp' => $this->input->post('kecamatan_identitas'),
			'kota_ktp' => $this->input->post('kabupaten_identitas'),
			'provinsi_ktp' => $this->input->post('provinsi_identitas'),
			'kode_pos_ktp' => $this->input->post('kode_pos_identitas'),
			'alamat_domisili' => $this->input->post('alamat_domisili'),
			'rt_domisili' => $this->input->post('rt_domisili'),
			'rw_domisili' => $this->input->post('rw_domisili'),
			'kelurahan_domisili' => $this->input->post('kelurahan_domisili'),
			'kecamatan_domisili' => $this->input->post('kecamatan_domisili'),
			'kota_domisili' => $this->input->post('kabupaten_domisili'),
			'provinsi_domisili' => $this->input->post('provinsi_domisili'),
			'kode_pos_domisili' => $this->input->post('kode_pos_domisili'),
			'no_telepon' => $this->input->post('telepon_rumah'),
			'no_hp' => $this->input->post('telepon_seluler'),
			'golongan_darah' => $this->input->post('golongan_darah') ?: 'A',
			'rhesus' => '+',
			'status_pasien' => 'Aktif',
			'created_at' => date('Y-m-d H:i:s'),
			'created_by' => $this->session->userdata('user_id')
		);

		// Validasi NIK tidak duplikat dengan model
		if (!$this->Pasien_model->validate_nik($pasien['nik'])) {
			$this->session->set_flashdata('error', 'NIK sudah terdaftar');
			$this->tambahpasien();
			return;
		}

		$this->db->insert('mst_pasien', $pasien);

		$this->session->set_flashdata('success', 'Pasien berhasil disimpan');
		redirect('pasien');
	}

	public function tampilpasien() {
		$data = array('title' => 'Tampil Pasien');
		$this->load->view('pendaftaran/tampilpasien', $data);
	}

	public function editpasien() {
		$data = array('title' => 'Edit Pasien');
		$this->load->view('pendaftaran/editpasien', $data);
	}

	public function hapuspasien() {
		$data = array('title' => 'Hapus Pasien');
		$this->load->view('pendaftaran/hapuspasien', $data);
	}
}
