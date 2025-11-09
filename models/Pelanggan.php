<?php
// File: models/Pelanggan.php (Versi Bersih - Siap GitHub)

class Pelanggan {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Menghitung total pelanggan aktif (untuk Dashboard)
    public function countActive() {
        $sql = "SELECT COUNT(*) as total FROM pelanggan WHERE deleted_at IS NULL";
        $result = $this->conn->query($sql); 
        if (!$result) return 0;
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Menghitung total data untuk paginasi, dengan filter pencarian
    public function countAll($search = '') {
        $sql = "SELECT COUNT(id_pelanggan) as total FROM pelanggan WHERE deleted_at IS NULL";
        $params = []; $types = '';
        if (!empty($search)) {
            $sql .= " AND (nama LIKE ? OR alamat LIKE ? OR no_hp LIKE ? OR no_ktp LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm]; $types = 'ssss';
        }
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
    
    // Mengambil semua data untuk tabel, dengan paginasi, pencarian, dan sorting
    public function getAll($search = '', $limit = 10, $offset = 0, $sortBy = 'id_pelanggan', $sortOrder = 'ASC') {
        $allowedSortColumns = ['id_pelanggan', 'nama', 'alamat', 'no_hp', 'no_ktp'];
        if (!in_array($sortBy, $allowedSortColumns)) $sortBy = 'id_pelanggan';
        if (!in_array(strtoupper($sortOrder), ['ASC', 'DESC'])) $sortOrder = 'ASC';
        $sql = "SELECT * FROM pelanggan WHERE deleted_at IS NULL";
        $params = []; $types = '';
        if (!empty($search)) {
            $sql .= " AND (nama LIKE ? OR alamat LIKE ? OR no_hp LIKE ? OR no_ktp LIKE ?)";
            $searchTerm = "%" . $search . "%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm]; $types = 'ssss';
        }
        $sql .= " ORDER BY $sortBy $sortOrder LIMIT ? OFFSET ?";
        $params[] = $limit; $params[] = $offset; $types .= 'ii';
        $stmt = $this->conn->prepare($sql);
        if (!empty($params)) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    // Mengambil satu data pelanggan berdasarkan ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM pelanggan WHERE id_pelanggan = ?");
        $stmt->bind_param("i", $id); $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    // --- (Fungsi CRUD Standar) ---

    public function create($nama, $alamat, $no_hp, $no_ktp) {
        $stmt = $this->conn->prepare("INSERT INTO pelanggan (nama, alamat, no_hp, no_ktp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama, $alamat, $no_hp, $no_ktp);
        return $stmt->execute();
    }
    public function update($id, $nama, $alamat, $no_hp, $no_ktp) {
        $stmt = $this->conn->prepare("UPDATE pelanggan SET nama=?, alamat=?, no_hp=?, no_ktp=? WHERE id_pelanggan=?");
        $stmt->bind_param("ssssi", $nama, $alamat, $no_hp, $no_ktp, $id);
        return $stmt->execute();
    }

    // --- (Fungsi Soft Delete & Recycle Bin) ---

    public function delete($id) {
        // Ini adalah Soft Delete
        $stmt = $this->conn->prepare("UPDATE pelanggan SET deleted_at = NOW() WHERE id_pelanggan = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function getAllDeleted() {
        return $this->conn->query("SELECT * FROM pelanggan WHERE deleted_at IS NOT NULL ORDER BY id_pelanggan ASC");
    }
    public function restore($id) {
        $stmt = $this->conn->prepare("UPDATE pelanggan SET deleted_at = NULL WHERE id_pelanggan = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function deletePermanent($id) {
        $stmt = $this->conn->prepare("DELETE FROM pelanggan WHERE id_pelanggan = ?");
        $stmt->bind_param("i", $id); return $stmt->execute();
    }
    public function restoreBulk(array $ids) {
        if (empty($ids)) return false;
        $idList = implode(',', $ids);
        foreach ($ids as $id) { if (!is_numeric($id)) return false; }
        $sql = "UPDATE pelanggan SET deleted_at = NULL WHERE id_pelanggan IN ($idList)";
        $stmt = $this->conn->prepare($sql); return $stmt->execute();
    }
    public function deletePermanentBulk(array $ids) {
        if (empty($ids)) return false;
        $idList = implode(',', $ids);
        foreach ($ids as $id) { if (!is_numeric($id)) return false; }
        $sql = "DELETE FROM pelanggan WHERE id_pelanggan IN ($idList) AND deleted_at IS NOT NULL";
        $stmt = $this->conn->prepare($sql); return $stmt->execute();
    }
    public function autoDeleteOld($days = 30) {
        $sql = "DELETE FROM pelanggan WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL ? DAY";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $days); return $stmt->execute();
    }
}
?>