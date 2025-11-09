<?php
// File: models/Dashboard.php (Versi Bersih - Siap GitHub)

class Dashboard {
    private $conn; 

    public function __construct($db) {
        $this->conn = $db; 
    }

    /**
     * Mengambil data ringkasan untuk kartu-kartu di Dashboard.
     * Catatan: total_kendaraan diambil dari KendaraanModel::getAvailableCountByType()
     */
    public function getSummary() {
        $summary = [];

        // Hitung total pelanggan
        $sqlPelanggan = "SELECT COUNT(*) as total FROM pelanggan WHERE deleted_at IS NULL";
        $resultPelanggan = $this->conn->query($sqlPelanggan);
        $summary['total_pelanggan'] = $resultPelanggan->fetch_assoc()['total'];

        // Hitung total transaksi
        $sqlTransaksi = "SELECT COUNT(*) as total FROM transaksi_sewa WHERE deleted_at IS NULL";
        $resultTransaksi = $this->conn->query($sqlTransaksi);
        $summary['total_transaksi'] = $resultTransaksi->fetch_assoc()['total'];

        // Hitung total pendapatan
        $sqlPendapatan = "SELECT SUM(total_biaya) as total FROM transaksi_sewa WHERE deleted_at IS NULL";
        $resultPendapatan = $this->conn->query($sqlPendapatan);
        $summary['total_pendapatan'] = $resultPendapatan->fetch_assoc()['total'] ?? 0;

        return $summary;
    }
}
?>