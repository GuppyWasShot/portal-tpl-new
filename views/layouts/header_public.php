<?php
/**
 * views/layouts/header_public.php
 * 
 * Header layout untuk halaman public
 * Digunakan di: detail_karya.php, galeri.php, index.php, tentang.php
 */

// Load config jika belum
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../../config/config.php';
}

// Set default page title jika tidak ada
if (!isset($page_title)) {
    $page_title = APP_NAME;
}

// Get current URL untuk active menu
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal showcase karya mahasiswa Teknologi Rekayasa Perangkat Lunak - Sekolah Vokasi IPB University">
    <meta name="keywords" content="TPL, Sekolah Vokasi, IPB, Portfolio, Karya Mahasiswa">
    <meta name="author" content="TPL - Sekolah Vokasi IPB">
    
    <title><?php echo htmlspecialchars($page_title); ?> - Portal TPL</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('img/favicon.ico'); ?>">
    
    <!-- Google Fonts (Poppins) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- MAIN CSS - INI YANG PENTING! -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    
    <style>
        /* Smooth scroll */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading animation */
        .page-loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .page-loading.hidden {
            display: none;
        }
        
        /* Alert message styles */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            animation: slideDown 0.3s ease-out;
        }
        
        .alert-success {
            background-color: #d1fae5;
            border: 1px solid #6ee7b7;
            color: #065f46;
        }
        
        .alert-error {
            background-color: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
        }
        
        .alert-info {
            background-color: #dbeafe;
            border: 1px solid #93c5fd;
            color: #1e40af;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Mobile menu animation */
        @media (max-width: 768px) {
            .mobile-menu {
                transition: max-height 0.3s ease-out;
                overflow: hidden;
            }
            
            .mobile-menu.closed {
                max-height: 0;
            }
            
            .mobile-menu.open {
                max-height: 500px;
            }
        }
    </style>
</head>
<body>
    
    <!-- Loading Screen -->
    <div class="page-loading" id="pageLoading">
        <div style="text-align: center;">
            <div style="border: 4px solid #f3f3f3; border-top: 4px solid #412358; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
            <p style="color: #412358; margin-top: 1rem;">Memuat...</p>
        </div>
    </div>
    
    <style>
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <script>
        // Hide loading screen when page fully loaded
        window.addEventListener('load', function() {
            document.getElementById('pageLoading').classList.add('hidden');
        });
    </script>

    <!-- Header Navigation -->
    <header>
        <div class="logo">
            <img src="<?php echo asset('img/logotpl.png'); ?>" alt="Logo TPL">
            <span>Portal<em>TPL</em></span>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="desktop-nav">
            <a href="<?php echo base_url(''); ?>" 
               class="<?php echo ($current_page === 'index') ? 'active' : ''; ?>">
                Beranda
            </a>
            <a href="<?php echo base_url('galeri'); ?>" 
               class="<?php echo ($current_page === 'galeri') ? 'active' : ''; ?>">
                Galeri Karya
            </a>
            <a href="<?php echo base_url('tentang'); ?>" 
               class="<?php echo ($current_page === 'tentang') ? 'active' : ''; ?>">
                Tentang TPL
            </a>
            <a href="<?php echo base_url('admin'); ?>" 
               class="btn-login">
                Login Admin
            </a>
        </nav>
        
        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Toggle menu">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </header>
    
    <!-- Mobile Navigation -->
    <div class="mobile-menu closed" id="mobileMenu">
        <nav>
            <a href="<?php echo base_url(''); ?>" 
               class="<?php echo ($current_page === 'index') ? 'active' : ''; ?>">
                Beranda
            </a>
            <a href="<?php echo base_url('galeri'); ?>" 
               class="<?php echo ($current_page === 'galeri') ? 'active' : ''; ?>">
                Galeri Karya
            </a>
            <a href="<?php echo base_url('tentang'); ?>" 
               class="<?php echo ($current_page === 'tentang') ? 'active' : ''; ?>">
                Tentang TPL
            </a>
            <a href="<?php echo base_url('admin'); ?>" 
               class="btn-login">
                Login Admin
            </a>
        </nav>
    </div>
    
    <script>
        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        
        mobileMenuBtn.addEventListener('click', function() {
            if (mobileMenu.classList.contains('closed')) {
                mobileMenu.classList.remove('closed');
                mobileMenu.classList.add('open');
            } else {
                mobileMenu.classList.remove('open');
                mobileMenu.classList.add('closed');
            }
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = mobileMenuBtn.contains(event.target) || mobileMenu.contains(event.target);
            
            if (!isClickInside && mobileMenu.classList.contains('open')) {
                mobileMenu.classList.remove('open');
                mobileMenu.classList.add('closed');
            }
        });
    </script>
    
    <!-- Flash Messages -->
    <?php if (flash('success')): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars(flash('success')); ?>
    </div>
    <?php endif; ?>
    
    <?php if (flash('error')): ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars(flash('error')); ?>
    </div>
    <?php endif; ?>
    
    <?php if (flash('info')): ?>
    <div class="alert alert-info">
        <?php echo htmlspecialchars(flash('info')); ?>
    </div>
    <?php endif; ?>
    
    <!-- Main Content Start -->
    <main>