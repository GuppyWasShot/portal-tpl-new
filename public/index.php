<?php
/**
 * public/index.php
 * 
 * Entry Point - Router untuk semua request
 */

// Load autoloader (ini akan load config juga)
require_once __DIR__ . '/../autoload.php';

// Import Controllers
use PortalTPL\Controllers\KaryaController;
use PortalTPL\Controllers\AuthController;

// ============================================
// PARSE URL
// ============================================
$request_uri = $_SERVER['REQUEST_URI'];

// Remove query string
$path = strtok($request_uri, '?');

// Remove base URL if exists
if (BASE_URL !== '') {
    $path = str_replace(BASE_URL, '', $path);
}

// Clean path
$path = trim($path, '/');

// Split path into segments
$segments = $path === '' ? [] : explode('/', $path);

// ============================================
// ROUTING LOGIC
// ============================================
try {
    // Get route (first segment)
    $route = $segments[0] ?? '';
    
    switch ($route) {
        // ===== HOME PAGE =====
        case '':
        case 'home':
            $controller = new KaryaController();
            $controller->showHome();
            break;
            
        // ===== GALERI =====
        case 'galeri':
            $controller = new KaryaController();
            $controller->showGaleri();
            break;
            
        // ===== DETAIL KARYA =====
        case 'detail':
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            if ($id <= 0) {
                redirect('galeri');
            }
            $controller = new KaryaController();
            $controller->showDetail($id);
            break;
            
        // ===== SUBMIT RATING =====
        case 'submit-rating':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                redirect('galeri');
            }
            $controller = new KaryaController();
            $controller->submitRating();
            break;
            
        // ===== TENTANG TPL =====
        case 'tentang':
            $page_title = "Tentang TPL";
            require VIEW_PATH . '/public/tentang.php';
            break;
            
        // ===== ADMIN ROUTES =====
        case 'admin':
            $action = $segments[1] ?? 'login';
            $authController = new AuthController();
            
            switch ($action) {
                case 'login':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        $authController->processLogin();
                    } else {
                        $authController->showLogin();
                    }
                    break;
                    
                case 'logout':
                    $authController->logout();
                    break;
                    
                case 'dashboard':
                    $authController->requireLogin();
                    $page_title = "Dashboard Admin";
                    require VIEW_PATH . '/admin/dashboard.php';
                    break;
                    
                default:
                    // Default redirect to login
                    redirect('admin/login');
                    break;
            }
            break;
            
        // ===== 404 NOT FOUND =====
        default:
            http_response_code(404);
            $page_title = "404 - Halaman Tidak Ditemukan";
            require VIEW_PATH . '/errors/404.php';
            break;
    }
    
} catch (Exception $e) {
    // ===== ERROR HANDLING =====
    http_response_code(500);
    
    if (APP_ENV === 'development') {
        // Development: Show full error
        echo "<div style='font-family: monospace; padding: 2rem; background: #fee; border: 3px solid #c33; margin: 2rem; border-radius: 8px;'>";
        echo "<h1 style='color: #c33; margin: 0 0 1rem 0;'>⚠️ Error 500</h1>";
        echo "<h2 style='color: #666; font-size: 1.2rem; margin-bottom: 1rem;'>" . htmlspecialchars($e->getMessage()) . "</h2>";
        echo "<div style='background: white; padding: 1rem; border-radius: 4px; overflow-x: auto;'>";
        echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>";
        echo "<strong>Line:</strong> " . $e->getLine() . "<br><br>";
        echo "<strong>Stack Trace:</strong><br>";
        echo "<pre style='font-size: 0.85rem; line-height: 1.4;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        echo "</div>";
        echo "<p style='margin-top: 1rem; color: #666;'><a href='" . base_url('galeri') . "' style='color: #c33;'>← Kembali ke Galeri</a></p>";
        echo "</div>";
    } else {
        // Production: Show generic error
        $page_title = "500 - Terjadi Kesalahan";
        require VIEW_PATH . '/errors/500.php';
    }
}