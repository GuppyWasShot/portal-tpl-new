<?php
// src/Models/Karya.php

namespace PortalTPL\Models;

use PortalTPL\Database\Database;
use mysqli;

/**
 * Model untuk tbl_project
 * Menggantikan query prosedural di detail_karya.php, galeri.php, dll
 */
class Karya {
    private mysqli $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Get karya by ID dengan semua relasi (kategori, rating, files, links)
     * 
     * @param int $id ID project
     * @param bool $publishedOnly Hanya ambil yang published
     * @return array|null Data karya atau null jika tidak ditemukan
     */
    public function getKaryaById(int $id, bool $publishedOnly = true): ?array {
        $statusCondition = $publishedOnly ? "AND p.status = 'Published'" : "";
        
        $query = "SELECT p.*, 
                  GROUP_CONCAT(DISTINCT c.nama_kategori ORDER BY c.nama_kategori SEPARATOR ', ') as kategori,
                  GROUP_CONCAT(DISTINCT c.warna_hex ORDER BY c.nama_kategori SEPARATOR ',') as warna,
                  AVG(r.skor) as avg_rating,
                  COUNT(DISTINCT r.id_rating) as total_rating
                  FROM tbl_project p
                  LEFT JOIN tbl_project_category pc ON p.id_project = pc.id_project
                  LEFT JOIN tbl_category c ON pc.id_kategori = c.id_kategori
                  LEFT JOIN tbl_rating r ON p.id_project = r.id_project
                  WHERE p.id_project = ? $statusCondition
                  GROUP BY p.id_project";
        
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
     * Get links untuk karya tertentu
     * 
     * @param int $id_project ID project
     * @return array Array of links
     */
    public function getLinks(int $id_project): array {
        $query = "SELECT * FROM tbl_project_links 
                  WHERE id_project = ? 
                  ORDER BY is_primary DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_project);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get files untuk karya tertentu
     * 
     * @param int $id_project ID project
     * @return array Array of files
     */
    public function getFiles(int $id_project): array {
        $query = "SELECT * FROM tbl_project_files 
                  WHERE id_project = ? 
                  ORDER BY id_file ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id_project);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Get snapshots (filter files yang ada di folder snapshots)
     * 
     * @param int $id_project ID project
     * @return array Array of snapshot files
     */
    public function getSnapshots(int $id_project): array {
        $files = $this->getFiles($id_project);
        return array_filter($files, function($file) {
            return strpos($file['file_path'], 'snapshots') !== false;
        });
    }
    
    /**
     * Get documents (filter files yang ada di folder files)
     * 
     * @param int $id_project ID project
     * @return array Array of document files
     */
    public function getDocuments(int $id_project): array {
        $files = $this->getFiles($id_project);
        return array_filter($files, function($file) {
            return strpos($file['file_path'], 'files') !== false;
        });
    }
    
    /**
     * Get all karya dengan filter dan sorting
     * 
     * @param array $filters Filter (search, kategori, sort)
     * @return array Array of karya
     */
    public function getAllKarya(array $filters = []): array {
        $where = ["p.status = 'Published'"];
        $params = [];
        $types = "";
        
        // Filter search
        if (!empty($filters['search'])) {
            $where[] = "(p.judul LIKE ? OR p.pembuat LIKE ? OR p.deskripsi LIKE ?)";
            $searchParam = "%{$filters['search']}%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= "sss";
        }
        
        // Filter kategori
        if (!empty($filters['kategori']) && is_array($filters['kategori'])) {
            $placeholders = implode(',', array_fill(0, count($filters['kategori']), '?'));
            $where[] = "pc.id_kategori IN ($placeholders)";
            foreach ($filters['kategori'] as $kat_id) {
                $params[] = (int)$kat_id;
                $types .= "i";
            }
        }
        
        // Order by
        $orderBy = match($filters['sort'] ?? 'terbaru') {
            'judul_asc' => "p.judul ASC",
            'judul_desc' => "p.judul DESC",
            'terlama' => "p.tanggal_selesai ASC",
            'rating' => "avg_rating DESC",
            default => "p.id_project DESC"
        };
        
        $whereClause = implode(' AND ', $where);
        
        $query = "SELECT p.*, 
                  GROUP_CONCAT(DISTINCT c.nama_kategori ORDER BY c.nama_kategori SEPARATOR ', ') as kategori,
                  GROUP_CONCAT(DISTINCT c.warna_hex ORDER BY c.nama_kategori SEPARATOR ',') as warna,
                  AVG(r.skor) as avg_rating,
                  COUNT(DISTINCT r.id_rating) as total_rating
                  FROM tbl_project p
                  LEFT JOIN tbl_project_category pc ON p.id_project = pc.id_project
                  LEFT JOIN tbl_category c ON pc.id_kategori = c.id_kategori
                  LEFT JOIN tbl_rating r ON p.id_project = r.id_project
                  WHERE $whereClause
                  GROUP BY p.id_project
                  ORDER BY $orderBy";
        
        if (!empty($params)) {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->db->query($query);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Create new karya
     * 
     * @param array $data Data karya
     * @return int|false ID karya yang baru dibuat atau false jika gagal
     */
    public function createKarya(array $data): int|false {
        $query = "INSERT INTO tbl_project 
                  (judul, deskripsi, pembuat, tanggal_selesai, status, link_external) 
                  VALUES (?, ?, ?, ?, ?, NULL)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "sssss", 
            $data['judul'],
            $data['deskripsi'],
            $data['pembuat'],
            $data['tanggal_selesai'],
            $data['status']
        );
        
        if ($stmt->execute()) {
            return $stmt->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update karya
     * 
     * @param int $id ID project
     * @param array $data Data yang akan diupdate
     * @return bool Success status
     */
    public function updateKarya(int $id, array $data): bool {
        $query = "UPDATE tbl_project 
                  SET judul = ?, deskripsi = ?, pembuat = ?, 
                      tanggal_selesai = ?, status = ? 
                  WHERE id_project = ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "sssssi",
            $data['judul'],
            $data['deskripsi'],
            $data['pembuat'],
            $data['tanggal_selesai'],
            $data['status'],
            $id
        );
        
        return $stmt->execute();
    }
    
    /**
     * Delete karya
     * 
     * @param int $id ID project
     * @return bool Success status
     */
    public function deleteKarya(int $id): bool {
        $query = "DELETE FROM tbl_project WHERE id_project = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}