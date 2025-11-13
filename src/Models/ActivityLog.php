<?php
// src/Models/ActivityLog.php

namespace PortalTPL\Models;

use PortalTPL\Database\Database;
use mysqli;

/**
 * Model untuk tbl_activity_logs
 * Mencatat aktivitas admin
 */
class ActivityLog {
    private mysqli $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Log aktivitas admin
     * 
     * @param int $id_admin ID admin yang melakukan aktivitas
     * @param string $username Username admin
     * @param string $action Deskripsi aktivitas
     * @param int|null $id_project ID project terkait (opsional)
     * @return bool
     */
    public function log(int $id_admin, string $username, string $action, ?int $id_project = null): bool {
        $query = "INSERT INTO tbl_activity_logs (id_admin, username, action, id_project) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issi", $id_admin, $username, $action, $id_project);
        
        return $stmt->execute();
    }
    
    /**
     * Get recent activities
     * 
     * @param int $limit Jumlah record yang diambil
     * @param int $offset Offset untuk pagination
     * @return array
     */
    public function getRecent(int $limit = 20, int $offset = 0): array {
        $query = "SELECT al.*, p.judul as project_title 
                  FROM tbl_activity_logs al
                  LEFT JOIN tbl_project p ON al.id_project = p.id_project
                  ORDER BY al.log_time DESC
                  LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get activities by admin
     * 
     * @param int $id_admin
     * @param int $limit
     * @return array
     */
    public function getByAdmin(int $id_admin, int $limit = 20): array {
        $query = "SELECT al.*, p.judul as project_title 
                  FROM tbl_activity_logs al
                  LEFT JOIN tbl_project p ON al.id_project = p.id_project
                  WHERE al.id_admin = ?
                  ORDER BY al.log_time DESC
                  LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $id_admin, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get activities by project
     * 
     * @param int $id_project
     * @return array
     */
    public function getByProject(int $id_project): array {
        $query = "SELECT al.* 
                  FROM tbl_activity_logs al
                  WHERE al.id_project = ?
                  ORDER BY al.log_time DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_project);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get activities in date range
     * 
     * @param string $startDate Format: Y-m-d
     * @param string $endDate Format: Y-m-d
     * @return array
     */
    public function getByDateRange(string $startDate, string $endDate): array {
        $query = "SELECT al.*, p.judul as project_title 
                  FROM tbl_activity_logs al
                  LEFT JOIN tbl_project p ON al.id_project = p.id_project
                  WHERE DATE(al.log_time) BETWEEN ? AND ?
                  ORDER BY al.log_time DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $startDate, $endDate);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Search activities
     * 
     * @param string $keyword Kata kunci pencarian
     * @param int $limit
     * @return array
     */
    public function search(string $keyword, int $limit = 50): array {
        $searchTerm = "%$keyword%";
        
        $query = "SELECT al.*, p.judul as project_title 
                  FROM tbl_activity_logs al
                  LEFT JOIN tbl_project p ON al.id_project = p.id_project
                  WHERE al.action LIKE ? OR al.username LIKE ?
                  ORDER BY al.log_time DESC
                  LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $searchTerm, $searchTerm, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get total count
     * 
     * @return int
     */
    public function getTotalCount(): int {
        $query = "SELECT COUNT(*) as total FROM tbl_activity_logs";
        $result = $this->db->query($query);
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    
    /**
     * Delete old logs (untuk maintenance)
     * 
     * @param int $daysOld Hapus log yang lebih tua dari X hari
     * @return int Jumlah record yang dihapus
     */
    public function deleteOldLogs(int $daysOld = 90): int {
        $query = "DELETE FROM tbl_activity_logs 
                  WHERE log_time < DATE_SUB(NOW(), INTERVAL ? DAY)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $daysOld);
        $stmt->execute();
        
        return $stmt->affected_rows;
    }
    
    /**
     * Get activity statistics
     * 
     * @return array
     */
    public function getStatistics(): array {
        $stats = [];
        
        // Total activities today
        $query = "SELECT COUNT(*) as total FROM tbl_activity_logs 
                  WHERE DATE(log_time) = CURDATE()";
        $result = $this->db->query($query);
        $stats['today'] = $result->fetch_assoc()['total'];
        
        // Total activities this week
        $query = "SELECT COUNT(*) as total FROM tbl_activity_logs 
                  WHERE YEARWEEK(log_time) = YEARWEEK(NOW())";
        $result = $this->db->query($query);
        $stats['this_week'] = $result->fetch_assoc()['total'];
        
        // Total activities this month
        $query = "SELECT COUNT(*) as total FROM tbl_activity_logs 
                  WHERE YEAR(log_time) = YEAR(NOW()) 
                  AND MONTH(log_time) = MONTH(NOW())";
        $result = $this->db->query($query);
        $stats['this_month'] = $result->fetch_assoc()['total'];
        
        // Most active admin
        $query = "SELECT username, COUNT(*) as total 
                  FROM tbl_activity_logs 
                  GROUP BY username 
                  ORDER BY total DESC 
                  LIMIT 1";
        $result = $this->db->query($query);
        $mostActive = $result->fetch_assoc();
        $stats['most_active_admin'] = $mostActive ? $mostActive['username'] : null;
        $stats['most_active_count'] = $mostActive ? $mostActive['total'] : 0;
        
        return $stats;
    }
}