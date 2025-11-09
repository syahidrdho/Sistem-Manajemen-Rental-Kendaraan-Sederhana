<?php
// File: controllers/PembayaranController.php (Versi Bersih - Siap GitHub)

require_once "controllers/BaseController.php";

class PembayaranController extends BaseController {
    
    private $pembayaranModel;
    private $transaksiModel;

    public function __construct($factory) {
        parent::__construct($factory); 
        
        $this->pembayaranModel = $this->factory->getModel('Pembayaran');
        $this->transaksiModel = $this->factory->getModel('TransaksiSewa');
    }

    public function index() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        // Logika Paginasi & Pencarian
        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $offset = ($currentPage - 1) * $limit;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id_pembayaran';
        $sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';

        $totalResults = $this->pembayaranModel->countAll($search);
        $totalPages = ceil($totalResults / $limit);
        $result = $this->pembayaranModel->getAll($search, $limit, $offset, $sortBy, $sortOrder);
        
        include "views/pembayaran/index.php";
    }

    public function create() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $errors = [];
        $data = [
            'id_sewa' => '', 'jumlah_bayar' => '',
            'tgl_bayar' => '', 'metode_bayar' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $data = [
                'id_sewa' => Sanitizer::numeric($_POST['id_sewa']),
                'jumlah_bayar' => Sanitizer::numeric($_POST['jumlah_bayar']),
                'tgl_bayar' => Sanitizer::text($_POST['tgl_bayar']),
                'metode_bayar' => Sanitizer::text($_POST['metode_bayar'])
            ];

            $validator = new Validator($this->factory->getDb()); 
            
            // Set nama field yang ramah pengguna untuk pesan error
            $validator->setFieldNames([
                'id_sewa' => 'ID Transaksi Sewa',
                'jumlah_bayar' => 'Jumlah Bayar',
                'tgl_bayar' => 'Tanggal Bayar',
                'metode_bayar' => 'Metode Bayar'
            ]);

            $rules = [
                'id_sewa' => 'required|numeric',
                'jumlah_bayar' => 'required|numeric|between:0,999999999',
                'tgl_bayar' => 'required|dateFormat:Y-m-d',
                'metode_bayar' => 'required|in:Tunai,Kartu,Transfer'
            ];

            if ($validator->validate($data, $rules)) {
                $this->pembayaranModel->create($data['id_sewa'], $data['jumlah_bayar'], $data['tgl_bayar'], $data['metode_bayar']);
                header("Location: index.php?page=pembayaran");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }

        $transaksi = $this->transaksiModel->getAll('', 999);
        include "views/pembayaran/create.php";
    }

    public function edit($id) {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $errors = [];
        $data = $this->pembayaranModel->getById($id);
        if (!$data) {
            echo "Data pembayaran tidak ditemukan.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();

            $data['id_sewa'] = Sanitizer::numeric($_POST['id_sewa']);
            $data['jumlah_bayar'] = Sanitizer::numeric($_POST['jumlah_bayar']);
            $data['tgl_bayar'] = Sanitizer::text($_POST['tgl_bayar']);
            $data['metode_bayar'] = Sanitizer::text($_POST['metode_bayar']);
            
            $validator = new Validator($this->factory->getDb()); 
            
            $validator->setFieldNames([
                'id_sewa' => 'ID Transaksi Sewa',
                'jumlah_bayar' => 'Jumlah Bayar',
                'tgl_bayar' => 'Tanggal Bayar',
                'metode_bayar' => 'Metode Bayar'
            ]);

            $rules = [
                'id_sewa' => 'required|numeric',
                'jumlah_bayar' => 'required|numeric|between:0,999999999',
                'tgl_bayar' => 'required|dateFormat:Y-m-d',
                'metode_bayar' => 'required|in:Tunai,Kartu,Transfer'
            ];

            if ($validator->validate($data, $rules)) {
                $this->pembayaranModel->update($id, $data['id_sewa'], $data['jumlah_bayar'], $data['tgl_bayar'], $data['metode_bayar']);
                header("Location: index.php?page=pembayaran");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }

        $transaksi = $this->transaksiModel->getAll('', 999);
        include "views/pembayaran/edit.php";
    }

    public function delete($id) {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        $this->pembayaranModel->delete($id);
        header("Location: index.php?page=pembayaran");
    }
    
    // --- Recycle Bin (Hanya Admin) ---

    public function recycleBin() {
        $this->authorize(['admin']);
        $result = $this->pembayaranModel->getAllDeleted();
        include "views/pembayaran/recycle_bin.php";
    }

    public function restore($id) {
        $this->authorize(['admin']);
        $this->pembayaranModel->restore($id);
        header("Location: index.php?page=pembayaran&action=recycleBin");
    }

    public function deletePermanent($id) {
        $this->authorize(['admin']);
        $this->pembayaranModel->deletePermanent($id);
        header("Location: index.php?page=pembayaran&action=recycleBin");
    }

    public function bulkRecycleBin() {
        $this->authorize(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            $action = $_POST['bulk_action'] ?? null;
            $ids = $_POST['ids'] ?? [];
            if (!empty($ids) && $action == 'restore') {
                $this->pembayaranModel->restoreBulk($ids); 
            } elseif (!empty($ids) && $action == 'delete_permanent') {
                $this->pembayaranModel->deletePermanentBulk($ids);
            }
        }
        header("Location: index.php?page=pembayaran&action=recycleBin");
    }
}
?>