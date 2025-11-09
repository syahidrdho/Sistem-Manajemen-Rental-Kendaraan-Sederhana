<?php
// File: controllers/PelangganController.php (Versi Bersih - FUNGSI DELETE AMAN)

require_once "controllers/BaseController.php";
require_once "core/Validator.php"; 
require_once "core/Sanitizer.php"; 
require_once "core/CSRF.php"; 

class PelangganController extends BaseController {
    
    private $model;
    
    public function __construct($factory) {
        parent::__construct($factory); 
        $this->model = $this->factory->getModel('Pelanggan');
    }

    public function index() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $offset = ($currentPage - 1) * $limit;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id_pelanggan';
        $sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
        
        $totalResults = $this->model->countAll($search);
        $totalPages = ceil($totalResults / $limit);
        $result = $this->model->getAll($search, $limit, $offset, $sortBy, $sortOrder);
        
        include "views/pelanggan/index.php";
    }

    public function create() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $errors = [];
        $data = ['nama' => '','alamat' => '','no_hp' => '','no_ktp' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $data['nama'] = Sanitizer::name($_POST['nama']); 
            $data['alamat'] = Sanitizer::text($_POST['alamat']); 
            $data['no_hp'] = Sanitizer::formatPhone($_POST['no_hp']); 
            $data['no_ktp'] = Sanitizer::numeric($_POST['no_ktp']); 

            $validator = new Validator($this->factory->getDb()); 
            
            $validator->setFieldNames([
                'nama' => 'Nama Pelanggan',
                'alamat' => 'Alamat',
                'no_hp' => 'No. HP',
                'no_ktp' => 'No. KTP'
            ]);

            $rules = [
                'nama' => 'required',
                'alamat' => 'required',
                'no_hp' => 'required|unique:pelanggan,no_hp',
                'no_ktp' => 'required|numeric|unique:pelanggan,no_ktp'
            ];
            
            if ($validator->validate($data, $rules)) {
                $this->model->create($data['nama'], $data['alamat'], $data['no_hp'], $data['no_ktp']);
                header("Location: index.php?page=pelanggan");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }
        
        include "views/pelanggan/create.php";
    }

    public function edit($id) {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $errors = [];
        $data = $this->model->getById($id);
        if (!$data) {
            echo "Data pelanggan tidak ditemukan.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();

            $data['nama'] = Sanitizer::name($_POST['nama']); 
            $data['alamat'] = Sanitizer::text($_POST['alamat']); 
            $data['no_hp'] = Sanitizer::formatPhone($_POST['no_hp']); 
            $data['no_ktp'] = Sanitizer::numeric($_POST['no_ktp']); 

            $validator = new Validator($this->factory->getDb()); 
            
            $validator->setFieldNames([
                'nama' => 'Nama Pelanggan',
                'alamat' => 'Alamat',
                'no_hp' => 'No. HP',
                'no_ktp' => 'No. KTP'
            ]);

            $rules = [
                'nama' => 'required',
                'alamat' => 'required',
                'no_hp' => "required|unique:pelanggan,no_hp,{$id}",
                'no_ktp' => "required|numeric|unique:pelanggan,no_ktp,{$id}"
            ];

            if ($validator->validate($data, $rules)) {
                $this->model->update($id, $data['nama'], $data['alamat'], $data['no_hp'], $data['no_ktp']);
                header("Location: index.php?page=pelanggan");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }
        
        include "views/pelanggan/edit.php";
    }

    // ======================================================
    // == INI ROMBAKAN BUG KEAMANAN (GET ke POST) ==
    // ======================================================
    public function delete() { // Parameter $id dihapus
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        // Hanya proses jika metodenya POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            // Ambil ID dari form POST, bukan URL
            $id = $_POST['id_to_delete'] ?? null;
            
            if ($id) {
                $this->model->delete($id);
            }
        }
        
        // Selalu redirect kembali ke halaman index
        header("Location: index.php?page=pelanggan");
    }
    // ======================================================

    public function recycleBin() {
        $this->authorize(['admin']);
        $result = $this->model->getAllDeleted();
        include "views/pelanggan/recycle_bin.php";
    }

    public function restore($id) {
        $this->authorize(['admin']);
        $this->model->restore($id);
        header("Location: index.php?page=pelanggan&action=recycleBin");
    }

    public function deletePermanent($id) {
        $this->authorize(['admin']);
        $this->model->deletePermanent($id);
        header("Location: index.php?page=pelanggan&action=recycleBin");
    }

    public function bulkRecycleBin() {
        $this->authorize(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            $action = $_POST['bulk_action'] ?? null;
            $ids = $_POST['ids'] ?? [];
            if (!empty($ids) && $action == 'restore') {
                $this->model->restoreBulk($ids);
            } elseif (!empty($ids) && $action == 'delete_permanent') {
                $this->model->deletePermanentBulk($ids);
            }
        }
        header("Location: index.php?page=pelanggan&action=recycleBin");
    }
}
?>