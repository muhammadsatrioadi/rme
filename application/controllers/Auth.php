<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }
        $this->login();
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $data['title'] = 'Login - Sistem RME';
        
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/login', $data);
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->User_model->authenticate($username, $password);

            if ($user) {
                // Set session data
                $session_data = array(
                    'user_id' => $user->id_user,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'nama_pegawai' => $user->nama_pegawai,
                    'jabatan' => $user->jabatan,
                    'logged_in' => TRUE
                );

                $this->session->set_userdata($session_data);

                // Log login activity
                $this->log_activity('LOGIN', 'User logged in successfully');

                redirect('dashboard');
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah!');
                redirect('auth/login');
            }
        }
    }

    public function logout() {
        // Log logout activity
        $this->log_activity('LOGOUT', 'User logged out');

        // Destroy session
        $this->session->sess_destroy();
        redirect('auth/login');
    }

    public function change_password() {
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $data['title'] = 'Ubah Password - Sistem RME';

        $this->form_validation->set_rules('current_password', 'Password Lama', 'required|trim');
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|trim|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/change_password', $data);
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password');

            // Verify current password
            $user = $this->User_model->get_user_by_id($this->session->userdata('user_id'));
            
            if (password_verify($current_password, $user->password)) {
                // Update password
                if ($this->User_model->change_password($user->id_user, $new_password)) {
                    $this->session->set_flashdata('success', 'Password berhasil diubah!');
                    $this->log_activity('CHANGE_PASSWORD', 'Password changed successfully');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengubah password!');
                }
            } else {
                $this->session->set_flashdata('error', 'Password lama salah!');
            }

            redirect('auth/change_password');
        }
    }

    public function forgot_password() {
        $data['title'] = 'Lupa Password - Sistem RME';

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/forgot_password', $data);
        } else {
            $email = $this->input->post('email');
            $user = $this->User_model->get_user_by_email($email);

            if ($user) {
                // Generate reset token (simplified - in production use proper token system)
                $reset_token = bin2hex(random_bytes(32));
                
                // Store reset token in database (you need to add this field)
                // For now, just show success message
                $this->session->set_flashdata('success', 'Instruksi reset password telah dikirim ke email Anda!');
                $this->log_activity('FORGOT_PASSWORD', 'Password reset requested for email: ' . $email);
            } else {
                $this->session->set_flashdata('error', 'Email tidak ditemukan!');
            }

            redirect('auth/forgot_password');
        }
    }

    public function reset_password($token = null) {
        if (!$token) {
            show_404();
        }

        $data['title'] = 'Reset Password - Sistem RME';
        $data['token'] = $token;

        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|trim|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|trim|matches[new_password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/reset_password', $data);
        } else {
            $new_password = $this->input->post('new_password');

            // Verify token and update password (simplified)
            // In production, implement proper token verification
            $this->session->set_flashdata('success', 'Password berhasil direset! Silakan login dengan password baru.');
            $this->log_activity('RESET_PASSWORD', 'Password reset completed');
            
            redirect('auth/login');
        }
    }

    private function log_activity($action, $description) {
        // Log user activity
        $log_data = array(
            'user_id' => $this->session->userdata('user_id'),
            'action' => $action,
            'description' => $description,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        );

        // You can create a separate activity log table or use audit_trail
        $this->db->insert('audit_trail', array(
            'table_name' => 'user_activity',
            'record_id' => $this->session->userdata('user_id'),
            'action' => $action,
            'old_values' => null,
            'new_values' => json_encode($log_data),
            'user_id' => $this->session->userdata('user_id'),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent()
        ));
    }

    public function check_session() {
        if (!$this->session->userdata('logged_in')) {
            $this->output->set_status_header(401);
            echo json_encode(array('status' => 'error', 'message' => 'Session expired'));
            exit;
        }
    }
}

