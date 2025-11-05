<?php
/**
 * CodeIgniter Router for PHP Built-in Server
 * This file handles URL rewriting for CodeIgniter when using PHP's built-in server.
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// Remove query string for file existence checks
$requested = $_SERVER['DOCUMENT_ROOT'] . $uri;

// If the request is for a file that exists, serve it directly
if (php_sapi_name() === 'cli-server' && is_file($requested)) {
    return false;
}

// If the request is for a directory that exists, serve index.php in that directory
if (is_dir($requested)) {
    $indexFile = rtrim($requested, '/') . '/index.php';
    if (file_exists($indexFile)) {
        include $indexFile;
        return true;
    }
}

// For all other requests, route through CodeIgniter's index.php
require_once __DIR__ . '/index.php';
