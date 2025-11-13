<?php
/**
 * autoload.php
 * 
 * Simple PSR-4 autoloader
 * Menggantikan manual require/include untuk class
 * 
 * Letakkan di root folder: portal_tpl/autoload.php
 */

spl_autoload_register(function ($class) {
    // Namespace prefix untuk project ini
    $prefix = 'PortalTPL\\';
    
    // Base directory untuk namespace
    $base_dir = __DIR__ . '/src/';
    
    // Cek apakah class menggunakan namespace prefix kita
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // Bukan namespace kita, skip
        return;
    }
    
    // Ambil relative class name (tanpa prefix)
    $relative_class = substr($class, $len);
    
    // Ganti namespace separator dengan directory separator
    // Tambahkan .php extension
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Jika file ada, require
    if (file_exists($file)) {
        require $file;
    }
});

/**
 * Contoh penggunaan:
 * 
 * require_once __DIR__ . '/autoload.php';
 * 
 * use PortalTPL\Models\Karya;
 * use PortalTPL\Controllers\KaryaController;
 * 
 * $karya = new Karya();  // Otomatis load dari src/Models/Karya.php
 * $controller = new KaryaController();  // Otomatis load dari src/Controllers/KaryaController.php
 */