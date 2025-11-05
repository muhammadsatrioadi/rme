<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Hooks
|--------------------------------------------------------------------------
|
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['pre_system'] = array(
    'class'    => 'Security_hooks',
    'function' => 'set_security_headers',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'check_session_security',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'check_csrf_protection',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'rate_limiting',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'sanitize_input',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'monitor_sql_injection',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['pre_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'check_file_upload_security',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['post_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'log_user_activity',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['post_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'check_suspicious_activity',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['post_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'log_database_queries',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);

$hook['post_controller'] = array(
    'class'    => 'Security_hooks',
    'function' => 'cleanup_old_logs',
    'filename' => 'Security_hooks.php',
    'filepath' => 'hooks'
);