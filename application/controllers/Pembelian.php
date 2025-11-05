<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('PR_model');
        $this->load->library('pagination');
        $this->load->helper('url');
    }

    public function index() {
        $this->load->view('Pembelian/PR');
    }

    public function pr() {
        $this->load->view('Pembelian/PR');
    }

    public function list_pr() {
        // Get filter parameters
        $search = $this->input->get('search');
        $status = $this->input->get('status');
        $priority = $this->input->get('priority');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        
        // Pagination configuration
        $config['base_url'] = base_url('pembelian/list_pr');
        $config['total_rows'] = $this->PR_model->count_pr_list($search, $status, $priority, $date_from, $date_to);
        $config['per_page'] = 25;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        // Pagination styling
        $config['full_tag_open'] = '<nav><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        
        $this->pagination->initialize($config);
        
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        
        // Get PR list data
        $pr_list = $this->PR_model->get_pr_list($search, $status, $priority, $date_from, $date_to, $config['per_page'], $page);
        
        // Prepare pagination info
        $pagination_info = array(
            'start' => $page + 1,
            'end' => min($page + $config['per_page'], $config['total_rows']),
            'total' => $config['total_rows'],
            'links' => $this->pagination->create_links()
        );
        
        $data = array(
            'pr_list' => $pr_list,
            'pagination' => $pagination_info
        );
        
        $this->load->view('Pembelian/list_pr', $data);
    }

    public function simpan_pr() {
        if ($this->input->method() == 'post') {
            $this->load->library('form_validation');
            
            // Set validation rules
            $this->form_validation->set_rules('pr_date', 'Tanggal PR', 'required');
            $this->form_validation->set_rules('requester_id', 'Pemohon', 'required');
            $this->form_validation->set_rules('dept_id', 'Departemen', 'required');
            $this->form_validation->set_rules('priority', 'Prioritas', 'required');
            $this->form_validation->set_rules('pr_type', 'Tipe PR', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('pembelian/pr');
            } else {
                // Generate PR number
                $pr_no = $this->generate_pr_number();
                
                // Prepare header data
                $header_data = array(
                    'pr_no' => $pr_no,
                    'pr_date' => $this->input->post('pr_date'),
                    'requester_id' => $this->input->post('requester_id'),
                    'dept_id' => $this->input->post('dept_id'),
                    'need_by_date' => $this->input->post('need_by_date'),
                    'priority' => $this->input->post('priority'),
                    'pr_type' => $this->input->post('pr_type'),
                    'currency' => $this->input->post('currency'),
                    'exch_rate' => $this->input->post('exch_rate'),
                    'status' => 'Draft',
                    'approval_level' => 0,
                    'approval_final_level' => 1,
                    'justification' => $this->input->post('justification'),
                    'remarks' => $this->input->post('remarks'),
                    'created_by' => $this->session->userdata('user_id') ?: 1,
                    'created_at' => date('Y-m-d H:i:s')
                );
                
                // Save header
                $header_id = $this->PR_model->save_pr_header($header_data);
                
                if ($header_id) {
                    // Save details
                    $details = $this->input->post('detail');
                    if (!empty($details)) {
                        foreach ($details as $detail) {
                            if (!empty($detail['item_id'])) {
                                $detail_data = array(
                                    'pr_header_id' => $header_id,
                                    'line_no' => $detail['line_no'],
                                    'item_id' => $detail['item_id'],
                                    'item_desc' => $detail['item_desc'],
                                    'uom_id' => $detail['uom_id'],
                                    'qty_req' => $detail['qty_req'],
                                    'est_unit_price' => $detail['est_unit_price'],
                                    'est_line_total' => $detail['est_line_total'],
                                    'warehouse_id' => $detail['warehouse_id'],
                                    'cc_id' => $detail['cc_id'],
                                    'project_id' => $detail['project_id'],
                                    'need_by_date' => $detail['need_by_date'],
                                    'note' => $detail['note']
                                );
                                $this->PR_model->save_pr_detail($detail_data);
                            }
                        }
                    }
                    
                    $this->session->set_flashdata('success', 'Purchase Request berhasil disimpan dengan nomor: ' . $pr_no);
                    redirect('pembelian/list_pr');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menyimpan Purchase Request');
                    redirect('pembelian/pr');
                }
            }
        } else {
            redirect('pembelian/pr');
        }
    }

    public function detail_pr($pr_no) {
        $pr_data = $this->PR_model->get_pr_by_no($pr_no);
        if ($pr_data) {
            $data['pr'] = $pr_data;
            $this->load->view('Pembelian/detail_pr', $data);
        } else {
            show_404();
        }
    }

    public function edit_pr($pr_no) {
        $pr_data = $this->PR_model->get_pr_by_no($pr_no);
        if ($pr_data && $pr_data['status'] == 'Draft') {
            $data['pr'] = $pr_data;
            $this->load->view('Pembelian/PR', $data);
        } else {
            $this->session->set_flashdata('error', 'PR tidak dapat diedit atau tidak ditemukan');
            redirect('pembelian/list_pr');
        }
    }

    public function delete_pr() {
        if ($this->input->method() == 'post') {
            $pr_no = $this->input->post('pr_no');
            $result = $this->PR_model->delete_pr($pr_no);
            
            if ($result) {
                echo json_encode(array('success' => true, 'message' => 'PR berhasil dihapus'));
            } else {
                echo json_encode(array('success' => false, 'message' => 'Gagal menghapus PR'));
            }
        }
    }

    public function print_pr($pr_no) {
        $pr_data = $this->PR_model->get_pr_by_no($pr_no);
        if ($pr_data) {
            $data['pr'] = $pr_data;
            $this->load->view('Pembelian/print_pr', $data);
        } else {
            show_404();
        }
    }

    private function generate_pr_number() {
        $year = date('Y');
        $month = date('m');
        
        // Get last PR number for this month
        $last_pr = $this->PR_model->get_last_pr_number($year, $month);
        
        if ($last_pr) {
            $last_number = intval(substr($last_pr, -4));
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }
        
        return 'PR' . $year . $month . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}

?>