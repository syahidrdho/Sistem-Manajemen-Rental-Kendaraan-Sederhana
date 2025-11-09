<?php
// File: controllers/TransaksiController.php (Versi Bersih - FUNGSI DELETE AMAN)

require_once "controllers/BaseController.php";
require_once "core/Validator.php"; 
require_once "core/Sanitizer.php"; 
require_once "core/CSRF.php"; 

class TransaksiController extends BaseController {
    
    private $transaksiModel;
    private $pelangganModel;
    private $kendaraanModel;
    
    public function __construct($factory) {
        parent::__construct($factory); 
        
        $this->transaksiModel = $this->factory->getModel('TransaksiSewa');
        $this->pelangganModel = $this->factory->getModel('Pelanggan');
        $this->kendaraanModel = $this->factory->getModel('Kendaraan');
    }

    public function index() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $offset = ($currentPage - 1) * $limit;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id_sewa';
        $sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

        $totalResults = $this->transaksiModel->countAll($search);
        $totalPages = ceil($totalResults / $limit);
        $result = $this->transaksiModel->getAll($search, $limit, $offset, $sortBy, $sortOrder);
        
        include "views/transaksi/index.php";
    }

    public function create() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $errors = [];
        $data = [
            'id_pelanggan' => '', 'id_kendaraan' => '', 'tgl_sewa' => '',
            'tgl_kembali' => '', 'total_biaya' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $data = [
                'id_pelanggan' => Sanitizer::numeric($_POST['id_pelanggan']),
                'id_kendaraan' => Sanitizer::numeric($_POST['id_kendaraan']),
                'tgl_sewa' => Sanitizer::text($_POST['tgl_sewa']),
                'tgl_kembali' => Sanitizer::text($_POST['tgl_kembali']),
                'total_biaya' => Sanitizer::numeric($_POST['total_biaya'])
            ];

            $validator = new Validator($this->factory->getDb()); 
            
            $validator->setFieldNames([
                'id_pelanggan' => 'Pelanggan',
                'id_kendaraan' => 'Kendaraan',
                'tgl_sewa' => 'Tanggal Sewa',
                'tgl_kembali' => 'Tanggal Kembali',
                'total_biaya' => 'Total Biaya'
            ]);
            
            $rules = [
                'id_pelanggan' => 'required|numeric',
                'id_kendaraan' => 'required|numeric',
                'tgl_sewa' => 'required',
                'tgl_kembali' => 'required|date_after:tgl_sewa',
                'total_biaya' => 'required|numeric'
            ];

            if ($validator->validate($data, $rules)) {
                $this->transaksiModel->create($data['id_pelanggan'], $data['id_kendaraan'], $data['tgl_sewa'], $data['tgl_kembali'], $data['total_biaya']);
                $this->kendaraanModel->updateStatus($data['id_kendaraan'], 'disewa');
                
                header("Location: index.php?page=transaksi");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }

        $pelanggan = $this->pelangganModel->getAll('', 999);
        $kendaraan = $this->kendaraanModel->getAllAvailable();
        
        include "views/transaksi/create.php";
    }

    public function edit($id) {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $errors = [];
        $data = $this->transaksiModel->getById($id);
        if (!$data) {
            echo "Data transaksi tidak ditemukan.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $data['id_pelanggan'] = Sanitizer::numeric($_POST['id_pelanggan']);
            $data['id_kendaraan'] = Sanitizer::numeric($_POST['id_kendaraan']);
            $data['tgl_sewa'] = Sanitizer::text($_POST['tgl_sewa']);
            $data['tgl_kembali'] = Sanitizer::text($_POST['tgl_kembali']);
            $data['total_biaya'] = Sanitizer::numeric($_POST['total_biaya']);
            
            $validator = new Validator($this->factory->getDb()); 
            
            $validator->setFieldNames([
                'id_pelanggan' => 'Pelanggan',
                'id_kendaraan' => 'Kendaraan',
                'tgl_sewa' => 'Tanggal Sewa',
                'tgl_kembali' => 'Tanggal Kembali',
                'total_biaya' => 'Total Biaya'
            ]);
            
            $rules = [
                'id_pelanggan' => 'required|numeric',
                'id_kendaraan' => 'required|numeric',
                'tgl_sewa' => 'required',
                'tgl_kembali' => 'required|date_after:tgl_sewa',
                'total_biaya' => 'required|numeric'
            ];

            if ($validator->validate($data, $rules)) {
                $this->transaksiModel->update($id, $data['id_pelanggan'], $data['id_kendaraan'], $data['tgl_sewa'], $data['tgl_kembali'], $data['total_biaya']);
                header("Location: index.php?page=transaksi");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }

        $pelanggan = $this->pelangganModel->getAll('', 999);
        $kendaraan = $this->kendaraanModel->getAll('', 999);
        include "views/transaksi/edit.php";
    }

    // ======================================================
    // == INI ROMBAKAN BUG KEAMANAN (GET ke POST) ==
    // ======================================================
    public function delete() { // Parameter $id dihapus
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $id = $_POST['id_to_delete'] ?? null;
            
            if ($id) {
                // Logika Bisnis: Kembalikan status mobil ke 'tersedia'
                $transaksi = $this->transaksiModel->getById($id);
                if ($transaksi) {
                    $this->kendaraanModel->updateStatus($transaksi['id_kendaraan'], 'tersedia');
                }
                
                $this->transaksiModel->delete($id);
            }
        }
        
        header("Location: index.php?page=transaksi");
    }
    // ======================================================

    
    public function recycleBin() {
        $this->authorize(['admin']);
        $result = $this->transaksiModel->getAllDeleted();
        include "views/transaksi/recycle_bin.php";
    }

    public function restore($id) {
        $this->authorize(['admin']);
        
        // Logika Bisnis: Kembalikan status mobil ke 'disewa'
        $transaksi = $this->transaksiModel->getById($id, true);
        if ($transaksi) {
            $this->kendaraanModel->updateStatus($transaksi['id_kendaraan'], 'disewa');
        }

        $this->transaksiModel->restore($id);
        
        header("Location: index.php?page=transaksi&action=recycleBin");
    }

    public function deletePermanent($id) {
        $this->authorize(['admin']);
        $this->transaksiModel->deletePermanent($id);
        header("Location: index.php?page=transaksi&action=recycleBin");
    }

    public function bulkRecycleBin() {
        $this->authorize(['admin']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            $action = $_POST['bulk_action'] ?? null;
            $ids = $_POST['ids'] ?? [];
            
            if (!empty($ids) && $action == 'restore') {
                foreach ($ids as $id) {
                    $transaksi = $this->transaksiModel->getById($id, true);
                    if ($transaksi) {
                        $this->kendaraanModel->updateStatus($transaksi['id_kendaraan'], 'disewa');
                    }
                }
                $this->transaksiModel->restoreBulk($ids); 
                
            } elseif (!empty($ids) && $action == 'delete_permanent') {
                $this->transaksiModel->deletePermanentBulk($ids);
            }
        }
        header("Location: index.php?page=transaksi&action=recycleBin");
    }
}
?>