<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pasien_model');
        $this->load->model('Rekam_medis_model');
        $this->load->model('Pegawai_model');
        $this->load->model('Poliklinik_model');
        $this->load->model('Obat_model');
        $this->load->model('User_model');
        $this->load->helper('url');
        
        // Check if user is logged in
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'Dashboard - Sistem RME';
        $data['user'] = $this->session->userdata();
        
        // Get statistics
        $data['stats'] = $this->get_dashboard_statistics();
        
        // Get recent activities
        $data['recent_activities'] = $this->get_recent_activities();
        
        // Get charts data
        $data['charts'] = $this->get_charts_data();
        
        $this->load->view('dashboard/index', $data);
    }

    private function get_dashboard_statistics() {
        $stats = array();
        
        // Patient statistics
        $pasien_stats = $this->Pasien_model->get_pasien_statistics();
        $stats['total_pasien'] = $pasien_stats['total_pasien'];
        $stats['pasien_baru_bulan_ini'] = $pasien_stats['new_this_month'];
        
        // Medical record statistics
        $rm_stats = $this->Rekam_medis_model->get_rekam_medis_statistics();
        $stats['total_rm'] = $rm_stats['total_rm'];
        $stats['rm_hari_ini'] = $rm_stats['today'];
        $stats['rm_bulan_ini'] = $rm_stats['this_month'];
        
        // Employee statistics
        $pegawai_stats = $this->Pegawai_model->get_pegawai_statistics();
        $stats['total_pegawai'] = $pegawai_stats['total_pegawai'];
        
        // Medicine statistics
        $obat_stats = $this->Obat_model->get_obat_statistics();
        $stats['total_obat'] = $obat_stats['total_obat'];
        $stats['obat_stok_rendah'] = $obat_stats['stok_rendah'];
        $stats['obat_expired'] = $obat_stats['expired'];
        
        // Clinic statistics
        $poliklinik_stats = $this->Poliklinik_model->get_poliklinik_statistics();
        $stats['total_poliklinik'] = $poliklinik_stats['total_poliklinik'];
        
        return $stats;
    }

    private function get_recent_activities() {
        $activities = array();
        
        // Recent medical records
        $this->db->select('rm.*, p.nama_lengkap, pol.nama_poliklinik, d.nama_lengkap as nama_dokter');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_pasien p', 'rm.id_pasien = p.id_pasien', 'left');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        $this->db->order_by('rm.created_at', 'DESC');
        $this->db->limit(10);
        $activities['rekam_medis'] = $this->db->get()->result();
        
        // Recent patients
        $this->db->select('*');
        $this->db->from('mst_pasien');
        $this->db->where('status_pasien', 'Aktif');
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(5);
        $activities['pasien'] = $this->db->get()->result();
        
        return $activities;
    }

    private function get_charts_data() {
        $charts = array();
        
        // Patient visits by month (last 12 months)
        $this->db->select('DATE_FORMAT(tanggal_kunjungan, "%Y-%m") as bulan, COUNT(*) as total');
        $this->db->from('rekam_medis');
        $this->db->where('tanggal_kunjungan >=', date('Y-m-01', strtotime('-11 months')));
        $this->db->group_by('DATE_FORMAT(tanggal_kunjungan, "%Y-%m")');
        $this->db->order_by('bulan', 'ASC');
        $charts['kunjungan_bulanan'] = $this->db->get()->result();
        
        // Patient visits by clinic
        $this->db->select('pol.nama_poliklinik, COUNT(rm.id_rm) as total');
        $this->db->from('mst_poliklinik pol');
        $this->db->join('rekam_medis rm', 'pol.id_poliklinik = rm.id_poliklinik', 'left');
        $this->db->where('pol.status_poliklinik', 'Aktif');
        $this->db->where('rm.tanggal_kunjungan >=', date('Y-m-01'));
        $this->db->group_by('pol.id_poliklinik');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);
        $charts['kunjungan_poliklinik'] = $this->db->get()->result();
        
        // Top diagnoses
        $this->db->select('kode_icd10_utama, diagnosis_utama, COUNT(*) as total');
        $this->db->from('rekam_medis');
        $this->db->where('kode_icd10_utama IS NOT NULL');
        $this->db->where('kode_icd10_utama !=', '');
        $this->db->where('tanggal_kunjungan >=', date('Y-m-01'));
        $this->db->group_by('kode_icd10_utama');
        $this->db->order_by('total', 'DESC');
        $this->db->limit(10);
        $charts['diagnosis_terbanyak'] = $this->db->get()->result();
        
        // Patient age distribution
        $this->db->select('
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 18 THEN "0-17"
                WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 30 THEN "18-30"
                WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 31 AND 45 THEN "31-45"
                WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 46 AND 60 THEN "46-60"
                ELSE "60+"
            END as kelompok_umur,
            COUNT(*) as total
        ');
        $this->db->from('mst_pasien');
        $this->db->where('status_pasien', 'Aktif');
        $this->db->group_by('kelompok_umur');
        $this->db->order_by('kelompok_umur', 'ASC');
        $charts['distribusi_umur'] = $this->db->get()->result();
        
        return $charts;
    }

    public function get_chart_data() {
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
            return;
        }

        $chart_type = $this->input->get('type');
        $data = array();

        switch ($chart_type) {
            case 'kunjungan_bulanan':
                $data = $this->get_charts_data()['kunjungan_bulanan'];
                break;
            case 'kunjungan_poliklinik':
                $data = $this->get_charts_data()['kunjungan_poliklinik'];
                break;
            case 'diagnosis_terbanyak':
                $data = $this->get_charts_data()['diagnosis_terbanyak'];
                break;
            case 'distribusi_umur':
                $data = $this->get_charts_data()['distribusi_umur'];
                break;
            default:
                $this->output->set_status_header(400);
                echo json_encode(array('status' => 'error', 'message' => 'Invalid chart type'));
                return;
        }

        echo json_encode(array('status' => 'success', 'data' => $data));
    }

    public function get_notifications() {
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(array('status' => 'error', 'message' => 'Unauthorized'));
            return;
        }

        $notifications = array();

        // Low stock medicines
        $stok_rendah = $this->Obat_model->get_obat_stok_rendah();
        if (count($stok_rendah) > 0) {
            $notifications[] = array(
                'type' => 'warning',
                'title' => 'Stok Obat Rendah',
                'message' => 'Ada ' . count($stok_rendah) . ' obat dengan stok rendah',
                'count' => count($stok_rendah)
            );
        }

        // Expired medicines
        $obat_expired = $this->Obat_model->get_obat_expired();
        if (count($obat_expired) > 0) {
            $notifications[] = array(
                'type' => 'danger',
                'title' => 'Obat Expired',
                'message' => 'Ada ' . count($obat_expired) . ' obat yang sudah expired',
                'count' => count($obat_expired)
            );
        }

        // Today's appointments (if you have appointment system)
        $this->db->where('tanggal_kunjungan', date('Y-m-d'));
        $this->db->where('status_rm', 'Draft');
        $appointments_today = $this->db->count_all_results('rekam_medis');
        
        if ($appointments_today > 0) {
            $notifications[] = array(
                'type' => 'info',
                'title' => 'Kunjungan Hari Ini',
                'message' => 'Ada ' . $appointments_today . ' kunjungan yang belum selesai',
                'count' => $appointments_today
            );
        }

        echo json_encode(array('status' => 'success', 'notifications' => $notifications));
    }

    public function export_statistics() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Check permission
        if (!$this->User_model->has_permission($this->session->userdata('role'), 'laporan', 'read')) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk melihat laporan!');
            redirect('dashboard');
        }

        $this->load->library('excel');
        
        $data = $this->get_dashboard_statistics();
        $charts = $this->get_charts_data();
        
        // Create Excel file
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("Statistik Dashboard RME");
        
        // Sheet 1: Summary Statistics
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('Ringkasan Statistik');
        
        $row = 1;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Statistik Dashboard RME');
        $objPHPExcel->getActiveSheet()->mergeCells('A' . $row . ':B' . $row);
        $objPHPExcel->getActiveSheet()->getStyle('A' . $row)->getFont()->setBold(true);
        
        $row = 3;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Total Pasien');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['total_pasien']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Total Rekam Medis');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['total_rm']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Kunjungan Hari Ini');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['rm_hari_ini']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Kunjungan Bulan Ini');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['rm_bulan_ini']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Total Pegawai');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['total_pegawai']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Total Obat');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['total_obat']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Obat Stok Rendah');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['obat_stok_rendah']);
        $row++;
        
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, 'Obat Expired');
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $data['obat_expired']);
        
        // Auto-size columns
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        
        // Set filename
        $filename = 'Statistik_Dashboard_RME_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Redirect output to a client's web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}

