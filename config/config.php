<?php
/**
 * config/config.php
 * 
 * File konfigurasi global untuk Portal TPL
 * Berisi konstanta dan pengaturan aplikasi
 */

// ============================================
// DATABASE CONFIGURATION
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_portal_tpl');

// ============================================
// APPLICATION CONFIGURATION
// ============================================
define('APP_NAME', 'Portal TPL');
define('APP_VERSION', '2.0.0');
define('APP_ENV', 'development'); // 'development' atau 'production'

// ============================================
// PATH CONFIGURATION
// ============================================
// Root path (adjust sesuai lokasi project)
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('VIEW_PATH', ROOT_PATH . '/views');

// URL Base (adjust sesuai environment)
// Jika di localhost/portal-tpl-new: BASE_URL = '/portal-tpl-new'
// Jika di root domain: BASE_URL = ''
define('BASE_URL', ''); // Kosongkan jika di root, atau '/portal-tpl-new' jika di subfolder

// ============================================
// UPLOAD CONFIGURATION
// ============================================
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_ALLOWED_TYPES', [
    'image/jpeg',
    'image/png',
    'image/jpg',
    'image/webp',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);

// ============================================
// SESSION CONFIGURATION
// ============================================
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'portal_tpl_session');

// ============================================
// SECURITY CONFIGURATION
// ============================================
define('HASH_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', 12);

// Login rate limiting
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 600); // 10 minutes in seconds

// ============================================
// PAGINATION CONFIGURATION
// ============================================
define('ITEMS_PER_PAGE', 12);

// ============================================
// ERROR REPORTING (sesuaikan dengan environment)
// ============================================
if (APP_ENV === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/error.log');
}

// ============================================
// TIMEZONE
// ============================================
date_default_timezone_set('Asia/Jakarta');

// ============================================
// HELPER FUNCTIONS
// ============================================

/**
 * Get base URL
 * @param string $path Path relatif dari base URL
 * @return string Full URL
 */
function base_url(string $path = ''): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    return $protocol . $host . BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Get asset URL
 * @param string $path Path ke asset (relatif dari public/assets/)
 * @return string Full URL ke asset
 */
function asset(string $path): string {
    return base_url('assets/' . ltrim($path, '/'));
}

/**
 * Redirect helper
 * @param string $url URL tujuan
 * @param int $statusCode HTTP status code
 */
function redirect(string $url, int $statusCode = 302): void {
    header("Location: " . base_url($url), true, $statusCode);
    exit();
}

/**
 * Flash message helper
 * @param string $key Key untuk message
 * @param mixed $value Value message
 * @return mixed Value jika $value null, void jika set
 */
function flash(string $key, $value = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if ($value === null) {
        // Get flash message
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
    
    // Set flash message
    $_SESSION['flash'][$key] = $value;
}

/**
 * Old input helper (untuk form validation)
 * @param string $key Input key
 * @param mixed $default Default value
 * @return mixed
 */
function old(string $key, $default = '') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return $_SESSION['old'][$key] ?? $default;
}

/**
 * Set old input
 * @param array $data
 */
function setOldInput(array $data): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $_SESSION['old'] = $data;
}

/**
 * Clear old input
 */
function clearOldInput(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    unset($_SESSION['old']);
}

/**
 * Format file size
 * @param int $bytes Size in bytes
 * @return string Formatted size
 */
function formatFileSize(int $bytes): string {
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    } else {
        return $bytes . ' bytes';
    }
}

/**
 * Format date to Indonesian
 * @param string $date Date string
 * @param string $format Format output
 * @return string Formatted date
 */
function formatDateIndo(string $date, string $format = 'd M Y'): string {
    $months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];
    
    $timestamp = strtotime($date);
    $formatted = date('d-M-Y H:i:s', $timestamp);
    
    foreach ($months as $key => $month) {
        $formatted = str_replace(date('M', mktime(0, 0, 0, $key + 1, 1)), $month, $formatted);
    }
    
    return $formatted;
}