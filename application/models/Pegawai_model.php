<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all employees with pagination
    public function get_all_pegawai($limit = null, $offset = null, $search = null) {
        $this->db->select('*');
        $this->db->from('mst_pegawai');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('jabatan', $search);
            $this->db->or_like('spesialisasi', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status_pegawai', 'Aktif');
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit && $offset) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    // Get employee by ID
    public function get_pegawai_by_id($id_pegawai) {
        return $this->db->get_where('mst_pegawai', array('id_pegawai' => $id_pegawai))->row();
    }

    // Get employee by NIK
    public function get_pegawai_by_nik($nik) {
        return $this->db->get_where('mst_pegawai', array('nik' => $nik))->row();
    }

    // Get doctors only
    public function get_doctors($limit = null, $search = null) {
        $this->db->select('*');
        $this->db->from('mst_pegawai');
        $this->db->where('jabatan', 'Dokter');
        $this->db->where('status_pegawai', 'Aktif');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('spesialisasi', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('nama_lengkap', 'ASC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    // Get employees by position
    public function get_pegawai_by_jabatan($jabatan) {
        $this->db->where('jabatan', $jabatan);
        $this->db->where('status_pegawai', 'Aktif');
        $this->db->order_by('nama_lengkap', 'ASC');
        return $this->db->get('mst_pegawai')->result();
    }

    // Insert new employee
    public function insert_pegawai($data) {
        $this->db->trans_start();
        
        // Generate ID Pegawai
        $data['id_pegawai'] = $this->generate_id_pegawai();
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert('mst_pegawai', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_pegawai', $data['id_pegawai'], 'INSERT', null, $data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        
        return $data['id_pegawai'];
    }

    // Update employee
    public function update_pegawai($id_pegawai, $data) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_pegawai_by_id($id_pegawai);
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->userdata('user_id');
        
        $this->db->where('id_pegawai', $id_pegawai);
        $this->db->update('mst_pegawai', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_pegawai', $id_pegawai, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Soft delete employee
    public function delete_pegawai($id_pegawai) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_pegawai_by_id($id_pegawai);
        
        $data = array(
            'status_pegawai' => 'Non Aktif',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_pegawai', $id_pegawai);
        $this->db->update('mst_pegawai', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_pegawai', $id_pegawai, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Count total employees
    public function count_pegawai($search = null) {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('jabatan', $search);
            $this->db->or_like('spesialisasi', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status_pegawai', 'Aktif');
        return $this->db->count_all_results('mst_pegawai');
    }

    // Generate ID Pegawai
    private function generate_id_pegawai() {
        $prefix = 'PEG';
        $date = date('Ymd');
        
        $this->db->select('id_pegawai');
        $this->db->from('mst_pegawai');
        $this->db->like('id_pegawai', $prefix . $date, 'after');
        $this->db->order_by('id_pegawai', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_id = $result->id_pegawai;
            $last_number = intval(substr($last_id, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . $date . sprintf('%04d', $new_number);
    }

    // Log audit trail
    private function log_audit_trail($table_name, $record_id, $action, $old_values = null, $new_values = null) {
        $audit_data = array(
            'table_name' => $table_name,
            'record_id' => $record_id,
            'action' => $action,
            'old_values' => $old_values ? json_encode($old_values) : null,
            'new_values' => $new_values ? json_encode($new_values) : null,
            'user_id' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        );
        
        $this->db->insert('audit_trail', $audit_data);
    }

    // Get employee statistics
    public function get_pegawai_statistics() {
        $stats = array();
        
        // Total employees
        $this->db->where('status_pegawai', 'Aktif');
        $stats['total_pegawai'] = $this->db->count_all_results('mst_pegawai');
        
        // Employees by position
        $this->db->select('jabatan, COUNT(*) as total');
        $this->db->from('mst_pegawai');
        $this->db->where('status_pegawai', 'Aktif');
        $this->db->group_by('jabatan');
        $stats['by_position'] = $this->db->get()->result();
        
        // Employees by gender
        $this->db->select('jenis_kelamin, COUNT(*) as total');
        $this->db->from('mst_pegawai');
        $this->db->where('status_pegawai', 'Aktif');
        $this->db->group_by('jenis_kelamin');
        $stats['by_gender'] = $this->db->get()->result();
        
        // New employees this month
        $this->db->where('status_pegawai', 'Aktif');
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        $stats['new_this_month'] = $this->db->count_all_results('mst_pegawai');
        
        return $stats;
    }

    // Validate NIK
    public function validate_nik($nik, $exclude_id = null) {
        $this->db->where('nik', $nik);
        if ($exclude_id) {
            $this->db->where('id_pegawai !=', $exclude_id);
        }
        return $this->db->count_all_results('mst_pegawai') == 0;
    }

    // Get employee performance (for doctors)
    public function get_doctor_performance($id_dokter, $bulan = null, $tahun = null) {
        if (!$bulan) $bulan = date('m');
        if (!$tahun) $tahun = date('Y');
        
        $this->db->select('COUNT(*) as total_pasien, COUNT(DISTINCT id_pasien) as pasien_unik');
        $this->db->from('rekam_medis');
        $this->db->where('id_dokter', $id_dokter);
        $this->db->where('MONTH(tanggal_kunjungan)', $bulan);
        $this->db->where('YEAR(tanggal_kunjungan)', $tahun);
        $this->db->where('status_rm', 'Selesai');
        
        return $this->db->get()->row();
    }
}

