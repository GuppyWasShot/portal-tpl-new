<?php
// src/Models/Rating.php

namespace PortalTPL\Models;

use PortalTPL\Database\Database;
use mysqli;

/**
 * Model untuk tbl_rating
 * Menggantikan proses_rating.php prosedural
 */
class Rating {
    private mysqli $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Cek apakah user sudah pernah rating karya ini
     * 
     * @param int $id_project ID project
     * @param string $uuid UUID user dari session
     * @param string $ip_address IP address user
     * @return array|null Rating data atau null jika belum pernah rating
     */
    public function getUserRating(int $id_project, string $uuid, string $ip_address): ?array {
        $query = "SELECT * FROM tbl_rating 
                  WHERE id_project = ? 
                  AND (uuid_user = ? OR ip_address = ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iss", $id_project, $uuid, $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Submit rating baru
     * 
     * @param int $id_project ID project
     * @param string $uuid UUID user
     * @param string $ip_address IP address user
     * @param int $skor Skor rating (1-5)
     * @return bool Success status
     * @throws \Exception Jika sudah pernah rating atau validasi gagal
     */
    public function submitRating(int $id_project, string $uuid, string $ip_address, int $skor): bool {
        // Validasi skor
        if ($skor < 1 || $skor > 5) {
            throw new \Exception("Skor rating harus antara 1-5");
        }
        
        // Cek apakah sudah pernah rating
        if ($this->getUserRating($id_project, $uuid, $ip_address) !== null) {
            throw new \Exception("Anda sudah pernah memberikan rating untuk karya ini");
        }
        
        // Insert rating
        $query = "INSERT INTO tbl_rating (id_project, uuid_user, ip_address, skor) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issi", $id_project, $uuid, $ip_address, $skor);
        
        return $stmt->execute();
    }
    
    /**
     * Get average rating untuk karya tertentu
     * 
     * @param int $id_project ID project
     * @return array ['avg' => float, 'total' => int]
     */
    public function getAverageRating(int $id_project): array {
        $query = "SELECT 
                  AVG(skor) as avg_rating,
                  COUNT(*) as total_rating
                  FROM tbl_rating 
                  WHERE id_project = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_project);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        return [
            'avg' => $data['avg_rating'] ? (float)$data['avg_rating'] : 0,
            'total' => (int)$data['total_rating']
        ];
    }
}