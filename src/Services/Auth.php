<?php
// src/Services/Auth.php

namespace PortalTPL\Services;

use PortalTPL\Models\Admin;
use PortalTPL\Models\ActivityLog;
use PortalTPL\Database\Database;
use mysqli;

/**
 * Authentication Service
 * Menangani login, logout, rate limiting, dan session management
 */
class Auth {
    private Admin $adminModel;
    private ActivityLog $activityLog;
    private mysqli $db;
    
    public function __construct() {
        $this->adminModel = new Admin();
        $this->activityLog = new ActivityLog();
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Login admin
     * 
     * @param string $username
     * @param string $password
     * @return array ['success' => bool, 'message' => string, 'admin' => array|null]
     */
    public function login(string $username, string $password): array {
        // Start session jika belum
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $ipAddress = $this->getIpAddress();
        
        // Check rate limiting
        if ($this->isIpLocked($ipAddress)) {
            $this->logLoginAttempt($username, $ipAddress, 'Locked');
            
            return [
                'success' => false,
                'message' => 'Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . (MAX_LOGIN_ATTEMPTS) . ' menit.',
                'admin' => null
            ];
        }
        
        // Validasi input
        if (empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Username dan password tidak boleh kosong',
                'admin' => null
            ];
        }
        
        // Verify credentials
        $admin = $this->adminModel->verifyLogin($username, $password);
        
        if ($admin === false) {
            // Login failed
            $this->logLoginAttempt($username, $ipAddress, 'Failed');
            
            $remainingAttempts = $this->getRemainingAttempts($ipAddress);
            
            return [
                'success' => false,
                'message' => 'Username atau password salah. Sisa percobaan: ' . $remainingAttempts,
                'admin' => null
            ];
        }
        
        // Login success
        $this->logLoginAttempt($username, $ipAddress, 'Success');
        $this->resetFailedAttempts($ipAddress);
        
        // Set session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id_admin'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['login_time'] = time();
        
        // Update last login
        $this->adminModel->updateLastLogin($admin['id_admin']);
        
        // Log activity
        $this->activityLog->log(
            $admin['id_admin'],
            $admin['username'],
            'Login ke sistem'
        );
        
        return [
            'success' => true,
            'message' => 'Login berhasil',
            'admin' => $admin
        ];
    }
    
    /**
     * Logout admin
     * 
     * @return bool
     */
    public function logout(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Log activity sebelum destroy session
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_username'])) {
            $this->activityLog->log(
                $_SESSION['admin_id'],
                $_SESSION['admin_username'],
                'Logout dari sistem'
            );
        }
        
        // Destroy session
        session_unset();
        session_destroy();
        
        return true;
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool
     */
    public function isLoggedIn(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Require login (redirect jika belum login)
     * 
     * @param string $redirectUrl URL redirect jika belum login
     */
    public function requireLogin(string $redirectUrl = '/admin/login'): void {
        if (!$this->isLoggedIn()) {
            flash('error', 'Silakan login terlebih dahulu');
            redirect($redirectUrl);
        }
    }
    
    /**
     * Get current admin data
     * 
     * @return array|null
     */
    public function getCurrentAdmin(): ?array {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'],
            'username' => $_SESSION['admin_username'],
            'email' => $_SESSION['admin_email'] ?? null
        ];
    }
    
    /**
     * Check if IP is locked (too many failed attempts)
     * 
     * @param string $ipAddress
     * @return bool
     */
    private function isIpLocked(string $ipAddress): bool {
        $lockoutTime = LOCKOUT_TIME / 60; // Convert to minutes
        
        $query = "SELECT COUNT(*) as attempts 
                  FROM tbl_admin_logs 
                  WHERE ip_address = ? 
                  AND status = 'Failed' 
                  AND log_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $ipAddress, $lockoutTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['attempts'] >= MAX_LOGIN_ATTEMPTS;
    }
    
    /**
     * Get remaining login attempts
     * 
     * @param string $ipAddress
     * @return int
     */
    private function getRemainingAttempts(string $ipAddress): int {
        $lockoutTime = LOCKOUT_TIME / 60;
        
        $query = "SELECT COUNT(*) as attempts 
                  FROM tbl_admin_logs 
                  WHERE ip_address = ? 
                  AND status = 'Failed' 
                  AND log_time > DATE_SUB(NOW(), INTERVAL ? MINUTE)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $ipAddress, $lockoutTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return max(0, MAX_LOGIN_ATTEMPTS - $row['attempts']);
    }
    
    /**
     * Reset failed login attempts
     * 
     * @param string $ipAddress
     * @return bool
     */
    private function resetFailedAttempts(string $ipAddress): bool {
        $query = "DELETE FROM tbl_admin_logs 
                  WHERE ip_address = ? 
                  AND status = 'Failed'";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $ipAddress);
        
        return $stmt->execute();
    }
    
    /**
     * Log login attempt
     * 
     * @param string $username
     * @param string $ipAddress
     * @param string $status 'Success', 'Failed', atau 'Locked'
     * @return bool
     */
    private function logLoginAttempt(string $username, string $ipAddress, string $status): bool {
        $query = "INSERT INTO tbl_admin_logs (username_attempt, ip_address, status) 
                  VALUES (?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $ipAddress, $status);
        
        return $stmt->execute();
    }
    
    /**
     * Get IP address (handle proxy/load balancer)
     * 
     * @return string
     */
    private function getIpAddress(): string {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    
    /**
     * Change password
     * 
     * @param int $adminId
     * @param string $oldPassword
     * @param string $newPassword
     * @return array ['success' => bool, 'message' => string]
     */
    public function changePassword(int $adminId, string $oldPassword, string $newPassword): array {
        $admin = $this->adminModel->getById($adminId);
        
        if (!$admin) {
            return [
                'success' => false,
                'message' => 'Admin tidak ditemukan'
            ];
        }
        
        // Verify old password
        if (!password_verify($oldPassword, $admin['password'])) {
            return [
                'success' => false,
                'message' => 'Password lama tidak sesuai'
            ];
        }
        
        // Validate new password
        if (strlen($newPassword) < 6) {
            return [
                'success' => false,
                'message' => 'Password baru minimal 6 karakter'
            ];
        }
        
        // Update password
        $updated = $this->adminModel->update($adminId, ['password' => $newPassword]);
        
        if ($updated) {
            // Log activity
            $this->activityLog->log(
                $adminId,
                $admin['username'],
                'Mengubah password'
            );
            
            return [
                'success' => true,
                'message' => 'Password berhasil diubah'
            ];
        }
        
        return [
            'success' => false,
            'message' => 'Gagal mengubah password'
        ];
    }
}