<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Security Configuration
|--------------------------------------------------------------------------
|
| Konfigurasi keamanan untuk aplikasi RME sesuai standar Menkes
|
*/

// Encryption key untuk data sensitif
$config['encryption_key'] = 'RME_HOSPITAL_2024_SECURE_KEY_12345';

// Session security
$config['sess_driver'] = 'database';
$config['sess_cookie_name'] = 'rme_session';
$config['sess_expiration'] = 7200; // 2 jam
$config['sess_save_path'] = 'ci_sessions';
$config['sess_match_ip'] = TRUE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = TRUE;

// CSRF Protection
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'csrf_rme_token';
$config['csrf_cookie_name'] = 'csrf_rme_cookie';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array(
    'api/.*',
    'auth/login',
    'auth/logout'
);

// XSS Protection
$config['global_xss_filtering'] = TRUE;

// Password requirements
$config['password_min_length'] = 8;
$config['password_require_uppercase'] = TRUE;
$config['password_require_lowercase'] = TRUE;
$config['password_require_number'] = TRUE;
$config['password_require_special'] = TRUE;

// Login attempts
$config['max_login_attempts'] = 5;
$config['login_attempt_timeout'] = 900; // 15 menit

// File upload security
$config['upload_max_size'] = 5242880; // 5MB
$config['upload_allowed_types'] = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx');
$config['upload_path'] = './uploads/';

// Database security
$config['db_encrypt'] = TRUE;
$config['db_encrypt_fields'] = array(
    'mst_pasien' => array('nik', 'no_telepon', 'no_hp', 'email'),
    'mst_pegawai' => array('nik', 'no_telepon', 'no_hp', 'email'),
    'users' => array('password', 'email')
);

// Audit trail configuration
$config['audit_enabled'] = TRUE;
$config['audit_tables'] = array(
    'mst_pasien',
    'mst_pegawai',
    'mst_poliklinik',
    'mst_obat',
    'rekam_medis',
    'rawat_inap',
    'resep_obat',
    'users'
);

// Data retention policy
$config['data_retention_years'] = 10;
$config['audit_retention_years'] = 7;

// Backup configuration
$config['backup_enabled'] = TRUE;
$config['backup_schedule'] = 'daily';
$config['backup_retention_days'] = 30;

// API security
$config['api_enabled'] = FALSE;
$config['api_key_required'] = TRUE;
$config['api_rate_limit'] = 100; // requests per hour

// SSL/TLS configuration
$config['force_https'] = TRUE;
$config['hsts_enabled'] = TRUE;
$config['hsts_max_age'] = 31536000; // 1 year

// Content Security Policy
$config['csp_enabled'] = TRUE;
$config['csp_policy'] = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:; connect-src 'self';";

// Security headers
$config['security_headers'] = array(
    'X-Frame-Options' => 'DENY',
    'X-Content-Type-Options' => 'nosniff',
    'X-XSS-Protection' => '1; mode=block',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
    'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()'
);

// Logging configuration
$config['log_security_events'] = TRUE;
$config['log_failed_logins'] = TRUE;
$config['log_data_access'] = TRUE;
$config['log_data_modification'] = TRUE;

// Two-factor authentication
$config['2fa_enabled'] = FALSE;
$config['2fa_required_roles'] = array('Admin', 'Manager');

// Data anonymization
$config['anonymize_enabled'] = TRUE;
$config['anonymize_after_years'] = 5;

// Compliance settings
$config['gdpr_compliance'] = TRUE;
$config['hipaa_compliance'] = TRUE;
$config['menkes_compliance'] = TRUE;

