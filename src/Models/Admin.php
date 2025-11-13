<?php
// src/Models/Admin.php

namespace PortalTPL\Models;

use PortalTPL\Database\Database;
use mysqli;

/**
 * Model untuk tbl_admin
 * Menangani data administrator
 */
class Admin {
    private mysqli $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get admin by username
     * 
     * @param string $username
     * @return array|null Admin data atau null jika tidak ditemukan
     */
    public function getByUsername(string $username): ?array {
        $query = "SELECT * FROM tbl_admin WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Get admin by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function getById(int $id): ?array {
        $query = "SELECT * FROM tbl_admin WHERE id_admin = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Verify password
     * 
     * @param string $username
     * @param string $password Plain text password
     * @return array|false Admin data jika berhasil, false jika gagal
     */
    public function verifyLogin(string $username, string $password) {
        $admin = $this->getByUsername($username);
        
        if (!$admin) {
            return false;
        }
        
        if (password_verify($password, $admin['password'])) {
            return $admin;
        }
        
        return false;
    }
    
    /**
     * Create new admin
     * 
     * @param string $username
     * @param string $password Plain text password (akan di-hash)
     * @param string $email
     * @return int|false ID admin baru atau false jika gagal
     */
    public function create(string $username, string $password, string $email): int|false {
        // Hash password
        $hashedPassword = password_hash($password, HASH_ALGO, ['cost' => HASH_COST]);
        
        $query = "INSERT INTO tbl_admin (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update admin
     * 
     * @param int $id
     * @param array $data Data yang akan diupdate
     * @return bool
     */
    public function update(int $id, array $data): bool {
        $fields = [];
        $values = [];
        $types = '';
        
        // Build dynamic update query
        foreach ($data as $key => $value) {
            if (in_array($key, ['username', 'email', 'password'])) {
                $fields[] = "$key = ?";
                
                // Hash password jika ada
                if ($key === 'password') {
                    $value = password_hash($value, HASH_ALGO, ['cost' => HASH_COST]);
                }
                
                $values[] = $value;
                $types .= 's';
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $values[] = $id;
        $types .= 'i';
        
        $query = "UPDATE tbl_admin SET " . implode(', ', $fields) . " WHERE id_admin = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }
    
    /**
     * Delete admin
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool {
        $query = "DELETE FROM tbl_admin WHERE id_admin = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Get all admins
     * 
     * @return array
     */
    public function getAll(): array {
        $query = "SELECT id_admin, username, email FROM tbl_admin ORDER BY username ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Check if username exists
     * 
     * @param string $username
     * @param int|null $excludeId ID admin yang dikecualikan (untuk update)
     * @return bool
     */
    public function usernameExists(string $username, ?int $excludeId = null): bool {
        $query = "SELECT COUNT(*) as count FROM tbl_admin WHERE username = ?";
        
        if ($excludeId !== null) {
            $query .= " AND id_admin != ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("si", $username, $excludeId);
        } else {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("s", $username);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['count'] > 0;
    }
    
    /**
     * Update last login time
     * 
     * @param int $id
     * @return bool
     */
    public function updateLastLogin(int $id): bool {
        $query = "UPDATE tbl_admin SET last_login = NOW() WHERE id_admin = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}