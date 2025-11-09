<?php
// File: core/ModelFactory.php (Versi Ultra-Bersih - Siap GitHub)

require_once 'models/Dashboard.php';
require_once 'models/Kendaraan.php';
require_once 'models/Pelanggan.php';
require_once 'models/TransaksiSewa.php'; 
require_once 'models/Pembayaran.php';
require_once 'models/Pengembalian.php';
require_once 'models/User.php';

class ModelFactory {
    
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function getModel($modelName) {
        switch ($modelName) {
            case 'Dashboard':
                return new Dashboard($this->conn);
            case 'Kendaraan':
                return new Kendaraan($this->conn);
            case 'Pelanggan':
                return new Pelanggan($this->conn);
            case 'TransaksiSewa': 
                return new TransaksiSewa($this->conn);
            case 'Pembayaran':
                return new Pembayaran($this->conn);
            case 'Pengembalian':
                return new Pengembalian($this->conn);
            case 'User':
                return new User($this->conn);
            default:
                return null; 
        }
    }

    // Dibutuhkan oleh Validator untuk rule 'unique'
    public function getDb() {
        return $this->conn;
    }
}
?>