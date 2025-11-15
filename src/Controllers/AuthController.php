<?php
// src/Controllers/AuthController.php

namespace PortalTPL\Controllers;

use PortalTPL\Services\Auth;

/**
 * Controller untuk authentication
 * Handle login, logout, dan session management
 */
class AuthController {
    private Auth $authService;
    
    public function __construct() {
        $this->authService = new Auth();
    }
    
    /**
     * Show login page
     */
    public function showLogin(): void {
        // Jika sudah login, redirect ke dashboard
        if ($this->authService->isLoggedIn()) {
            redirect('admin/dashboard');
        }
        
        $page_title = "Login Admin";
        require __DIR__ . '/../../views/admin/login.php';
    }
    
    /**
     * Process login
     */
    public function processLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/login');
        }
        
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Attempt login
        $result = $this->authService->login($username, $password);
        
        if ($result['success']) {
            // Login berhasil
            flash('success', 'Selamat datang, ' . $result['admin']['username'] . '!');
            redirect('admin/dashboard');
        } else {
            // Login gagal
            flash('error', $result['message']);
            setOldInput(['username' => $username]);
            redirect('admin/login');
        }
    }
    
    /**
     * Logout
     */
    public function logout(): void {
        $this->authService->logout();
        flash('info', 'Anda telah logout');
        redirect('admin/login');
    }
    
    /**
     * Require login (wrapper untuk authService)
     */
    public function requireLogin(): void {
        $this->authService->requireLogin();
    }
    
    /**
     * Show change password form
     */
    public function showChangePassword(): void {
        $this->authService->requireLogin();
        
        $page_title = "Ubah Password";
        require __DIR__ . '/../../views/admin/change_password.php';
    }
    
    /**
     * Process change password
     */
    public function processChangePassword(): void {
        $this->authService->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/change-password');
        }
        
        $admin = $this->authService->getCurrentAdmin();
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validasi
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            flash('error', 'Semua field harus diisi');
            redirect('admin/change-password');
        }
        
        if ($newPassword !== $confirmPassword) {
            flash('error', 'Password baru dan konfirmasi tidak cocok');
            redirect('admin/change-password');
        }
        
        // Change password
        $result = $this->authService->changePassword(
            $admin['id'],
            $oldPassword,
            $newPassword
        );
        
        if ($result['success']) {
            flash('success', $result['message']);
            redirect('admin/dashboard');
        } else {
            flash('error', $result['message']);
            redirect('admin/change-password');
        }
    }
}