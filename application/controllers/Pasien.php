<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pasien_model');
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
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'pasien', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat data pasien!');
            redirect('dashboard');
        }

        $data['title'] = 'Data Pasien - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Pagination
        $this->load->library('pagination');
        $config['base_url'] = base_url('pasien/index');
        $config['total_rows'] = $this->Pasien_model->count_pasien();
        $config['per_page'] = 10;
        $config['uri_segment'] = 3;
        
        $this->pagination->initialize($config);
        
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['pasien'] = $this->Pasien_model->get_all_pasien($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        
        $this->load->view('pasien/index', $data);
    }

    public function create() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'pasien', 'create')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menambah data pasien!');
            redirect('pasien');
        }

        $data['title'] = 'Tambah Pasien - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|exact_length[16]|numeric');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[L,P]');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('alamat_ktp', 'Alamat KTP', 'required|trim');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required|in_list[A,B,AB,O]');
        $this->form_validation->set_rules('rhesus', 'Rhesus', 'required|in_list[+,-]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('pasien/create', $data);
        } else {
            $data_pasien = array(
                'nik' => $this->input->post('nik'),
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'nama_panggilan' => $this->input->post('nama_panggilan'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'agama' => $this->input->post('agama'),
                'suku_bangsa' => $this->input->post('suku_bangsa'),
                'status_perkawinan' => $this->input->post('status_perkawinan'),
                'pendidikan' => $this->input->post('pendidikan'),
                'pekerjaan' => $this->input->post('pekerjaan'),
                'alamat_ktp' => $this->input->post('alamat_ktp'),
                'rt_ktp' => $this->input->post('rt_ktp'),
                'rw_ktp' => $this->input->post('rw_ktp'),
                'kelurahan_ktp' => $this->input->post('kelurahan_ktp'),
                'kecamatan_ktp' => $this->input->post('kecamatan_ktp'),
                'kota_ktp' => $this->input->post('kota_ktp'),
                'provinsi_ktp' => $this->input->post('provinsi_ktp'),
                'kode_pos_ktp' => $this->input->post('kode_pos_ktp'),
                'alamat_domisili' => $this->input->post('alamat_domisili'),
                'rt_domisili' => $this->input->post('rt_domisili'),
                'rw_domisili' => $this->input->post('rw_domisili'),
                'kelurahan_domisili' => $this->input->post('kelurahan_domisili'),
                'kecamatan_domisili' => $this->input->post('kecamatan_domisili'),
                'kota_domisili' => $this->input->post('kota_domisili'),
                'provinsi_domisili' => $this->input->post('provinsi_domisili'),
                'kode_pos_domisili' => $this->input->post('kode_pos_domisili'),
                'no_telepon' => $this->input->post('no_telepon'),
                'no_hp' => $this->input->post('no_hp'),
                'email' => $this->input->post('email'),
                'nama_ayah' => $this->input->post('nama_ayah'),
                'nama_ibu' => $this->input->post('nama_ibu'),
                'nama_suami_istri' => $this->input->post('nama_suami_istri'),
                'golongan_darah' => $this->input->post('golongan_darah'),
                'rhesus' => $this->input->post('rhesus'),
                'alergi' => $this->input->post('alergi'),
                'penyakit_kronis' => $this->input->post('penyakit_kronis'),
                'asuransi' => $this->input->post('asuransi'),
                'no_asuransi' => $this->input->post('no_asuransi')
            );

            // Validate NIK uniqueness
            if (!$this->Pasien_model->validate_nik($data_pasien['nik'])) {
                $this->session->set_flashdata('error', 'NIK sudah terdaftar!');
                $this->load->view('pasien/create', $data);
                return;
            }

            if ($this->Pasien_model->insert_pasien($data_pasien)) {
                $this->session->set_flashdata('success', 'Data pasien berhasil ditambahkan!');
                redirect('pasien');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data pasien!');
                $this->load->view('pasien/create', $data);
            }
        }
    }

    public function edit($id_pasien) {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'pasien', 'update')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengubah data pasien!');
            redirect('pasien');
        }

        $data['title'] = 'Edit Pasien - Sistem RME';
        $data['user'] = $this->session->userdata();
        $data['pasien'] = $this->Pasien_model->get_pasien_by_id($id_pasien);

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan!');
            redirect('pasien');
        }

        $this->form_validation->set_rules('nik', 'NIK', 'required|trim|exact_length[16]|numeric');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required|in_list[L,P]');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('alamat_ktp', 'Alamat KTP', 'required|trim');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required|in_list[A,B,AB,O]');
        $this->form_validation->set_rules('rhesus', 'Rhesus', 'required|in_list[+,-]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('pasien/edit', $data);
        } else {
            $data_pasien = array(
                'nik' => $this->input->post('nik'),
                'nama_lengkap' => $this->input->post('nama_lengkap'),
                'nama_panggilan' => $this->input->post('nama_panggilan'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'agama' => $this->input->post('agama'),
                'suku_bangsa' => $this->input->post('suku_bangsa'),
                'status_perkawinan' => $this->input->post('status_perkawinan'),
                'pendidikan' => $this->input->post('pendidikan'),
                'pekerjaan' => $this->input->post('pekerjaan'),
                'alamat_ktp' => $this->input->post('alamat_ktp'),
                'rt_ktp' => $this->input->post('rt_ktp'),
                'rw_ktp' => $this->input->post('rw_ktp'),
                'kelurahan_ktp' => $this->input->post('kelurahan_ktp'),
                'kecamatan_ktp' => $this->input->post('kecamatan_ktp'),
                'kota_ktp' => $this->input->post('kota_ktp'),
                'provinsi_ktp' => $this->input->post('provinsi_ktp'),
                'kode_pos_ktp' => $this->input->post('kode_pos_ktp'),
                'alamat_domisili' => $this->input->post('alamat_domisili'),
                'rt_domisili' => $this->input->post('rt_domisili'),
                'rw_domisili' => $this->input->post('rw_domisili'),
                'kelurahan_domisili' => $this->input->post('kelurahan_domisili'),
                'kecamatan_domisili' => $this->input->post('kecamatan_domisili'),
                'kota_domisili' => $this->input->post('kota_domisili'),
                'provinsi_domisili' => $this->input->post('provinsi_domisili'),
                'kode_pos_domisili' => $this->input->post('kode_pos_domisili'),
                'no_telepon' => $this->input->post('no_telepon'),
                'no_hp' => $this->input->post('no_hp'),
                'email' => $this->input->post('email'),
                'nama_ayah' => $this->input->post('nama_ayah'),
                'nama_ibu' => $this->input->post('nama_ibu'),
                'nama_suami_istri' => $this->input->post('nama_suami_istri'),
                'golongan_darah' => $this->input->post('golongan_darah'),
                'rhesus' => $this->input->post('rhesus'),
                'alergi' => $this->input->post('alergi'),
                'penyakit_kronis' => $this->input->post('penyakit_kronis'),
                'asuransi' => $this->input->post('asuransi'),
                'no_asuransi' => $this->input->post('no_asuransi')
            );

            // Validate NIK uniqueness (exclude current record)
            if (!$this->Pasien_model->validate_nik($data_pasien['nik'], $id_pasien)) {
                $this->session->set_flashdata('error', 'NIK sudah terdaftar!');
                $this->load->view('pasien/edit', $data);
                return;
            }

            if ($this->Pasien_model->update_pasien($id_pasien, $data_pasien)) {
                $this->session->set_flashdata('success', 'Data pasien berhasil diupdate!');
                redirect('pasien');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data pasien!');
                $this->load->view('pasien/edit', $data);
            }
        }
    }

    public function view($id_pasien) {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'pasien', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat data pasien!');
            redirect('dashboard');
        }

        $data['title'] = 'Detail Pasien - Sistem RME';
        $data['user'] = $this->session->userdata();
        $data['pasien'] = $this->Pasien_model->get_pasien_by_id($id_pasien);

        if (!$data['pasien']) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan!');
            redirect('pasien');
        }

        // Get medical history
        $this->load->model('Rekam_medis_model');
        $data['riwayat_medis'] = $this->Rekam_medis_model->get_riwayat_medis($id_pasien);

        $this->load->view('pasien/view', $data);
    }

    public function delete($id_pasien) {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'pasien', 'delete')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk menghapus data pasien!');
            redirect('pasien');
        }

        $pasien = $this->Pasien_model->get_pasien_by_id($id_pasien);
        if (!$pasien) {
            $this->session->set_flashdata('error', 'Data pasien tidak ditemukan!');
            redirect('pasien');
        }

        if ($this->Pasien_model->delete_pasien($id_pasien)) {
            $this->session->set_flashdata('success', 'Data pasien berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data pasien!');
        }

        redirect('pasien');
    }

    public function search() {
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
            return;
        }

        $search = $this->input->get('q');
        $pasien = $this->Pasien_model->get_all_pasien(20, 0, $search);

        $results = array();
        foreach ($pasien as $p) {
            $results[] = array(
                'id' => $p->id_pasien,
                'text' => $p->nama_lengkap . ' (' . $p->no_rm . ')',
                'no_rm' => $p->no_rm,
                'nik' => $p->nik,
                'nama_lengkap' => $p->nama_lengkap,
                'jenis_kelamin' => $p->jenis_kelamin,
                'tanggal_lahir' => $p->tanggal_lahir
            );
        }

        echo json_encode(array('results' => $results));
    }

    public function get_by_id($id_pasien) {
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
            return;
        }

        $pasien = $this->Pasien_model->get_pasien_by_id($id_pasien);
        if ($pasien) {
            echo json_encode(array('status' => 'success', 'data' => $pasien));
        } else {
            $this->output->set_status_header(404);
            echo json_encode(array('status' => 'error', 'message' => 'Pasien tidak ditemukan'));
        }
    }

    public function export() {
        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'pasien', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk mengexport data pasien!');
            redirect('pasien');
        }

        $this->load->library('excel');
        
        $pasien = $this->Pasien_model->get_all_pasien();
        
        // Create Excel file
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Data Pasien RME");
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Data Pasien');
        
        // Headers
        $headers = array(
            'No', 'No RM', 'NIK', 'Nama Lengkap', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'Alamat KTP', 'No Telepon', 'No HP', 'Golongan Darah', 'Rhesus', 'Alergi', 'Penyakit Kronis'
        );
        
        $col = 'A';
        foreach ($headers as $header) {
            $objPHPExcel->getActiveSheet()->setCellValue($col . '1', $header);
            $col++;
        }
        
        // Data
        $row = 2;
        $no = 1;
        foreach ($pasien as $p) {
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $no);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $p->no_rm);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $p->nik);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $p->nama_lengkap);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan');
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $p->tempat_lahir);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $p->tanggal_lahir);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $p->alamat_ktp);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $row, $p->no_telepon);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $row, $p->no_hp);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $p->golongan_darah);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $p->rhesus);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $row, $p->alergi);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $row, $p->penyakit_kronis);
            $row++;
            $no++;
        }
        
        // Auto-size columns
        foreach (range('A', 'N') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        
        // Set filename
        $filename = 'Data_Pasien_RME_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}

