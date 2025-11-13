<?php
/**
 * public/index.php
 * 
 * Entry point untuk semua request
 * Router sederhana untuk Portal TPL
 * 
 * URL akan diproses menjadi:
 * /detail?id=1 → KaryaController->showDetail(1)
 * /galeri → KaryaController->showGaleri()
 * /submit-rating → KaryaController->submitRating()
 */

// Load autoloader
require_once __DIR__ . '/../autoload.php';

// Import controllers yang dibutuhkan
use PortalTPL\Controllers\KaryaController;
// use PortalTPL\Controllers\AuthController; // Untuk admin nanti

// Error handling untuk development (matikan di production!)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Parse URL
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$path = str_replace($script_name, '', $request_uri);
$path = trim($path, '/');

// Pisahkan path dan query string
$path_parts = explode('?', $path);
$route = $path_parts[0];

// ============================================
// ROUTING LOGIC
// ============================================

try {
    switch ($route) {
        // ===== PUBLIC ROUTES =====
        
        case '':
        case 'home':
            // Homepage - untuk sementara redirect ke galeri
            header("Location: /galeri");
            exit();
            break;
            
        case 'detail':
            // Detail karya: /detail?id=1
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($id <= 0) {
                header("Location: /galeri");
                exit();
            }
            
            $controller = new KaryaController();
            $controller->showDetail($id);
            break;
            
        case 'galeri':
            // Galeri dengan filter: /galeri?search=...&kategori[]=...
            $controller = new KaryaController();
            $controller->showGaleri();
            break;
            
        case 'submit-rating':
            // Submit rating (POST only)
            $controller = new KaryaController();
            $controller->submitRating();
            break;
            
        // ===== ADMIN ROUTES =====
        // TODO: Implement AuthController untuk login, dashboard, dll
        
        case 'admin':
            // Redirect ke admin login untuk sementara
            header("Location: /admin/login");
            exit();
            break;
            
        // ===== 404 NOT FOUND =====
        default:
            http_response_code(404);
            echo "
            <!DOCTYPE html>
            <html>
            <head>
                <title>404 - Halaman Tidak Ditemukan</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                    }
                    .container {
                        text-align: center;
                    }
                    h1 {
                        font-size: 120px;
                        margin: 0;
                    }
                    p {
                        font-size: 24px;
                    }
                    a {
                        color: white;
                        text-decoration: underline;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>404</h1>
                    <p>Halaman tidak ditemukan</p>
                    <p><a href='/galeri'>← Kembali ke Galeri</a></p>
                </div>
            </body>
            </html>
            ";
            break;
    }
    
} catch (Exception $e) {
    // Error handling global
    http_response_code(500);
    
    // Di production, jangan tampilkan error detail
    if (ini_get('display_errors') == '1') {
        // Development mode
        echo "<h1>Error</h1>";
        echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        // Production mode
        echo "<h1>Terjadi Kesalahan</h1>";
        echo "<p>Silakan coba lagi nanti.</p>";
        echo "<p><a href='/galeri'>← Kembali ke Galeri</a></p>";
    }
}