<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security_library {

    private $CI;
    private $config;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('security');
        $this->config = $this->CI->config->item('security');
    }

    /**
     * Encrypt sensitive data
     */
    public function encrypt_data($data) {
        if (empty($data)) {
            return $data;
        }

        $this->CI->load->library('encryption');
        return $this->CI->encryption->encrypt($data);
    }

    /**
     * Decrypt sensitive data
     */
    public function decrypt_data($encrypted_data) {
        if (empty($encrypted_data)) {
            return $encrypted_data;
        }

        $this->CI->load->library('encryption');
        return $this->CI->encryption->decrypt($encrypted_data);
    }

    /**
     * Hash password with salt
     */
    public function hash_password($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Verify password
     */
    public function verify_password($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Generate secure random token
     */
    public function generate_token($length = 32) {
        return bin2hex(random_bytes($length));
    }

    /**
     * Validate password strength
     */
    public function validate_password_strength($password) {
        $errors = array();

        if (strlen($password) < $this->config['password_min_length']) {
            $errors[] = 'Password minimal ' . $this->config['password_min_length'] . ' karakter';
        }

        if ($this->config['password_require_uppercase'] && !preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf besar';
        }

        if ($this->config['password_require_lowercase'] && !preg_match('/[a-z]/', $password)) {
            $errors[] = 'Password harus mengandung huruf kecil';
        }

        if ($this->config['password_require_number'] && !preg_match('/[0-9]/', $password)) {
            $errors[] = 'Password harus mengandung angka';
        }

        if ($this->config['password_require_special'] && !preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Password harus mengandung karakter khusus';
        }

        return $errors;
    }

    /**
     * Check login attempts
     */
    public function check_login_attempts($username, $ip_address) {
        $this->CI->load->model('User_model');
        
        $attempts = $this->CI->db->where('username', $username)
                                 ->where('ip_address', $ip_address)
                                 ->where('created_at >', date('Y-m-d H:i:s', time() - $this->config['login_attempt_timeout']))
                                 ->count_all_results('login_attempts');

        return $attempts < $this->config['max_login_attempts'];
    }

    /**
     * Record failed login attempt
     */
    public function record_failed_login($username, $ip_address, $user_agent) {
        $data = array(
            'username' => $username,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('login_attempts', $data);
    }

    /**
     * Clear login attempts
     */
    public function clear_login_attempts($username, $ip_address) {
        $this->CI->db->where('username', $username)
                     ->where('ip_address', $ip_address)
                     ->delete('login_attempts');
    }

    /**
     * Sanitize input data
     */
    public function sanitize_input($data) {
        if (is_array($data)) {
            return array_map(array($this, 'sanitize_input'), $data);
        }

        // Remove null bytes
        $data = str_replace(chr(0), '', $data);
        
        // Trim whitespace
        $data = trim($data);
        
        // Remove HTML tags
        $data = strip_tags($data);
        
        // Escape special characters
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');

        return $data;
    }

    /**
     * Validate file upload
     */
    public function validate_file_upload($file) {
        $errors = array();

        // Check file size
        if ($file['size'] > $this->config['upload_max_size']) {
            $errors[] = 'File terlalu besar. Maksimal ' . ($this->config['upload_max_size'] / 1024 / 1024) . 'MB';
        }

        // Check file type
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $this->config['upload_allowed_types'])) {
            $errors[] = 'Tipe file tidak diizinkan. Hanya: ' . implode(', ', $this->config['upload_allowed_types']);
        }

        // Check for malicious content
        if ($this->contains_malicious_content($file['tmp_name'])) {
            $errors[] = 'File mengandung konten berbahaya';
        }

        return $errors;
    }

    /**
     * Check for malicious content in file
     */
    private function contains_malicious_content($file_path) {
        $content = file_get_contents($file_path);
        
        // Check for PHP tags
        if (strpos($content, '<?php') !== false || strpos($content, '<?=') !== false) {
            return true;
        }

        // Check for script tags
        if (preg_match('/<script[^>]*>.*?<\/script>/is', $content)) {
            return true;
        }

        // Check for executable content
        if (preg_match('/eval\s*\(/i', $content)) {
            return true;
        }

        return false;
    }

    /**
     * Generate secure filename
     */
    public function generate_secure_filename($original_filename) {
        $extension = pathinfo($original_filename, PATHINFO_EXTENSION);
        $basename = pathinfo($original_filename, PATHINFO_FILENAME);
        
        // Sanitize basename
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '', $basename);
        
        // Generate unique filename
        $filename = $basename . '_' . time() . '_' . $this->generate_token(8) . '.' . $extension;
        
        return $filename;
    }

    /**
     * Set security headers
     */
    public function set_security_headers() {
        if ($this->config['force_https'] && !$this->CI->input->is_https()) {
            redirect('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], 'location', 301);
        }

        foreach ($this->config['security_headers'] as $header => $value) {
            header($header . ': ' . $value);
        }

        if ($this->config['hsts_enabled']) {
            header('Strict-Transport-Security: max-age=' . $this->config['hsts_max_age']);
        }

        if ($this->config['csp_enabled']) {
            header('Content-Security-Policy: ' . $this->config['csp_policy']);
        }
    }

    /**
     * Log security event
     */
    public function log_security_event($event_type, $description, $user_id = null, $ip_address = null) {
        if (!$this->config['log_security_events']) {
            return;
        }

        $data = array(
            'event_type' => $event_type,
            'description' => $description,
            'user_id' => $user_id ?: $this->CI->session->userdata('user_id'),
            'ip_address' => $ip_address ?: $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('security_logs', $data);
    }

    /**
     * Check user permissions
     */
    public function check_permission($module, $action, $user_role = null) {
        if (!$user_role) {
            $user_role = $this->CI->session->userdata('role');
        }

        $this->CI->load->model('User_model');
        return $this->CI->User_model->has_permission($user_role, $module, $action);
    }

    /**
     * Anonymize sensitive data
     */
    public function anonymize_data($data, $fields_to_anonymize) {
        foreach ($fields_to_anonymize as $field) {
            if (isset($data[$field])) {
                $data[$field] = $this->anonymize_field($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Anonymize individual field
     */
    private function anonymize_field($value) {
        if (empty($value)) {
            return $value;
        }

        // For names, keep first letter and replace rest with asterisks
        if (preg_match('/^[A-Za-z\s]+$/', $value)) {
            $words = explode(' ', $value);
            $anonymized = array();
            foreach ($words as $word) {
                if (strlen($word) > 1) {
                    $anonymized[] = substr($word, 0, 1) . str_repeat('*', strlen($word) - 1);
                } else {
                    $anonymized[] = $word;
                }
            }
            return implode(' ', $anonymized);
        }

        // For numbers (NIK, phone), replace middle digits
        if (preg_match('/^[0-9]+$/', $value)) {
            if (strlen($value) > 4) {
                return substr($value, 0, 2) . str_repeat('*', strlen($value) - 4) . substr($value, -2);
            }
            return str_repeat('*', strlen($value));
        }

        // For emails, keep first part and domain
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $value);
            if (strlen($parts[0]) > 2) {
                $parts[0] = substr($parts[0], 0, 2) . str_repeat('*', strlen($parts[0]) - 2);
            }
            return implode('@', $parts);
        }

        // Default: replace with asterisks
        return str_repeat('*', strlen($value));
    }

    /**
     * Generate audit trail entry
     */
    public function generate_audit_trail($table_name, $record_id, $action, $old_data = null, $new_data = null) {
        if (!$this->config['audit_enabled'] || !in_array($table_name, $this->config['audit_tables'])) {
            return;
        }

        $audit_data = array(
            'table_name' => $table_name,
            'record_id' => $record_id,
            'action' => $action,
            'old_values' => $old_data ? json_encode($old_data) : null,
            'new_values' => $new_data ? json_encode($new_data) : null,
            'user_id' => $this->CI->session->userdata('user_id'),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->CI->db->insert('audit_trail', $audit_data);
    }

    /**
     * Clean up old security logs
     */
    public function cleanup_security_logs() {
        $retention_days = $this->config['audit_retention_years'] * 365;
        $cutoff_date = date('Y-m-d H:i:s', time() - ($retention_days * 24 * 60 * 60));

        $this->CI->db->where('created_at <', $cutoff_date)
                     ->delete('security_logs');
    }

    /**
     * Clean up old audit trails
     */
    public function cleanup_audit_trails() {
        $retention_days = $this->config['audit_retention_years'] * 365;
        $cutoff_date = date('Y-m-d H:i:s', time() - ($retention_days * 24 * 60 * 60));

        $this->CI->db->where('created_at <', $cutoff_date)
                     ->delete('audit_trail');
    }
}

