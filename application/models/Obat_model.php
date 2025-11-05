<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all medicines with pagination
    public function get_all_obat($limit = null, $offset = null, $search = null) {
        $this->db->select('*');
        $this->db->from('mst_obat');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_obat', $search);
            $this->db->or_like('nama_generik', $search);
            $this->db->or_like('kode_obat', $search);
            $this->db->or_like('kategori_obat', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status_obat', 'Aktif');
        $this->db->order_by('nama_obat', 'ASC');
        
        if ($limit && $offset) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    // Get medicine by ID
    public function get_obat_by_id($id_obat) {
        return $this->db->get_where('mst_obat', array('id_obat' => $id_obat))->row();
    }

    // Get medicine by code
    public function get_obat_by_kode($kode_obat) {
        return $this->db->get_where('mst_obat', array('kode_obat' => $kode_obat))->row();
    }

    // Get medicines by category
    public function get_obat_by_kategori($kategori_obat) {
        $this->db->where('kategori_obat', $kategori_obat);
        $this->db->where('status_obat', 'Aktif');
        $this->db->order_by('nama_obat', 'ASC');
        return $this->db->get('mst_obat')->result();
    }

    // Get low stock medicines
    public function get_obat_stok_rendah() {
        $this->db->where('stok_aktual <= stok_minimal');
        $this->db->where('status_obat', 'Aktif');
        $this->db->order_by('stok_aktual', 'ASC');
        return $this->db->get('mst_obat')->result();
    }

    // Get expired medicines
    public function get_obat_expired() {
        $this->db->where('expired_date <=', date('Y-m-d'));
        $this->db->where('status_obat', 'Aktif');
        $this->db->order_by('expired_date', 'ASC');
        return $this->db->get('mst_obat')->result();
    }

    // Insert new medicine
    public function insert_obat($data) {
        $this->db->trans_start();
        
        // Generate ID Obat
        $data['id_obat'] = $this->generate_id_obat();
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert('mst_obat', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_obat', $data['id_obat'], 'INSERT', null, $data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        
        return $data['id_obat'];
    }

    // Update medicine
    public function update_obat($id_obat, $data) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_obat_by_id($id_obat);
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->userdata('user_id');
        
        $this->db->where('id_obat', $id_obat);
        $this->db->update('mst_obat', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_obat', $id_obat, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Update stock
    public function update_stok($id_obat, $jumlah, $type = 'in') {
        $this->db->trans_start();
        
        $obat = $this->get_obat_by_id($id_obat);
        if (!$obat) {
            return false;
        }
        
        $old_stok = $obat->stok_aktual;
        
        if ($type == 'in') {
            $new_stok = $old_stok + $jumlah;
        } else {
            $new_stok = $old_stok - $jumlah;
            if ($new_stok < 0) {
                $new_stok = 0;
            }
        }
        
        $data = array(
            'stok_aktual' => $new_stok,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_obat', $id_obat);
        $this->db->update('mst_obat', $data);
        
        // Log audit trail
        $audit_data = array(
            'old_stok' => $old_stok,
            'new_stok' => $new_stok,
            'jumlah' => $jumlah,
            'type' => $type
        );
        $this->log_audit_trail('mst_obat', $id_obat, 'UPDATE', array('stok_aktual' => $old_stok), $audit_data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Soft delete medicine
    public function delete_obat($id_obat) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_obat_by_id($id_obat);
        
        $data = array(
            'status_obat' => 'Non Aktif',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_obat', $id_obat);
        $this->db->update('mst_obat', $data);
        
        // Log audit trail
        $this->log_audit_trail('mst_obat', $id_obat, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Count total medicines
    public function count_obat($search = null) {
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_obat', $search);
            $this->db->or_like('nama_generik', $search);
            $this->db->or_like('kode_obat', $search);
            $this->db->or_like('kategori_obat', $search);
            $this->db->group_end();
        }
        
        $this->db->where('status_obat', 'Aktif');
        return $this->db->count_all_results('mst_obat');
    }

    // Generate ID Obat
    private function generate_id_obat() {
        $prefix = 'OBT';
        $date = date('Ymd');
        
        $this->db->select('id_obat');
        $this->db->from('mst_obat');
        $this->db->like('id_obat', $prefix . $date, 'after');
        $this->db->order_by('id_obat', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_id = $result->id_obat;
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

    // Get medicine statistics
    public function get_obat_statistics() {
        $stats = array();
        
        // Total medicines
        $this->db->where('status_obat', 'Aktif');
        $stats['total_obat'] = $this->db->count_all_results('mst_obat');
        
        // Medicines by category
        $this->db->select('kategori_obat, COUNT(*) as total');
        $this->db->from('mst_obat');
        $this->db->where('status_obat', 'Aktif');
        $this->db->group_by('kategori_obat');
        $stats['by_category'] = $this->db->get()->result();
        
        // Low stock medicines
        $this->db->where('stok_aktual <= stok_minimal');
        $this->db->where('status_obat', 'Aktif');
        $stats['stok_rendah'] = $this->db->count_all_results('mst_obat');
        
        // Expired medicines
        $this->db->where('expired_date <=', date('Y-m-d'));
        $this->db->where('status_obat', 'Aktif');
        $stats['expired'] = $this->db->count_all_results('mst_obat');
        
        // Total stock value
        $this->db->select('SUM(stok_aktual * harga_beli) as total_nilai');
        $this->db->from('mst_obat');
        $this->db->where('status_obat', 'Aktif');
        $result = $this->db->get()->row();
        $stats['total_nilai_stok'] = $result->total_nilai ? $result->total_nilai : 0;
        
        return $stats;
    }

    // Validate medicine code
    public function validate_kode_obat($kode_obat, $exclude_id = null) {
        $this->db->where('kode_obat', $kode_obat);
        if ($exclude_id) {
            $this->db->where('id_obat !=', $exclude_id);
        }
        return $this->db->count_all_results('mst_obat') == 0;
    }

    // Get medicine categories
    public function get_kategori_obat() {
        $this->db->select('DISTINCT kategori_obat');
        $this->db->from('mst_obat');
        $this->db->where('status_obat', 'Aktif');
        $this->db->order_by('kategori_obat', 'ASC');
        
        $result = $this->db->get()->result();
        $categories = array();
        foreach ($result as $row) {
            $categories[] = $row->kategori_obat;
        }
        
        return $categories;
    }

    // Search medicines for prescription
    public function search_obat_for_resep($search) {
        $this->db->select('id_obat, nama_obat, nama_generik, satuan, harga_jual, stok_aktual');
        $this->db->from('mst_obat');
        $this->db->where('status_obat', 'Aktif');
        $this->db->where('stok_aktual >', 0);
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_obat', $search);
            $this->db->or_like('nama_generik', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('nama_obat', 'ASC');
        $this->db->limit(20);
        
        return $this->db->get()->result();
    }
}

