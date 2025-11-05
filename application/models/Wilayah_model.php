<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all provinces
    public function get_provinsi() {
        $this->db->select('id, name as nama');
        $this->db->from('provinces');
        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result();
    }

    // Get regencies (kabupaten) by province
    public function get_kabupaten_by_provinsi($id_provinsi) {
        $this->db->select('id, name as nama');
        $this->db->from('regencies');
        $this->db->where('province_id', $id_provinsi);
        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result();
    }

    // Get districts (kecamatan) by regency
    public function get_kecamatan_by_kabupaten($id_kabupaten) {
        $this->db->select('id, name as nama');
        $this->db->from('districts');
        $this->db->where('regency_id', $id_kabupaten);
        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result();
    }

    // Get villages (kelurahan) by district
    public function get_kelurahan_by_kecamatan($id_kecamatan) {
        $this->db->select('id, name as nama');
        $this->db->from('villages');
        $this->db->where('district_id', $id_kecamatan);
        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result();
    }

    // Get kode pos by kelurahan
    // Note: indonesia.sql tidak memiliki kolom kode_pos di tabel villages
    // ID kelurahan menggunakan format 10 digit: PPKKDDNNNN (Provinsi-Kabupaten-Kecamatan-Kelurahan)
    // Kode pos biasanya 5 digit dan tidak bisa langsung diambil dari ID ini
    // Return kosong karena data kode pos tidak tersedia di database
    public function get_kodepos_by_kelurahan($id_kelurahan) {
        // Tabel villages tidak memiliki kolom kode_pos
        // Jika diperlukan, bisa ditambahkan tabel kode_pos terpisah atau kolom di tabel villages
        return '';
    }
}

