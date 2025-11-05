<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pasien_model');
        $this->load->model('Rekam_medis_model');
        $this->load->model('Pegawai_model');
        $this->load->model('Poliklinik_model');
        $this->load->model('Obat_model');
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('url');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $data['title'] = 'Laporan - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        $this->load->view('laporan/index', $data);
    }

    public function pasien() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $data['title'] = 'Laporan Pasien - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'jenis_kelamin' => $this->input->get('jenis_kelamin'),
            'status_pasien' => $this->input->get('status_pasien')
        );
        
        $data['filter'] = $filter;
        $data['pasien'] = $this->get_pasien_report($filter);
        
        $this->load->view('laporan/pasien', $data);
    }

    public function rekam_medis() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $data['title'] = 'Laporan Rekam Medis - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'id_poliklinik' => $this->input->get('id_poliklinik'),
            'id_dokter' => $this->input->get('id_dokter'),
            'status_rm' => $this->input->get('status_rm')
        );
        
        $data['filter'] = $filter;
        $data['poliklinik'] = $this->Poliklinik_model->get_all_poliklinik();
        $data['dokter'] = $this->Pegawai_model->get_doctors();
        $data['rekam_medis'] = $this->get_rekam_medis_report($filter);
        
        $this->load->view('laporan/rekam_medis', $data);
    }

    public function kunjungan() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $data['title'] = 'Laporan Kunjungan - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'id_poliklinik' => $this->input->get('id_poliklinik'),
            'group_by' => $this->input->get('group_by') ?: 'hari'
        );
        
        $data['filter'] = $filter;
        $data['poliklinik'] = $this->Poliklinik_model->get_all_poliklinik();
        $data['kunjungan'] = $this->get_kunjungan_report($filter);
        
        $this->load->view('laporan/kunjungan', $data);
    }

    public function diagnosis() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $data['title'] = 'Laporan Diagnosis - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'id_poliklinik' => $this->input->get('id_poliklinik'),
            'limit' => $this->input->get('limit') ?: 20
        );
        
        $data['filter'] = $filter;
        $data['poliklinik'] = $this->Poliklinik_model->get_all_poliklinik();
        $data['diagnosis'] = $this->get_diagnosis_report($filter);
        
        $this->load->view('laporan/diagnosis', $data);
    }

    public function obat() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $data['title'] = 'Laporan Obat - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $filter = array(
            'kategori_obat' => $this->input->get('kategori_obat'),
            'status_stok' => $this->input->get('status_stok'),
            'expired' => $this->input->get('expired')
        );
        
        $data['filter'] = $filter;
        $data['kategori_obat'] = $this->Obat_model->get_kategori_obat();
        $data['obat'] = $this->get_obat_report($filter);
        
        $this->load->view('laporan/obat', $data);
    }

    public function audit_trail() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'audit_trail', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat audit trail!');
            redirect('dashboard');
        }

        $data['title'] = 'Audit Trail - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get filter parameters
        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'table_name' => $this->input->get('table_name'),
            'action' => $this->input->get('action'),
            'user_id' => $this->input->get('user_id')
        );
        
        $data['filter'] = $filter;
        $data['audit_trail'] = $this->get_audit_trail_report($filter);
        
        $this->load->view('laporan/audit_trail', $data);
    }

    public function export_pasien() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport laporan!');
            redirect('laporan');
        }

        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'jenis_kelamin' => $this->input->get('jenis_kelamin'),
            'status_pasien' => $this->input->get('status_pasien')
        );

        $this->export_to_excel('Laporan Pasien', $this->get_pasien_report($filter), 'pasien');
    }

    public function export_rekam_medis() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport laporan!');
            redirect('laporan');
        }

        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'id_poliklinik' => $this->input->get('id_poliklinik'),
            'id_dokter' => $this->input->get('id_dokter'),
            'status_rm' => $this->input->get('status_rm')
        );

        $this->export_to_excel('Laporan Rekam Medis', $this->get_rekam_medis_report($filter), 'rekam_medis');
    }

    public function export_kunjungan() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport laporan!');
            redirect('laporan');
        }

        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'id_poliklinik' => $this->input->get('id_poliklinik'),
            'group_by' => $this->input->get('group_by') ?: 'hari'
        );

        $this->export_to_excel('Laporan Kunjungan', $this->get_kunjungan_report($filter), 'kunjungan');
    }

    public function export_diagnosis() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport laporan!');
            redirect('laporan');
        }

        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'id_poliklinik' => $this->input->get('id_poliklinik'),
            'limit' => $this->input->get('limit') ?: 20
        );

        $this->export_to_excel('Laporan Diagnosis', $this->get_diagnosis_report($filter), 'diagnosis');
    }

    public function export_obat() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport laporan!');
            redirect('laporan');
        }

        $filter = array(
            'kategori_obat' => $this->input->get('kategori_obat'),
            'status_stok' => $this->input->get('status_stok'),
            'expired' => $this->input->get('expired')
        );

        $this->export_to_excel('Laporan Obat', $this->get_obat_report($filter), 'obat');
    }

    public function export_audit_trail() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'audit_trail', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport audit trail!');
            redirect('laporan');
        }

        $filter = array(
            'tanggal_dari' => $this->input->get('tanggal_dari'),
            'tanggal_sampai' => $this->input->get('tanggal_sampai'),
            'table_name' => $this->input->get('table_name'),
            'action' => $this->input->get('action'),
            'user_id' => $this->input->get('user_id')
        );

        $this->export_to_excel('Audit Trail', $this->get_audit_trail_report($filter), 'audit_trail');
    }

    private function get_pasien_report($filter) {
        $this->db->select('*');
        $this->db->from('mst_pasien');
        
        if ($filter['tanggal_dari']) {
            $this->db->where('created_at >=', $filter['tanggal_dari']);
        }
        if ($filter['tanggal_sampai']) {
            $this->db->where('created_at <=', $filter['tanggal_sampai']);
        }
        if ($filter['jenis_kelamin']) {
            $this->db->where('jenis_kelamin', $filter['jenis_kelamin']);
        }
        if ($filter['status_pasien']) {
            $this->db->where('status_pasien', $filter['status_pasien']);
        }
        
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get()->result();
    }

    private function get_rekam_medis_report($filter) {
        $this->db->select('rm.*, p.nama_lengkap, p.no_rm, p.nik, pol.nama_poliklinik, d.nama_lengkap as nama_dokter');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_pasien p', 'rm.id_pasien = p.id_pasien', 'left');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        
        if ($filter['tanggal_dari']) {
            $this->db->where('rm.tanggal_kunjungan >=', $filter['tanggal_dari']);
        }
        if ($filter['tanggal_sampai']) {
            $this->db->where('rm.tanggal_kunjungan <=', $filter['tanggal_sampai']);
        }
        if ($filter['id_poliklinik']) {
            $this->db->where('rm.id_poliklinik', $filter['id_poliklinik']);
        }
        if ($filter['id_dokter']) {
            $this->db->where('rm.id_dokter', $filter['id_dokter']);
        }
        if ($filter['status_rm']) {
            $this->db->where('rm.status_rm', $filter['status_rm']);
        }
        
        $this->db->order_by('rm.tanggal_kunjungan', 'DESC');
        return $this->db->get()->result();
    }

    private function get_kunjungan_report($filter) {
        $this->db->select('
            DATE(rm.tanggal_kunjungan) as tanggal,
            pol.nama_poliklinik,
            COUNT(*) as total_kunjungan,
            COUNT(DISTINCT rm.id_pasien) as pasien_unik
        ');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        
        if ($filter['tanggal_dari']) {
            $this->db->where('rm.tanggal_kunjungan >=', $filter['tanggal_dari']);
        }
        if ($filter['tanggal_sampai']) {
            $this->db->where('rm.tanggal_kunjungan <=', $filter['tanggal_sampai']);
        }
        if ($filter['id_poliklinik']) {
            $this->db->where('rm.id_poliklinik', $filter['id_poliklinik']);
        }
        
        if ($filter['group_by'] == 'hari') {
            $this->db->group_by('DATE(rm.tanggal_kunjungan)');
        } elseif ($filter['group_by'] == 'bulan') {
            $this->db->group_by('DATE_FORMAT(rm.tanggal_kunjungan, "%Y-%m")');
        } elseif ($filter['group_by'] == 'poliklinik') {
            $this->db->group_by('rm.id_poliklinik');
        }
        
        $this->db->order_by('tanggal', 'DESC');
        return $this->db->get()->result();
    }

    private function get_diagnosis_report($filter) {
        $this->db->select('
            rm.kode_icd10_utama,
            rm.diagnosis_utama,
            COUNT(*) as total,
            pol.nama_poliklinik
        ');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        
        if ($filter['tanggal_dari']) {
            $this->db->where('rm.tanggal_kunjungan >=', $filter['tanggal_dari']);
        }
        if ($filter['tanggal_sampai']) {
            $this->db->where('rm.tanggal_kunjungan <=', $filter['tanggal_sampai']);
        }
        if ($filter['id_poliklinik']) {
            $this->db->where('rm.id_poliklinik', $filter['id_poliklinik']);
        }
        
        $this->db->where('rm.kode_icd10_utama IS NOT NULL');
        $this->db->where('rm.kode_icd10_utama !=', '');
        $this->db->group_by('rm.kode_icd10_utama');
        $this->db->order_by('total', 'DESC');
        $this->db->limit($filter['limit']);
        
        return $this->db->get()->result();
    }

    private function get_obat_report($filter) {
        $this->db->select('*');
        $this->db->from('mst_obat');
        
        if ($filter['kategori_obat']) {
            $this->db->where('kategori_obat', $filter['kategori_obat']);
        }
        if ($filter['status_stok'] == 'rendah') {
            $this->db->where('stok_aktual <= stok_minimal');
        } elseif ($filter['status_stok'] == 'habis') {
            $this->db->where('stok_aktual', 0);
        }
        if ($filter['expired'] == 'ya') {
            $this->db->where('expired_date <=', date('Y-m-d'));
        }
        
        $this->db->where('status_obat', 'Aktif');
        $this->db->order_by('nama_obat', 'ASC');
        return $this->db->get()->result();
    }

    private function get_audit_trail_report($filter) {
        $this->db->select('at.*, u.username');
        $this->db->from('audit_trail at');
        $this->db->join('users u', 'at.user_id = u.id_user', 'left');
        
        if ($filter['tanggal_dari']) {
            $this->db->where('at.created_at >=', $filter['tanggal_dari']);
        }
        if ($filter['tanggal_sampai']) {
            $this->db->where('at.created_at <=', $filter['tanggal_sampai']);
        }
        if ($filter['table_name']) {
            $this->db->where('at.table_name', $filter['table_name']);
        }
        if ($filter['action']) {
            $this->db->where('at.action', $filter['action']);
        }
        if ($filter['user_id']) {
            $this->db->where('at.user_id', $filter['user_id']);
        }
        
        $this->db->order_by('at.created_at', 'DESC');
        return $this->db->get()->result();
    }

    private function export_to_excel($title, $data, $type) {
        $this->load->library('excel');
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle($title);
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle($title);
        
        // Set headers based on report type
        $headers = $this->get_report_headers($type);
        
        // Write headers
        $col = 'A';
        foreach ($headers as $header) {
            $objPHPExcel->getActiveSheet()->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Write data
        $row = 2;
        foreach ($data as $item) {
            $col = 'A';
            $row_data = $this->get_report_row_data($item, $type);
            foreach ($row_data as $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        // Auto-size columns
        $last_column = $objPHPExcel->getActiveSheet()->getHighestColumn();
        for ($col = 'A'; $col <= $last_column; $col++) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Set filename
        $filename = $title . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    private function get_report_headers($type) {
        switch ($type) {
            case 'pasien':
                return array(
                    'No RM', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
                    'Alamat KTP', 'No Telepon', 'No HP', 'Golongan Darah', 'Rhesus', 'Alergi', 'Penyakit Kronis'
                );
            case 'rekam_medis':
                return array(
                    'ID RM', 'No RM', 'Nama Pasien', 'NIK', 'Poliklinik', 'Dokter', 'Tanggal Kunjungan',
                    'Jam Kunjungan', 'Jenis Kunjungan', 'Keluhan Utama', 'Diagnosis Utama', 'Kode ICD10', 'Status'
                );
            case 'kunjungan':
                return array(
                    'Tanggal', 'Poliklinik', 'Total Kunjungan', 'Pasien Unik'
                );
            case 'diagnosis':
                return array(
                    'Kode ICD10', 'Diagnosis', 'Total', 'Poliklinik'
                );
            case 'obat':
                return array(
                    'Kode Obat', 'Nama Obat', 'Nama Generik', 'Kategori', 'Satuan', 'Harga Beli', 'Harga Jual',
                    'Stok Minimal', 'Stok Aktual', 'Expired Date'
                );
            case 'audit_trail':
                return array(
                    'Tanggal', 'Tabel', 'Record ID', 'Aksi', 'User', 'IP Address', 'User Agent'
                );
            default:
                return array();
        }
    }

    private function get_report_row_data($item, $type) {
        switch ($type) {
            case 'pasien':
                return array(
                    $item->no_rm,
                    $item->nik,
                    $item->nama_lengkap,
                    $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    $item->tempat_lahir,
                    $item->tanggal_lahir,
                    $item->alamat_ktp,
                    $item->no_telepon,
                    $item->no_hp,
                    $item->golongan_darah,
                    $item->rhesus,
                    $item->alergi,
                    $item->penyakit_kronis
                );
            case 'rekam_medis':
                return array(
                    $item->id_rm,
                    $item->no_rm,
                    $item->nama_lengkap,
                    $item->nik,
                    $item->nama_poliklinik,
                    $item->nama_dokter,
                    $item->tanggal_kunjungan,
                    $item->jam_kunjungan,
                    $item->jenis_kunjungan,
                    $item->keluhan_utama,
                    $item->diagnosis_utama,
                    $item->kode_icd10_utama,
                    $item->status_rm
                );
            case 'kunjungan':
                return array(
                    $item->tanggal,
                    $item->nama_poliklinik,
                    $item->total_kunjungan,
                    $item->pasien_unik
                );
            case 'diagnosis':
                return array(
                    $item->kode_icd10_utama,
                    $item->diagnosis_utama,
                    $item->total,
                    $item->nama_poliklinik
                );
            case 'obat':
                return array(
                    $item->kode_obat,
                    $item->nama_obat,
                    $item->nama_generik,
                    $item->kategori_obat,
                    $item->satuan,
                    $item->harga_beli,
                    $item->harga_jual,
                    $item->stok_minimal,
                    $item->stok_aktual,
                    $item->expired_date
                );
            case 'audit_trail':
                return array(
                    $item->created_at,
                    $item->table_name,
                    $item->record_id,
                    $item->action,
                    $item->username,
                    $item->ip_address,
                    $item->user_agent
                );
            default:
                return array();
        }
    }
}

