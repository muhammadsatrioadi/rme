<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PR_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    public function save_pr_header($data) {
        $this->db->insert('pr_header', $data);
        return $this->db->insert_id();
    }

    public function save_pr_detail($data) {
        $this->db->insert('pr_detail', $data);
        return $this->db->insert_id();
    }

    public function get_pr_list($search = '', $status = '', $priority = '', $date_from = '', $date_to = '', $limit = 25, $offset = 0) {
        $this->db->select('
            h.*,
            u.name as requester_name,
            d.name as dept_name,
            (SELECT SUM(est_line_total) FROM pr_detail WHERE pr_header_id = h.id) as total_estimation
        ');
        $this->db->from('pr_header h');
        $this->db->join('users u', 'u.id = h.requester_id', 'left');
        $this->db->join('departments d', 'd.id = h.dept_id', 'left');
        
        // Apply filters
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('h.pr_no', $search);
            $this->db->or_like('u.name', $search);
            $this->db->or_like('d.name', $search);
            $this->db->group_end();
        }
        
        if (!empty($status)) {
            $this->db->where('h.status', $status);
        }
        
        if (!empty($priority)) {
            $this->db->where('h.priority', $priority);
        }
        
        if (!empty($date_from)) {
            $this->db->where('h.pr_date >=', $date_from);
        }
        
        if (!empty($date_to)) {
            $this->db->where('h.pr_date <=', $date_to);
        }
        
        $this->db->order_by('h.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function count_pr_list($search = '', $status = '', $priority = '', $date_from = '', $date_to = '') {
        $this->db->from('pr_header h');
        $this->db->join('users u', 'u.id = h.requester_id', 'left');
        $this->db->join('departments d', 'd.id = h.dept_id', 'left');
        
        // Apply filters
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('h.pr_no', $search);
            $this->db->or_like('u.name', $search);
            $this->db->or_like('d.name', $search);
            $this->db->group_end();
        }
        
        if (!empty($status)) {
            $this->db->where('h.status', $status);
        }
        
        if (!empty($priority)) {
            $this->db->where('h.priority', $priority);
        }
        
        if (!empty($date_from)) {
            $this->db->where('h.pr_date >=', $date_from);
        }
        
        if (!empty($date_to)) {
            $this->db->where('h.pr_date <=', $date_to);
        }
        
        return $this->db->count_all_results();
    }

    public function get_pr_by_no($pr_no) {
        $this->db->select('
            h.*,
            u.name as requester_name,
            d.name as dept_name
        ');
        $this->db->from('pr_header h');
        $this->db->join('users u', 'u.id = h.requester_id', 'left');
        $this->db->join('departments d', 'd.id = h.dept_id', 'left');
        $this->db->where('h.pr_no', $pr_no);
        
        $query = $this->db->get();
        $header = $query->row_array();
        
        if ($header) {
            // Get details
            $this->db->select('
                d.*,
                i.name as item_name,
                uom.name as uom_name,
                w.name as warehouse_name,
                cc.name as cc_name,
                p.name as project_name
            ');
            $this->db->from('pr_detail d');
            $this->db->join('items i', 'i.id = d.item_id', 'left');
            $this->db->join('uom uom', 'uom.id = d.uom_id', 'left');
            $this->db->join('warehouses w', 'w.id = d.warehouse_id', 'left');
            $this->db->join('cost_centers cc', 'cc.id = d.cc_id', 'left');
            $this->db->join('projects p', 'p.id = d.project_id', 'left');
            $this->db->where('d.pr_header_id', $header['id']);
            $this->db->order_by('d.line_no', 'ASC');
            
            $query = $this->db->get();
            $header['details'] = $query->result_array();
        }
        
        return $header;
    }

    public function get_last_pr_number($year, $month) {
        $this->db->select('pr_no');
        $this->db->from('pr_header');
        $this->db->like('pr_no', 'PR' . $year . $month);
        $this->db->order_by('pr_no', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        $result = $query->row();
        
        return $result ? $result->pr_no : null;
    }

    public function delete_pr($pr_no) {
        $this->db->trans_start();
        
        // Get header ID
        $this->db->select('id');
        $this->db->from('pr_header');
        $this->db->where('pr_no', $pr_no);
        $query = $this->db->get();
        $header = $query->row();
        
        if ($header) {
            // Delete details first
            $this->db->where('pr_header_id', $header->id);
            $this->db->delete('pr_detail');
            
            // Delete header
            $this->db->where('pr_no', $pr_no);
            $this->db->delete('pr_header');
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_pr_status($pr_no, $status, $approval_level = null) {
        $data = array('status' => $status);
        
        if ($approval_level !== null) {
            $data['approval_level'] = $approval_level;
        }
        
        $this->db->where('pr_no', $pr_no);
        return $this->db->update('pr_header', $data);
    }

    public function get_pr_statistics() {
        $stats = array();
        
        // Total PR
        $stats['total'] = $this->db->count_all('pr_header');
        
        // By status
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('pr_header');
        $this->db->group_by('status');
        $query = $this->db->get();
        $status_counts = $query->result_array();
        
        foreach ($status_counts as $row) {
            $stats['by_status'][$row['status']] = $row['count'];
        }
        
        // By priority
        $this->db->select('priority, COUNT(*) as count');
        $this->db->from('pr_header');
        $this->db->group_by('priority');
        $query = $this->db->get();
        $priority_counts = $query->result_array();
        
        foreach ($priority_counts as $row) {
            $stats['by_priority'][$row['priority']] = $row['count'];
        }
        
        // Total value
        $this->db->select('SUM((SELECT SUM(est_line_total) FROM pr_detail WHERE pr_header_id = pr_header.id)) as total_value');
        $this->db->from('pr_header');
        $query = $this->db->get();
        $result = $query->row();
        $stats['total_value'] = $result->total_value ?: 0;
        
        return $stats;
    }
}

?>
