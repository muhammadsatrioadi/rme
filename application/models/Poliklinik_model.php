<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poliklinik_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all clinics
    public function get_all_poliklinik($search = null) {
        $this->db->select('p.*, d.nama_lengkap as nama_kepala');
        $this->db->from('mst_poliklinik p');
        $this->db->join('mst_pegawai d', 'p.kepala_poliklinik = d.id_pegawai', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('p.nama_poliklinik', $search);
            $this->db->or_like('p.kode_poliklinik', $search);
            $this->db->or_like('d.nama_lengkap', $search);
            $this->db->group_end();
        }
        
        $this->db->where('p.status_poliklinik', 'Aktif');
        $this->db->order_by('p.nama_poliklinik', 'ASC');
        
        return $this->db->get()->result();
    }

    // Get clinic by ID
    public function get_poliklinik_by_id($id_poliklinik) {
        $this->db->select('p.*, d.nama_lengkap as nama_kepala');
        $this->db->from('mst_poliklinik p');
        $this->db->join('mst_pegawai d', 'p.kepala_poliklinik = d.id_pegawai', 'left');
        $this->db->where('p.id_poliklinik', $id_poliklinik);
        
        return $this->db->get()->row();
    }

    // Get clinic by code
    public function get_poliklinik_by_kode($kode_poliklinik) {
        return $this->db->get_where('mst_poliklinik', array('kode_poliklinik' => $kode_poliklinik))->row();
    }

    // Insert new clinic
    public function insert_poliklinik($data) {
        $this->db->trans_start();
        
        // Generate ID Poliklinik
        $data['id_poliklinik'] = $this->generate_id_poliklinik();
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert('mst_poliklinik', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_poliklinik', $data['id_poliklinik'], 'INSERT', null, $data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        
        return $data['id_poliklinik'];
    }

    // Update clinic
    public function update_poliklinik($id_poliklinik, $data) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_poliklinik_by_id($id_poliklinik);
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->userdata('user_id');
        
        $this->db->where('id_poliklinik', $id_poliklinik);
        $this->db->update('mst_poliklinik', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_poliklinik', $id_poliklinik, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Soft delete clinic
    public function delete_poliklinik($id_poliklinik) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_poliklinik_by_id($id_poliklinik);
        
        $data = array(
            'status_poliklinik' => 'Non Aktif',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_poliklinik', $id_poliklinik);
        $this->db->update('mst_poliklinik', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_poliklinik', $id_poliklinik, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Generate ID Poliklinik
    private function generate_id_poliklinik() {
        $prefix = 'POL';
        
        $this->db->select('id_poliklinik');
        $this->db->from('mst_poliklinik');
        $this->db->like('id_poliklinik', $prefix, 'after');
        $this->db->order_by('id_poliklinik', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_id = $result->id_poliklinik;
            $last_number = intval(substr($last_id, -3));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return $prefix . sprintf('%03d', $new_number);
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

    // Get clinic statistics
    public function get_poliklinik_statistics() {
        $stats = array();
        
        // Total clinics
        $this->db->where('status_poliklinik', 'Aktif');
        $stats['total_poliklinik'] = $this->db->count_all_results('mst_poliklinik');
        
        // Patient visits by clinic
        $this->db->select('p.nama_poliklinik, COUNT(rm.id_rm) as total_kunjungan');
        $this->db->from('mst_poliklinik p');
        $this->db->join('rekam_medis rm', 'p.id_poliklinik = rm.id_poliklinik', 'left');
        $this->db->where('p.status_poliklinik', 'Aktif');
        $this->db->where('rm.tanggal_kunjungan >=', date('Y-m-01'));
        $this->db->group_by('p.id_poliklinik');
        $this->db->order_by('total_kunjungan', 'DESC');
        $stats['kunjungan_bulan_ini'] = $this->db->get()->result();
        
        return $stats;
    }

    // Validate clinic code
    public function validate_kode_poliklinik($kode_poliklinik, $exclude_id = null) {
        $this->db->where('kode_poliklinik', $kode_poliklinik);
        if ($exclude_id) {
            $this->db->where('id_poliklinik !=', $exclude_id);
        }
        return $this->db->count_all_results('mst_poliklinik') == 0;
    }

    // Get clinic performance
    public function get_clinic_performance($id_poliklinik, $bulan = null, $tahun = null) {
        if (!$bulan) $bulan = date('m');
        if (!$tahun) $tahun = date('Y');
        
        $this->db->select('COUNT(*) as total_kunjungan, COUNT(DISTINCT rm.id_pasien) as pasien_unik');
        $this->db->from('rekam_medis rm');
        $this->db->where('rm.id_poliklinik', $id_poliklinik);
        $this->db->where('MONTH(rm.tanggal_kunjungan)', $bulan);
        $this->db->where('YEAR(rm.tanggal_kunjungan)', $tahun);
        $this->db->where('rm.status_rm', 'Selesai');
        
        return $this->db->get()->row();
    }
}

