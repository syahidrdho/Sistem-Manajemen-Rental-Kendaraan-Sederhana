<?php
// File: models/Kendaraan.php (Versi Bersih - Siap GitHub)

class Kendaraan {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Menghitung total aset aktif (non-recycle bin)
    public function countActive() {
        $sql = "SELECT COUNT(*) as total FROM kendaraan WHERE deleted_at IS NULL";
        $result = $this->conn->query($sql); 
        if (!$result) return 0;
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Menghitung total data untuk paginasi, dengan filter pencarian
    public function countAll($search = '') {
        $sql = "SELECT COUNT(id_kendaraan) as total FROM kendaraan WHERE deleted_at IS NULL";
        $params = []; $types = '';
        if (!empty($search)) {
            $sql .= " AND (jenis LIKE ? OR merk LIKE ? OR no_plat LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm]; $types = 'sss';
        }
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
    
    // Mengambil semua data untuk tabel, dengan paginasi, pencarian, dan sorting
    public function getAll($search = '', $limit = 10, $offset = 0, $sortBy = 'id_kendaraan', $sortOrder = 'ASC') {
        $allowedSortColumns = ['id_kendaraan', 'jenis', 'merk', 'no_plat', 'status'];
        if (!in_array($sortBy, $allowedSortColumns)) $sortBy = 'id_kendaraan';
        if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) $sortOrder = 'ASC';
        $sql = "SELECT * FROM kendaraan WHERE deleted_at IS NULL";
        $params = []; $types = '';
        if (!empty($search)) {
            $sql .= " AND (jenis LIKE ? OR merk LIKE ? OR no_plat LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm]; $types = 'sss';
        }
        $sql .= " ORDER BY $sortBy $sortOrder LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset; $types .= 'ii';
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Mengambil data by ID. $includeDeleted = true akan mencari di recycle bin.
    public function getById($id, $includeDeleted = false) {
        $sql = "SELECT * FROM kendaraan WHERE id_kendaraan = ?";
        if ($includeDeleted == false) {
            $sql .= " AND deleted_at IS NULL";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id); $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // --- (Fungsi CRUD Standar) ---

    public function create($jenis, $merk, $no_plat, $status) {
        $stmt = $this->conn->prepare("INSERT INTO kendaraan (jenis, merk, no_plat, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $jenis, $merk, $no_plat, $status);
        return $stmt->execute();
    }
    public function update($id, $jenis, $merk, $no_plat, $status) {
        $stmt = $this->conn->prepare("UPDATE kendaraan SET jenis=?, merk=?, no_plat=?, status=? WHERE id_kendaraan=?");
        $stmt->bind_param("ssssi", $jenis, $merk, $no_plat, $status, $id);
        return $stmt->execute();
    }

    // --- (Fungsi Soft Delete & Recycle Bin) ---

    public function delete($id) {
        // Ini adalah Soft Delete
        $stmt = $this->conn->prepare("UPDATE kendaraan SET deleted_at = NOW() WHERE id_kendaraan = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function getAllDeleted() {
        return $this->conn->query("SELECT * FROM kendaraan WHERE deleted_at IS NOT NULL ORDER BY id_kendaraan ASC");
    }
    public function restore($id) {
        $stmt = $this->conn->prepare("UPDATE kendaraan SET deleted_at = NULL WHERE id_kendaraan = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function deletePermanent($id) {
        $stmt = $this->conn->prepare("DELETE FROM kendaraan WHERE id_kendaraan = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function restoreBulk(array $ids) {
        if (empty($ids)) return false;
        $idList = implode(',', $ids);
        foreach ($ids as $id) { if (!is_numeric($id)) return false; }
        $sql = "UPDATE kendaraan SET deleted_at = NULL WHERE id_kendaraan IN ($idList)";
        $stmt = $this->conn->prepare($sql); return $stmt->execute();
    }
    public function deletePermanentBulk(array $ids) {
        if (empty($ids)) return false;
        $idList = implode(',', $ids);
        foreach ($ids as $id) { if (!is_numeric($id)) return false; }
        $sql = "DELETE FROM kendaraan WHERE id_kendaraan IN ($idList) AND deleted_at IS NOT NULL";
        $stmt = $this->conn->prepare($sql); return $stmt->execute();
    }
    public function autoDeleteOld($days = 30) {
        $sql = "DELETE FROM kendaraan WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL ? DAY";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $days); return $stmt->execute();
    }
    
    // (Fungsi helper untuk validasi - tidak ditampilkan penuh)
    public function isPlatExists($no_plat) { /* ... */ }
    public function isPlatExistsForAnotherVehicle($no_plat, $current_id) { /* ... */ }

    // --- (Fungsi Logika Bisnis & Kustom) ---

    // Mengambil semua kendaraan yang 'tersedia' (untuk dropdown form Transaksi)
    public function getAllAvailable() {
        $sql = "SELECT id_kendaraan, merk, no_plat, status 
                FROM kendaraan 
                WHERE status = 'tersedia' AND deleted_at IS NULL 
                ORDER BY merk ASC";
        
        $result = $this->conn->query($sql);
        return $result;
    }

    // Mengubah status kendaraan (dipanggil oleh Controller Transaksi/Pengembalian)
    public function updateStatus($id, $status) {
        $sql = "UPDATE kendaraan SET status = ? WHERE id_kendaraan = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    // Menghitung kendaraan 'tersedia' per jenis (untuk Dashboard)
    public function getAvailableCountByType() {
        $sql = "SELECT 
                    jenis, 
                    COUNT(*) as jumlah_tersedia 
                FROM 
                    kendaraan 
                WHERE 
                    status = 'tersedia' 
                    AND deleted_at IS NULL 
                GROUP BY 
                    jenis";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            return [];
        }

        $counts = [];
        while ($row = $result->fetch_assoc()) {
            $counts[$row['jenis']] = $row['jumlah_tersedia'];
        }
        
        return $counts;
    }

}
?>