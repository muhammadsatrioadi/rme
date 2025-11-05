<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	public function index() {
		$data = array(
			'title' => "Ecommerce Dashboard"
		);
		$this->load->view('master/pegawai', $data);
	}
}