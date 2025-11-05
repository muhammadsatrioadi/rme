<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wilayah extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Wilayah_model');
        header('Content-Type: application/json');
    }
    
    public function provinsi() {
        try {
            $data = $this->Wilayah_model->get_provinsi();
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    
    public function kabupaten() {
        try {
            $id_provinsi = $this->input->get('provinsi');
            if (!$id_provinsi) {
                echo json_encode(array());
                return;
            }
            $data = $this->Wilayah_model->get_kabupaten_by_provinsi($id_provinsi);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    
    public function kecamatan() {
        try {
            $id_kabupaten = $this->input->get('kabupaten');
            if (!$id_kabupaten) {
                echo json_encode(array());
                return;
            }
            $data = $this->Wilayah_model->get_kecamatan_by_kabupaten($id_kabupaten);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    
    public function kelurahan() {
        try {
            $id_kecamatan = $this->input->get('kecamatan');
            if (!$id_kecamatan) {
                echo json_encode(array());
                return;
            }
            $data = $this->Wilayah_model->get_kelurahan_by_kecamatan($id_kecamatan);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
    
    public function kodepos() {
        try {
            $id_kelurahan = $this->input->get('kelurahan');
            if (!$id_kelurahan) {
                echo json_encode(array('kode_pos' => ''));
                return;
            }
            $kode_pos = $this->Wilayah_model->get_kodepos_by_kelurahan($id_kelurahan);
            echo json_encode(array('kode_pos' => $kode_pos));
        } catch (Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
}
