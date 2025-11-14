<?php
/**
 * autoload.php
 * 
 * PSR-4 Autoloader + Config Loader
 * Letakkan di root: portal_tpl/autoload.php
 */

// ============================================
// LOAD CONFIGURATION FIRST
// ============================================
require_once __DIR__ . '/config/config.php';

// ============================================
// PSR-4 AUTOLOADER
// ============================================
spl_autoload_register(function ($class) {
    // Namespace prefix untuk project ini
    $prefix = 'PortalTPL\\';
    
    // Base directory untuk namespace
    $base_dir = __DIR__ . '/src/';
    
    // Cek apakah class menggunakan namespace prefix kita
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Bukan namespace kita, skip
    }
    
    // Ambil relative class name (tanpa prefix)
    $relative_class = substr($class, $len);
    
    // Ganti namespace separator dengan directory separator
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Jika file ada, require
    if (file_exists($file)) {
        require $file;
    }
});

// ============================================
// ERROR HANDLER (Development Only)
// ============================================
if (APP_ENV === 'development') {
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        $error_types = [
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_NOTICE => 'Notice',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice'
        ];
        
        $type = $error_types[$errno] ?? 'Unknown Error';
        
        echo "<div style='background: #fee; border: 2px solid #c33; padding: 1rem; margin: 1rem; border-radius: 8px; font-family: monospace;'>";
        echo "<strong style='color: #c33;'>[$type]</strong> $errstr<br>";
        echo "<small style='color: #666;'>File: $errfile (Line: $errline)</small>";
        echo "</div>";
        
        return true; // Don't execute PHP internal error handler
    });
}

// ============================================
// SESSION CONFIGURATION
// ============================================
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'secure' => APP_ENV === 'production', // HTTPS only in production
        'httponly' => true,
        'samesite' => 'Strict'
    ]);
    session_start();
}

// ============================================
// TIMEZONE
// ============================================
date_default_timezone_set('Asia/Jakarta');