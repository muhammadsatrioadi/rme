<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all patients with pagination
    public function get_all_pasien($limit = null, $offset = null, $search = null) {
        $this->db->select('*');
        $this->db->from('mst_pasien');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('no_rm', $search);
            $this->db->or_like('no_telepon', $search);
            $this->db->or_like('no_hp', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status_pasien', 'Aktif');
        $this->db->order_by('created_at', 'DESC');
        
        if ($limit && $offset) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    // Get patient by ID
    public function get_pasien_by_id($id_pasien) {
        return $this->db->get_where('mst_pasien', array('id_pasien' => $id_pasien))->row();
    }

    // Get patient by NIK
    public function get_pasien_by_nik($nik) {
        return $this->db->get_where('mst_pasien', array('nik' => $nik))->row();
    }

    // Get patient by No RM
    public function get_pasien_by_no_rm($no_rm) {
        return $this->db->get_where('mst_pasien', array('no_rm' => $no_rm))->row();
    }

    // Insert new patient
    public function insert_pasien($data) {
        $this->db->trans_start();
        
        // Generate ID Pasien
        $data['id_pasien'] = $this->generate_id_pasien();
        
        // Generate No RM
        if (empty($data['no_rm'])) {
            $data['no_rm'] = $this->generate_no_rm();
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert('mst_pasien', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_pasien', $data['id_pasien'], 'INSERT', null, $data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        
        return $data['id_pasien'];
    }

    // Update patient
    public function update_pasien($id_pasien, $data) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_pasien_by_id($id_pasien);
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->userdata('user_id');
        
        $this->db->where('id_pasien', $id_pasien);
        $this->db->update('mst_pasien', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_pasien', $id_pasien, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Soft delete patient
    public function delete_pasien($id_pasien) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_pasien_by_id($id_pasien);
        
        $data = array(
            'status_pasien' => 'Non Aktif',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_pasien', $id_pasien);
        $this->db->update('mst_pasien', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_pasien', $id_pasien, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Count total patients
    public function count_pasien($search = null) {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_lengkap', $search);
            $this->db->or_like('nik', $search);
            $this->db->or_like('no_rm', $search);
            $this->db->or_like('no_telepon', $search);
            $this->db->or_like('no_hp', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status_pasien', 'Aktif');
        return $this->db->count_all_results('mst_pasien');
    }

    // Generate ID Pasien
    private function generate_id_pasien() {
        $prefix = 'PAS';
        $date = date('Ymd');
        
        $this->db->select('id_pasien');
        $this->db->from('mst_pasien');
        $this->db->like('id_pasien', $prefix . $date, 'after');
        $this->db->order_by('id_pasien', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_id = $result->id_pasien;
            $last_number = intval(substr($last_id, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . $date . sprintf('%04d', $new_number);
    }

    // Generate No RM
    private function generate_no_rm() {
        $year = date('Y');
        
        $this->db->select('no_rm');
        $this->db->from('mst_pasien');
        $this->db->like('no_rm', $year, 'after');
        $this->db->order_by('no_rm', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_rm = $result->no_rm;
            $last_number = intval(substr($last_rm, -6));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $year . sprintf('%06d', $new_number);
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

    // Get patient statistics
    public function get_pasien_statistics() {
        $stats = array();
        
        // Total patients
        $this->db->where('status_pasien', 'Aktif');
        $stats['total_pasien'] = $this->db->count_all_results('mst_pasien');
        
        // Patients by gender
        $this->db->select('jenis_kelamin, COUNT(*) as total');
        $this->db->from('mst_pasien');
        $this->db->where('status_pasien', 'Aktif');
        $this->db->group_by('jenis_kelamin');
        $stats['by_gender'] = $this->db->get()->result();
        
        // New patients this month
        $this->db->where('status_pasien', 'Aktif');
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        $stats['new_this_month'] = $this->db->count_all_results('mst_pasien');
        
        return $stats;
    }

    // Validate NIK
    public function validate_nik($nik, $exclude_id = null) {
        $this->db->where('nik', $nik);
        if ($exclude_id) {
            $this->db->where('id_pasien !=', $exclude_id);
        }
        return $this->db->count_all_results('mst_pasien') == 0;
    }

    // Validate No RM
    public function validate_no_rm($no_rm, $exclude_id = null) {
        $this->db->where('no_rm', $no_rm);
        if ($exclude_id) {
            $this->db->where('id_pasien !=', $exclude_id);
        }
        return $this->db->count_all_results('mst_pasien') == 0;
    }
}

