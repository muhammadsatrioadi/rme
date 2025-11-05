<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekam_medis_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all medical records with pagination
    public function get_all_rekam_medis($limit = null, $offset = null, $search = null, $filter = null) {
        $this->db->select('rm.*, p.nama_lengkap, p.no_rm, p.nik, pol.nama_poliklinik, d.nama_lengkap as nama_dokter');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_pasien p', 'rm.id_pasien = p.id_pasien', 'left');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('p.nama_lengkap', $search);
            $this->db->or_like('p.nik', $search);
            $this->db->or_like('p.no_rm', $search);
            $this->db->or_like('rm.id_rm', $search);
            $this->db->or_like('d.nama_lengkap', $search);
            $this->db->group_end();
        }
        
        if ($filter) {
            if (isset($filter['tanggal_dari']) && $filter['tanggal_dari']) {
                $this->db->where('rm.tanggal_kunjungan >=', $filter['tanggal_dari']);
            }
            if (isset($filter['tanggal_sampai']) && $filter['tanggal_sampai']) {
                $this->db->where('rm.tanggal_kunjungan <=', $filter['tanggal_sampai']);
            }
            if (isset($filter['id_poliklinik']) && $filter['id_poliklinik']) {
                $this->db->where('rm.id_poliklinik', $filter['id_poliklinik']);
            }
            if (isset($filter['id_dokter']) && $filter['id_dokter']) {
                $this->db->where('rm.id_dokter', $filter['id_dokter']);
            }
            if (isset($filter['status_rm']) && $filter['status_rm']) {
                $this->db->where('rm.status_rm', $filter['status_rm']);
            }
        }
        
        $this->db->order_by('rm.tanggal_kunjungan', 'DESC');
        $this->db->order_by('rm.jam_kunjungan', 'DESC');
        
        if ($limit && $offset) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    // Get medical record by ID
    public function get_rekam_medis_by_id($id_rm) {
        $this->db->select('rm.*, p.nama_lengkap, p.no_rm, p.nik, p.jenis_kelamin, p.tanggal_lahir, p.alamat_ktp, p.no_telepon, p.no_hp, p.golongan_darah, p.rhesus, p.alergi, p.penyakit_kronis, pol.nama_poliklinik, d.nama_lengkap as nama_dokter, d.spesialisasi');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_pasien p', 'rm.id_pasien = p.id_pasien', 'left');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        $this->db->where('rm.id_rm', $id_rm);
        
        return $this->db->get()->row();
    }

    // Get medical records by patient ID
    public function get_rekam_medis_by_pasien($id_pasien, $limit = null) {
        $this->db->select('rm.*, pol.nama_poliklinik, d.nama_lengkap as nama_dokter');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        $this->db->where('rm.id_pasien', $id_pasien);
        $this->db->order_by('rm.tanggal_kunjungan', 'DESC');
        $this->db->order_by('rm.jam_kunjungan', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result();
    }

    // Insert new medical record
    public function insert_rekam_medis($data) {
        $this->db->trans_start();
        
        // Generate ID RM
        $data['id_rm'] = $this->generate_id_rm();
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert('rekam_medis', $data);
        
        // Log audit trail
        $this->log_audit_trail('rekam_medis', $data['id_rm'], 'INSERT', null, $data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        
        return $data['id_rm'];
    }

    // Update medical record
    public function update_rekam_medis($id_rm, $data) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_rekam_medis_by_id($id_rm);
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->userdata('user_id');
        
        $this->db->where('id_rm', $id_rm);
        $this->db->update('rekam_medis', $data);
        
        // Log audit trail
        $this->log_audit_trail('rekam_medis', $id_rm, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Delete medical record
    public function delete_rekam_medis($id_rm) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_rekam_medis_by_id($id_rm);
        
        $this->db->where('id_rm', $id_rm);
        $this->db->delete('rekam_medis');
        
        // Log audit trail
        $this->log_audit_trail('rekam_medis', $id_rm, 'DELETE', $old_data, null);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Count total medical records
    public function count_rekam_medis($search = null, $filter = null) {
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_pasien p', 'rm.id_pasien = p.id_pasien', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('p.nama_lengkap', $search);
            $this->db->or_like('p.nik', $search);
            $this->db->or_like('p.no_rm', $search);
            $this->db->or_like('rm.id_rm', $search);
            $this->db->or_like('d.nama_lengkap', $search);
            $this->db->group_end();
        }
        
        if ($filter) {
            if (isset($filter['tanggal_dari']) && $filter['tanggal_dari']) {
                $this->db->where('rm.tanggal_kunjungan >=', $filter['tanggal_dari']);
            }
            if (isset($filter['tanggal_sampai']) && $filter['tanggal_sampai']) {
                $this->db->where('rm.tanggal_kunjungan <=', $filter['tanggal_sampai']);
            }
            if (isset($filter['id_poliklinik']) && $filter['id_poliklinik']) {
                $this->db->where('rm.id_poliklinik', $filter['id_poliklinik']);
            }
            if (isset($filter['id_dokter']) && $filter['id_dokter']) {
                $this->db->where('rm.id_dokter', $filter['id_dokter']);
            }
            if (isset($filter['status_rm']) && $filter['status_rm']) {
                $this->db->where('rm.status_rm', $filter['status_rm']);
            }
        }
        
        return $this->db->count_all_results();
    }

    // Generate ID RM
    private function generate_id_rm() {
        $prefix = 'RM';
        $date = date('Ymd');
        
        $this->db->select('id_rm');
        $this->db->from('rekam_medis');
        $this->db->like('id_rm', $prefix . $date, 'after');
        $this->db->order_by('id_rm', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_id = $result->id_rm;
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

    // Get medical record statistics
    public function get_rekam_medis_statistics() {
        $stats = array();
        
        // Total medical records
        $stats['total_rm'] = $this->db->count_all_results('rekam_medis');
        
        // Medical records by status
        $this->db->select('status_rm, COUNT(*) as total');
        $this->db->from('rekam_medis');
        $this->db->group_by('status_rm');
        $stats['by_status'] = $this->db->get()->result();
        
        // Medical records by clinic
        $this->db->select('pol.nama_poliklinik, COUNT(*) as total');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->group_by('rm.id_poliklinik');
        $stats['by_clinic'] = $this->db->get()->result();
        
        // Medical records this month
        $this->db->where('MONTH(tanggal_kunjungan)', date('m'));
        $this->db->where('YEAR(tanggal_kunjungan)', date('Y'));
        $stats['this_month'] = $this->db->count_all_results('rekam_medis');
        
        // Medical records today
        $this->db->where('tanggal_kunjungan', date('Y-m-d'));
        $stats['today'] = $this->db->count_all_results('rekam_medis');
        
        return $stats;
    }

    // Get patient medical history
    public function get_riwayat_medis($id_pasien) {
        $this->db->select('rm.*, pol.nama_poliklinik, d.nama_lengkap as nama_dokter, d.spesialisasi');
        $this->db->from('rekam_medis rm');
        $this->db->join('mst_poliklinik pol', 'rm.id_poliklinik = pol.id_poliklinik', 'left');
        $this->db->join('mst_pegawai d', 'rm.id_dokter = d.id_pegawai', 'left');
        $this->db->where('rm.id_pasien', $id_pasien);
        $this->db->order_by('rm.tanggal_kunjungan', 'DESC');
        $this->db->order_by('rm.jam_kunjungan', 'DESC');
        
        return $this->db->get()->result();
    }

    // Get diagnosis statistics
    public function get_diagnosis_statistics($limit = 10) {
        $this->db->select('kode_icd10_utama, diagnosis_utama, COUNT(*) as total');
        $this->db->from('rekam_medis');
        $this->db->where('kode_icd10_utama IS NOT NULL');
        $this->db->where('kode_icd10_utama !=', '');
        $this->db->group_by('kode_icd10_utama');
        $this->db->order_by('total', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
}

