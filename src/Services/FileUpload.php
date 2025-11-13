<?php
// ============================================
// src/Services/FileUpload.php
// ============================================

namespace PortalTPL\Services;

use Exception;

/**
 * Service untuk handle file upload
 * Menggantikan logic upload di proses_tambah_karya.php
 */
class FileUpload {
    private string $uploadDir;
    private array $allowedTypes;
    private int $maxSize;
    
    /**
     * Constructor
     * @param string $uploadDir Directory untuk upload (relatif dari root)
     * @param array $allowedTypes Allowed MIME types
     * @param int $maxSize Max file size dalam bytes
     */
    public function __construct(
        string $uploadDir = 'public/uploads/',
        array $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'],
        int $maxSize = 2097152 // 2MB default
    ) {
        $this->uploadDir = $uploadDir;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSize;
        
        // Create directory if not exists
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Upload single file
     * 
     * @param array $file $_FILES['fieldname']
     * @param string $prefix Prefix untuk nama file
     * @return array ['success' => bool, 'file_path' => string, 'error' => string]
     */
    public function uploadSingle(array $file, string $prefix = 'file'): array {
        // Check upload error
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'error' => $this->getUploadErrorMessage($file['error'])
            ];
        }
        
        // Validate file type
        if (!in_array($file['type'], $this->allowedTypes)) {
            return [
                'success' => false,
                'error' => 'File type not allowed: ' . $file['type']
            ];
        }
        
        // Validate file size
        if ($file['size'] > $this->maxSize) {
            $maxSizeMB = $this->maxSize / 1048576;
            return [
                'success' => false,
                'error' => "File too large. Max size: {$maxSizeMB}MB"
            ];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $extension;
        $filePath = $this->uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return [
                'success' => true,
                'file_path' => str_replace('public/', '', $filePath),
                'file_size' => $file['size'],
                'mime_type' => $file['type'],
                'original_name' => $file['name']
            ];
        }
        
        return [
            'success' => false,
            'error' => 'Failed to move uploaded file'
        ];
    }
    
    /**
     * Upload multiple files
     * 
     * @param array $files $_FILES['fieldname'] (multiple)
     * @param string $prefix Prefix untuk nama file
     * @return array Array of upload results
     */
    public function uploadMultiple(array $files, string $prefix = 'file'): array {
        $results = [];
        
        // Handle multiple file upload format
        if (isset($files['name']) && is_array($files['name'])) {
            $fileCount = count($files['name']);
            
            for ($i = 0; $i < $fileCount; $i++) {
                $file = [
                    'name' => $files['name'][$i],
                    'type' => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                
                $results[] = $this->uploadSingle($file, $prefix . '_' . $i);
            }
        }
        
        return $results;
    }
    
    /**
     * Delete file
     * 
     * @param string $filePath Path to file (relatif dari root)
     * @return bool Success status
     */
    public function deleteFile(string $filePath): bool {
        $fullPath = 'public/' . $filePath;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }
    
    /**
     * Get upload error message
     * 
     * @param int $errorCode PHP upload error code
     * @return string Error message
     */
    private function getUploadErrorMessage(int $errorCode): string {
        return match($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'PHP extension stopped the upload',
            default => 'Unknown upload error'
        };
    }
}