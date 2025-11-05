<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security_hooks {

    private $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->library('security_library');
    }

    /**
     * Set security headers
     */
    public function set_security_headers() {
        $this->CI->security_library->set_security_headers();
    }

    /**
     * Check session security
     */
    public function check_session_security() {
        // Skip for login and API endpoints
        $uri = $this->CI->uri->uri_string();
        if (strpos($uri, 'auth/login') !== false || strpos($uri, 'api/') !== false) {
            return;
        }

        // Check if user is logged in
        if (!$this->CI->session->userdata('logged_in')) {
            // Store the requested URL for redirect after login
            $this->CI->session->set_userdata('redirect_after_login', current_url());
            redirect('auth/login');
        }

        // Check session timeout
        $last_activity = $this->CI->session->userdata('last_activity');
        $session_timeout = $this->CI->config->item('sess_expiration');
        
        if ($last_activity && (time() - $last_activity) > $session_timeout) {
            $this->CI->session->sess_destroy();
            $this->CI->session->set_flashdata('error', 'Session telah berakhir. Silakan login kembali.');
            redirect('auth/login');
        }

        // Update last activity
        $this->CI->session->set_userdata('last_activity', time());
    }

    /**
     * Log user activity
     */
    public function log_user_activity() {
        // Skip for AJAX requests and API endpoints
        if ($this->CI->input->is_ajax_request() || strpos($this->CI->uri->uri_string(), 'api/') !== false) {
            return;
        }

        $user_id = $this->CI->session->userdata('user_id');
        if (!$user_id) {
            return;
        }

        $activity_data = array(
            'user_id' => $user_id,
            'controller' => $this->CI->router->class,
            'method' => $this->CI->router->method,
            'uri' => $this->CI->uri->uri_string(),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'timestamp' => date('Y-m-d H:i:s')
        );

        // Log to database
        $this->CI->db->insert('user_activity_logs', $activity_data);
    }

    /**
     * Check CSRF protection
     */
    public function check_csrf_protection() {
        // Skip for GET requests and API endpoints
        if ($this->CI->input->method() === 'get' || strpos($this->CI->uri->uri_string(), 'api/') !== false) {
            return;
        }

        // Skip for excluded URIs
        $exclude_uris = $this->CI->config->item('csrf_exclude_uris');
        $current_uri = $this->CI->uri->uri_string();
        
        foreach ($exclude_uris as $exclude_uri) {
            if (preg_match('/' . $exclude_uri . '/', $current_uri)) {
                return;
            }
        }

        // Check CSRF token
        $csrf_token = $this->CI->input->post($this->CI->config->item('csrf_token_name'));
        $csrf_cookie = $this->CI->input->cookie($this->CI->config->item('csrf_cookie_name'));

        if (!$csrf_token || !$csrf_cookie || !hash_equals($csrf_cookie, $csrf_token)) {
            $this->CI->security_library->log_security_event('CSRF_ATTACK', 'CSRF token mismatch', null, $this->CI->input->ip_address());
            show_error('CSRF token mismatch. Please refresh the page and try again.', 403);
        }
    }

    /**
     * Rate limiting
     */
    public function rate_limiting() {
        $ip_address = $this->CI->input->ip_address();
        $current_time = time();
        $window_size = 3600; // 1 hour
        $max_requests = 1000; // Max requests per hour

        // Clean old entries
        $this->CI->db->where('timestamp <', $current_time - $window_size)
                     ->delete('rate_limits');

        // Count current requests
        $current_requests = $this->CI->db->where('ip_address', $ip_address)
                                        ->where('timestamp >', $current_time - $window_size)
                                        ->count_all_results('rate_limits');

        if ($current_requests >= $max_requests) {
            $this->CI->security_library->log_security_event('RATE_LIMIT_EXCEEDED', 'Rate limit exceeded for IP: ' . $ip_address, null, $ip_address);
            show_error('Rate limit exceeded. Please try again later.', 429);
        }

        // Record this request
        $this->CI->db->insert('rate_limits', array(
            'ip_address' => $ip_address,
            'timestamp' => $current_time
        ));
    }

    /**
     * Input sanitization
     */
    public function sanitize_input() {
        // Skip for API endpoints
        if (strpos($this->CI->uri->uri_string(), 'api/') !== false) {
            return;
        }

        // Sanitize POST data
        if ($this->CI->input->post()) {
            $_POST = $this->CI->security_library->sanitize_input($_POST);
        }

        // Sanitize GET data
        if ($this->CI->input->get()) {
            $_GET = $this->CI->security_library->sanitize_input($_GET);
        }
    }

    /**
     * Check for suspicious activity
     */
    public function check_suspicious_activity() {
        $user_id = $this->CI->session->userdata('user_id');
        if (!$user_id) {
            return;
        }

        $ip_address = $this->CI->input->ip_address();
        $user_agent = $this->CI->input->user_agent();

        // Check for IP changes
        $last_ip = $this->CI->session->userdata('last_ip');
        if ($last_ip && $last_ip !== $ip_address) {
            $this->CI->security_library->log_security_event('IP_CHANGE', 'User IP changed from ' . $last_ip . ' to ' . $ip_address, $user_id, $ip_address);
        }

        // Check for user agent changes
        $last_user_agent = $this->CI->session->userdata('last_user_agent');
        if ($last_user_agent && $last_user_agent !== $user_agent) {
            $this->CI->security_library->log_security_event('USER_AGENT_CHANGE', 'User agent changed', $user_id, $ip_address);
        }

        // Update session with current IP and user agent
        $this->CI->session->set_userdata('last_ip', $ip_address);
        $this->CI->session->set_userdata('last_user_agent', $user_agent);
    }

    /**
     * Database query logging
     */
    public function log_database_queries() {
        // Only log in development or when explicitly enabled
        if (ENVIRONMENT !== 'development' && !$this->CI->config->item('log_queries')) {
            return;
        }

        $queries = $this->CI->db->queries;
        if (!empty($queries)) {
            $log_data = array(
                'queries' => json_encode($queries),
                'execution_time' => $this->CI->db->query_times,
                'memory_usage' => memory_get_usage(true),
                'timestamp' => date('Y-m-d H:i:s')
            );

            $this->CI->db->insert('query_logs', $log_data);
        }
    }

    /**
     * Cleanup old logs
     */
    public function cleanup_old_logs() {
        // Run cleanup only once per day
        $last_cleanup = $this->CI->session->userdata('last_cleanup');
        if ($last_cleanup && (time() - $last_cleanup) < 86400) {
            return;
        }

        $this->CI->security_library->cleanup_security_logs();
        $this->CI->security_library->cleanup_audit_trails();

        // Cleanup old user activity logs
        $retention_days = 90; // 3 months
        $cutoff_date = date('Y-m-d H:i:s', time() - ($retention_days * 24 * 60 * 60));
        
        $this->CI->db->where('timestamp <', $cutoff_date)
                     ->delete('user_activity_logs');

        // Cleanup old query logs
        $this->CI->db->where('timestamp <', $cutoff_date)
                     ->delete('query_logs');

        $this->CI->session->set_userdata('last_cleanup', time());
    }

    /**
     * Check file upload security
     */
    public function check_file_upload_security() {
        if (empty($_FILES)) {
            return;
        }

        foreach ($_FILES as $file) {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $errors = $this->CI->security_library->validate_file_upload($file);
                if (!empty($errors)) {
                    $this->CI->security_library->log_security_event('FILE_UPLOAD_BLOCKED', 'File upload blocked: ' . implode(', ', $errors), null, $this->CI->input->ip_address());
                    show_error('File upload blocked: ' . implode(', ', $errors), 400);
                }
            }
        }
    }

    /**
     * Monitor for SQL injection attempts
     */
    public function monitor_sql_injection() {
        $input_data = array_merge($_GET, $_POST);
        
        foreach ($input_data as $key => $value) {
            if (is_string($value)) {
                // Check for common SQL injection patterns
                $patterns = array(
                    '/(\bunion\b.*\bselect\b)/i',
                    '/(\bselect\b.*\bfrom\b)/i',
                    '/(\binsert\b.*\binto\b)/i',
                    '/(\bupdate\b.*\bset\b)/i',
                    '/(\bdelete\b.*\bfrom\b)/i',
                    '/(\bdrop\b.*\btable\b)/i',
                    '/(\bcreate\b.*\btable\b)/i',
                    '/(\balter\b.*\btable\b)/i',
                    '/(\bexec\b|\bexecute\b)/i',
                    '/(\bscript\b)/i',
                    '/(\bjavascript\b)/i',
                    '/(\bvbscript\b)/i'
                );

                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        $this->CI->security_library->log_security_event('SQL_INJECTION_ATTEMPT', 'SQL injection attempt detected in ' . $key . ': ' . $value, null, $this->CI->input->ip_address());
                        show_error('Suspicious activity detected.', 403);
                    }
                }
            }
        }
    }
}

