<?php
// ============================================
// src/Models/Category.php
// ============================================

namespace PortalTPL\Models;

use PortalTPL\Database\Database;
use mysqli;

/**
 * Model untuk tbl_category
 * Untuk dropdown, filter, dll
 */
class Category {
    private mysqli $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get all categories
     * @return array Array of categories
     */
    public function getAllCategories(): array {
        $query = "SELECT * FROM tbl_category ORDER BY nama_kategori ASC";
        $result = $this->db->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get category by ID
     * @param int $id
     * @return array|null
     */
    public function getCategoryById(int $id): ?array {
        $query = "SELECT * FROM tbl_category WHERE id_kategori = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
}
