<?php
// File: models/TransaksiSewa.php (Versi Bersih - Siap GitHub)

class TransaksiSewa {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Menghitung total transaksi aktif (untuk Dashboard)
    public function countActive() {
        $sql = "SELECT COUNT(*) as total FROM transaksi_sewa WHERE deleted_at IS NULL";
        $result = $this->conn->query($sql);
        if (!$result) return 0;
        $row = $result->fetch_assoc();
        return $row['total'];
    }
    
    // Menghitung total pendapatan (untuk Dashboard)
    public function sumActive() {
        $sql = "SELECT SUM(total_biaya) as total FROM transaksi_sewa WHERE deleted_at IS NULL";
        $result = $this->conn->query($sql);
        if (!$result) return 0;
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    // Menghitung total data untuk paginasi, dengan filter pencarian
    public function countAll($search = '') {
        $sql = "SELECT COUNT(ts.id_sewa) as total
                FROM transaksi_sewa ts
                JOIN pelanggan p ON ts.id_pelanggan = p.id_pelanggan
                JOIN kendaraan k ON ts.id_kendaraan = k.id_kendaraan
                WHERE ts.deleted_at IS NULL";
        $params = []; $types = '';
        if (!empty($search)) {
            $sql .= " AND (p.nama LIKE ? OR k.merk LIKE ? OR k.no_plat LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm]; $types = 'sss';
        }
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }

    // Mengambil semua data untuk tabel, dengan paginasi, pencarian, dan sorting
    public function getAll($search = '', $limit = 10, $offset = 0, $sortBy = 'id_sewa', $sortOrder = 'ASC') {
        $allowedSortColumns = ['id_sewa', 'nama_pelanggan', 'merk_kendaraan', 'tgl_sewa', 'tgl_kembali', 'total_biaya'];
        $sortColumnMap = [
            'id_sewa' => 'ts.id_sewa', 'nama_pelanggan' => 'p.nama',
            'merk_kendaraan' => 'k.merk', 'tgl_sewa' => 'ts.tgl_sewa',
            'tgl_kembali' => 'ts.tgl_kembali', 'total_biaya' => 'ts.total_biaya'
        ];
        if (!in_array($sortBy, $allowedSortColumns)) $sortBy = 'id_sewa';
        if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) $sortOrder = 'ASC';
        $orderByColumn = $sortColumnMap[$sortBy];
        
        $sql = "SELECT ts.*, p.nama AS nama_pelanggan, k.merk AS merk_kendaraan, k.no_plat
                FROM transaksi_sewa ts
                JOIN pelanggan p ON ts.id_pelanggan = p.id_pelanggan
                JOIN kendaraan k ON ts.id_kendaraan = k.id_kendaraan
                WHERE ts.deleted_at IS NULL";
        $params = []; $types = '';
        if (!empty($search)) {
            $sql .= " AND (p.nama LIKE ? OR k.merk LIKE ? OR k.no_plat LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm]; $types = 'sss';
        }
        $sql .= " ORDER BY $orderByColumn $sortOrder LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset; $types .= 'ii';
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Mengambil data by ID. $includeDeleted = true akan mencari di recycle bin.
    public function getById($id, $includeDeleted = false) {
        $sql = "SELECT * FROM transaksi_sewa WHERE id_sewa = ?";
        
        if ($includeDeleted == false) {
            $sql .= " AND deleted_at IS NULL";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id); 
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // --- (Fungsi CRUD Standar) ---

    public function create($id_pelanggan, $id_kendaraan, $tgl_sewa, $tgl_kembali, $total_biaya) {
        $stmt = $this->conn->prepare("INSERT INTO transaksi_sewa (id_pelanggan, id_kendaraan, tgl_sewa, tgl_kembali, total_biaya) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iissd", $id_pelanggan, $id_kendaraan, $tgl_sewa, $tgl_kembali, $total_biaya);
        return $stmt->execute();
    }
    public function update($id, $id_pelanggan, $id_kendaraan, $tgl_sewa, $tgl_kembali, $total_biaya) {
        $stmt = $this->conn->prepare("UPDATE transaksi_sewa SET id_pelanggan=?, id_kendaraan=?, tgl_sewa=?, tgl_kembali=?, total_biaya=? WHERE id_sewa=?");
        $stmt->bind_param("iissdi", $id_pelanggan, $id_kendaraan, $tgl_sewa, $tgl_kembali, $total_biaya, $id);
        return $stmt->execute();
    }

    // --- (Fungsi Soft Delete & Recycle Bin) ---

    public function delete($id) {
        // Ini adalah Soft Delete
        $stmt = $this->conn->prepare("UPDATE transaksi_sewa SET deleted_at = NOW() WHERE id_sewa = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function getAllDeleted() {
        $sql = "SELECT ts.*, p.nama AS nama_pelanggan, k.merk AS merk_kendaraan, k.no_plat
                FROM transaksi_sewa ts
                JOIN pelanggan p ON ts.id_pelanggan = p.id_pelanggan
                JOIN kendaraan k ON ts.id_kendaraan = k.id_kendaraan
                WHERE ts.deleted_at IS NOT NULL ORDER BY ts.deleted_at DESC";
        return $this->conn->query($sql);
    }
    public function restore($id) {
        $stmt = $this->conn->prepare("UPDATE transaksi_sewa SET deleted_at = NULL WHERE id_sewa = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function deletePermanent($id) {
        $stmt = $this->conn->prepare("DELETE FROM transaksi_sewa WHERE id_sewa = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function restoreBulk(array $ids) {
        if (empty($ids)) return false;
        $idList = implode(',', $ids);
        foreach ($ids as $id) { if (!is_numeric($id)) return false; }
        $sql = "UPDATE transaksi_sewa SET deleted_at = NULL WHERE id_sewa IN ($idList)";
        $stmt = $this->conn->prepare($sql); return $stmt->execute();
    }
    public function deletePermanentBulk(array $ids) {
        if (empty($ids)) return false;
        $idList = implode(',', $ids);
        foreach ($ids as $id) { if (!is_numeric($id)) return false; }
        $sql = "DELETE FROM transaksi_sewa WHERE id_sewa IN ($idList) AND deleted_at IS NOT NULL";
        $stmt = $this->conn->prepare($sql); return $stmt->execute();
    }
    public function autoDeleteOld($days = 30) {
        $sql = "DELETE FROM transaksi_sewa WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL ? DAY";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $days); return $stmt->execute();
    }
}
?>