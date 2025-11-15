<?php
// src/Controllers/KaryaController.php

namespace PortalTPL\Controllers;

use PortalTPL\Models\Karya;
use PortalTPL\Models\Rating;

/**
 * Controller untuk menangani logic karya
 * Koordinasi antara Model dan View
 */
class KaryaController {
    private Karya $karyaModel;
    private Rating $ratingModel;
    
    public function __construct() {
        $this->karyaModel = new Karya();
        $this->ratingModel = new Rating();
    }
    
    /**
     * Tampilkan halaman detail karya
     * Menggantikan public/detail_karya.php prosedural
     * 
     * @param int $id ID project dari $_GET
     */
    public function showDetail(int $id): void {
        // Start session jika belum
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Generate UUID untuk user jika belum ada
        if (!isset($_SESSION['user_uuid'])) {
            $_SESSION['user_uuid'] = uniqid('user_', true);
        }
        
        // Ambil data karya
        $karya = $this->karyaModel->getKaryaById($id, true);
        
        // Redirect ke galeri jika karya tidak ditemukan
        if ($karya === null) {
            redirect('galeri');
        }
        
        // Ambil data tambahan
        $links = $this->karyaModel->getLinks($id);
        $files = $this->karyaModel->getFiles($id);
        $snapshots = $this->karyaModel->getSnapshots($id);
        $documents = $this->karyaModel->getDocuments($id);
        
        // Cek apakah user sudah rating
        $userRating = $this->ratingModel->getUserRating(
            $id,
            $_SESSION['user_uuid'],
            $_SERVER['REMOTE_ADDR']
        );
        
        // Set page title untuk header
        $page_title = $karya['judul'];
        
        // Load view dengan data
        require __DIR__ . '/../../views/public/detail_karya.php';
    }
    
    /**
     * Tampilkan halaman galeri dengan filter
     * Menggantikan public/galeri.php prosedural
     */
    public function showGaleri(): void {
        // Ambil parameter dari GET
        $filters = [
            'search' => $_GET['search'] ?? '',
            'sort' => $_GET['sort'] ?? 'terbaru',
            'kategori' => $_GET['kategori'] ?? []
        ];
        
        // Ambil semua karya dengan filter
        $karya_list = $this->karyaModel->getAllKarya($filters);
        
        // Set page title
        $page_title = "Galeri Karya";
        
        // Load view
        require __DIR__ . '/../../views/public/galeri.php';
    }
    
    /**
     * Tampilkan homepage
     */
    public function showHome(): void {
        // Ambil karya featured/terbaru untuk homepage
        $filters = [
            'sort' => 'terbaru'
        ];
        
        $karya_list = $this->karyaModel->getAllKarya($filters);
        
        // Limit untuk homepage (6 karya featured)
        $karya_featured = array_slice($karya_list, 0, 6);
        
        // Set page title
        $page_title = "Beranda";
        
        // Load view
        require __DIR__ . '/../../views/public/index.php';
    }

    /**
     * Handle submit rating
     * Menggantikan public/proses_rating.php
     */
    public function submitRating(): void {
        // Pastikan request adalah POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('galeri');
        }
        
        session_start();
        
        // Generate UUID jika belum ada
        if (!isset($_SESSION['user_uuid'])) {
            $_SESSION['user_uuid'] = uniqid('user_', true);
        }
        
        $id_project = (int)($_POST['id_project'] ?? 0);
        $skor = (int)($_POST['skor'] ?? 0);
        
        try {
            // Submit rating menggunakan model
            $this->ratingModel->submitRating(
                $id_project,
                $_SESSION['user_uuid'],
                $_SERVER['REMOTE_ADDR'],
                $skor
            );
            
            // Redirect dengan success message
            flash('success', 'Rating berhasil dikirim!');
            redirect('detail?id=' . $id_project);
            
        } catch (\Exception $e) {
            // Redirect dengan error message
            flash('error', $e->getMessage());
            redirect('detail?id=' . $id_project);
        }
    }
}
