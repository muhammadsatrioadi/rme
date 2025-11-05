<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Get all users with pagination
    public function get_all_users($limit = null, $offset = null, $search = null) {
        $this->db->select('u.*, p.nama_lengkap as nama_pegawai, p.jabatan');
        $this->db->from('users u');
        $this->db->join('mst_pegawai p', 'u.id_pegawai = p.id_pegawai', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('u.username', $search);
            $this->db->or_like('u.email', $search);
            $this->db->or_like('p.nama_lengkap', $search);
            $this->db->group_end();
        }
        
        $this->db->where('u.status_user', 'Aktif');
        $this->db->order_by('u.created_at', 'DESC');
        
        if ($limit && $offset) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result();
    }

    // Get user by ID
    public function get_user_by_id($id_user) {
        $this->db->select('u.*, p.nama_lengkap as nama_pegawai, p.jabatan, p.spesialisasi');
        $this->db->from('users u');
        $this->db->join('mst_pegawai p', 'u.id_pegawai = p.id_pegawai', 'left');
        $this->db->where('u.id_user', $id_user);
        
        return $this->db->get()->row();
    }

    // Get user by username
    public function get_user_by_username($username) {
        $this->db->select('u.*, p.nama_lengkap as nama_pegawai, p.jabatan, p.spesialisasi');
        $this->db->from('users u');
        $this->db->join('mst_pegawai p', 'u.id_pegawai = p.id_pegawai', 'left');
        $this->db->where('u.username', $username);
        $this->db->where('u.status_user', 'Aktif');
        
        return $this->db->get()->row();
    }

    // Get user by email
    public function get_user_by_email($email) {
        $this->db->select('u.*, p.nama_lengkap as nama_pegawai, p.jabatan, p.spesialisasi');
        $this->db->from('users u');
        $this->db->join('mst_pegawai p', 'u.id_pegawai = p.id_pegawai', 'left');
        $this->db->where('u.email', $email);
        $this->db->where('u.status_user', 'Aktif');
        
        return $this->db->get()->row();
    }

    // Authenticate user
    public function authenticate($username, $password) {
        $user = $this->get_user_by_username($username);
        
        if ($user && password_verify($password, $user->password)) {
            // Update last login
            $this->update_last_login($user->id_user);
            return $user;
        }
        
        return false;
    }

    // Insert new user
    public function insert_user($data) {
        $this->db->trans_start();
        
        // Generate ID User
        $data['id_user'] = $this->generate_id_user();
        
        // Hash password
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $this->session->userdata('user_id');
        
        $this->db->insert('users', $data);
        
        // Log audit trail
        $this->log_audit_trail('users', $data['id_user'], 'INSERT', null, $data);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        
        return $data['id_user'];
    }

    // Update user
    public function update_user($id_user, $data) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_user_by_id($id_user);
        
        // Hash password if provided
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $this->session->userdata('user_id');
        
        $this->db->where('id_user', $id_user);
        $this->db->update('users', $data);
        
        // Log audit trail
        $this->log_audit_trail('users', $id_user, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Update last login
    public function update_last_login($id_user) {
        $data = array(
            'last_login' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id_user', $id_user);
        $this->db->update('users', $data);
    }

    // Change password
    public function change_password($id_user, $new_password) {
        $data = array(
            'password' => password_hash($new_password, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_user', $id_user);
        return $this->db->update('users', $data);
    }

    // Soft delete user
    public function delete_user($id_user) {
        $this->db->trans_start();
        
        // Get old data for audit trail
        $old_data = $this->get_user_by_id($id_user);
        
        $data = array(
            'status_user' => 'Non Aktif',
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata('user_id')
        );
        
        $this->db->where('id_user', $id_user);
        $this->db->update('users', $data);
        
        // Log audit trail
        $this->log_audit_trail('users', $id_user, 'UPDATE', $old_data, $data);
        
        $this->db->trans_complete();
        
        return $this->db->trans_status() !== FALSE;
    }

    // Count total users
    public function count_users($search = null) {
        $this->db->from('users u');
        $this->db->join('mst_pegawai p', 'u.id_pegawai = p.id_pegawai', 'left');
        
        if ($search) {
            $this->db->group_start();
            $this->db->like('u.username', $search);
            $this->db->or_like('u.email', $search);
            $this->db->or_like('p.nama_lengkap', $search);
            $this->db->group_end();
        }
        
        $this->db->where('u.status_user', 'Aktif');
        return $this->db->count_all_results();
    }

    // Generate ID User
    private function generate_id_user() {
        $prefix = 'USR';
        $date = date('Ymd');
        
        $this->db->select('id_user');
        $this->db->from('users');
        $this->db->like('id_user', $prefix . $date, 'after');
        $this->db->order_by('id_user', 'DESC');
        $this->db->limit(1);
        
        $result = $this->db->get()->row();
        
        if ($result) {
            $last_id = $result->id_user;
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

    // Get user statistics
    public function get_user_statistics() {
        $stats = array();
        
        // Total users
        $this->db->where('status_user', 'Aktif');
        $stats['total_users'] = $this->db->count_all_results('users');
        
        // Users by role
        $this->db->select('role, COUNT(*) as total');
        $this->db->from('users');
        $this->db->where('status_user', 'Aktif');
        $this->db->group_by('role');
        $stats['by_role'] = $this->db->get()->result();
        
        // Active users today
        $this->db->where('status_user', 'Aktif');
        $this->db->where('DATE(last_login)', date('Y-m-d'));
        $stats['active_today'] = $this->db->count_all_results('users');
        
        // New users this month
        $this->db->where('status_user', 'Aktif');
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        $stats['new_this_month'] = $this->db->count_all_results('users');
        
        return $stats;
    }

    // Validate username
    public function validate_username($username, $exclude_id = null) {
        $this->db->where('username', $username);
        if ($exclude_id) {
            $this->db->where('id_user !=', $exclude_id);
        }
        return $this->db->count_all_results('users') == 0;
    }

    // Validate email
    public function validate_email($email, $exclude_id = null) {
        $this->db->where('email', $email);
        if ($exclude_id) {
            $this->db->where('id_user !=', $exclude_id);
        }
        return $this->db->count_all_results('users') == 0;
    }

    // Get user permissions
    public function get_user_permissions($role) {
        $permissions = array();
        
        switch ($role) {
            case 'Admin':
                $permissions = array(
                    'pasien' => array('create', 'read', 'update', 'delete'),
                    'pegawai' => array('create', 'read', 'update', 'delete'),
                    'poliklinik' => array('create', 'read', 'update', 'delete'),
                    'obat' => array('create', 'read', 'update', 'delete'),
                    'rekam_medis' => array('create', 'read', 'update', 'delete'),
                    'rawat_inap' => array('create', 'read', 'update', 'delete'),
                    'resep' => array('create', 'read', 'update', 'delete'),
                    'laporan' => array('read'),
                    'user_management' => array('create', 'read', 'update', 'delete'),
                    'audit_trail' => array('read')
                );
                break;
                
            case 'Dokter':
                $permissions = array(
                    'pasien' => array('read'),
                    'rekam_medis' => array('create', 'read', 'update'),
                    'resep' => array('create', 'read', 'update'),
                    'laporan' => array('read')
                );
                break;
                
            case 'Perawat':
                $permissions = array(
                    'pasien' => array('read'),
                    'rekam_medis' => array('read'),
                    'rawat_inap' => array('create', 'read', 'update'),
                    'laporan' => array('read')
                );
                break;
                
            case 'Apoteker':
                $permissions = array(
                    'obat' => array('read', 'update'),
                    'resep' => array('read', 'update'),
                    'laporan' => array('read')
                );
                break;
                
            case 'Kasir':
                $permissions = array(
                    'pasien' => array('read'),
                    'rekam_medis' => array('read'),
                    'resep' => array('read'),
                    'laporan' => array('read')
                );
                break;
                
            case 'Manager':
                $permissions = array(
                    'pasien' => array('read'),
                    'pegawai' => array('read'),
                    'poliklinik' => array('read'),
                    'obat' => array('read'),
                    'rekam_medis' => array('read'),
                    'rawat_inap' => array('read'),
                    'resep' => array('read'),
                    'laporan' => array('read'),
                    'audit_trail' => array('read')
                );
                break;
        }
        
        return $permissions;
    }

    // Check user permission
    public function has_permission($user_role, $module, $action) {
        $permissions = $this->get_user_permissions($user_role);
        
        if (isset($permissions[$module]) && in_array($action, $permissions[$module])) {
            return true;
        }
        
        return false;
    }
}

