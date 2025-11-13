<?php
// src/Database/Database.php

namespace PortalTPL\Database;

use mysqli;
use Exception;

/**
 * Singleton Database Connection
 * Menggantikan db_connect.php prosedural
 */
class Database {
    private static ?Database $instance = null;
    private mysqli $connection;
    
    // Konfigurasi database
    private string $host = 'localhost';
    private string $user = 'root';
    private string $pass = '';
    private string $db_name = 'db_portal_tpl';
    
    /**
     * Private constructor untuk Singleton pattern
     */
    private function __construct() {
        $this->connection = new mysqli(
            $this->host, 
            $this->user, 
            $this->pass, 
            $this->db_name
        );
        
        if ($this->connection->connect_error) {
            throw new Exception("Koneksi database gagal: " . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8mb4");
    }
    
    /**
     * Get singleton instance
     */
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    /**
     * Get mysqli connection
     */
    public function getConnection(): mysqli {
        return $this->connection;
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserialization
     */
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}