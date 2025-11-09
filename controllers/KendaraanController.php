<?php
// File: controllers/KendaraanController.php (Versi FINAL DIPERBAIKI - Siap GitHub)

require_once "controllers/BaseController.php";

class KendaraanController extends BaseController {
    
    private $model;

    public function __construct($factory) {
        parent::__construct($factory); 
        $this->model = $this->factory->getModel('Kendaraan');
    }

    public function index() {
        $this->authorize(['admin', 'manajer', 'karyawan']);
        
        $limit = 10;
        $currentPage = isset($_GET['p']) ? (int)$_GET['p'] : 1;
        $offset = ($currentPage - 1) * $limit;
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'id_kendaraan';
        $sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
        
        $totalResults = $this->model->countAll($search);
        $totalPages = ceil($totalResults / $limit);
        $result = $this->model->getAll($search, $limit, $offset, $sortBy, $sortOrder);
        
        include "views/kendaraan/index.php";
    }

    public function create() {
        $this->authorize(['admin', 'manajer']);
        
        $errors = [];
        $data = ['jenis' => '', 'merk' => '', 'no_plat' => '', 'status' => 'tersedia'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail(); 
            
            $data['jenis'] = Sanitizer::text($_POST['jenis']);
            $data['merk'] = Sanitizer::text($_POST['merk']);
            $data['no_plat'] = Sanitizer::alphanum($_POST['no_plat']);
            $data['status'] = $_POST['status'];

            $validator = new Validator($this->factory->getDb());
            
            $validator->setFieldNames([
                'jenis' => 'Jenis Kendaraan', 
                'merk' => 'Merk',
                'no_plat' => 'No. Plat',
                'status' => 'Status'
            ]);

            $rules = [
                'jenis' => 'required',
                'merk' => 'required',
                'no_plat' => 'required|unique:kendaraan,no_plat',
                'status' => 'required'
            ];

            if ($validator->validate($data, $rules)) {
                $this->model->create($data['jenis'], $data['merk'], $data['no_plat'], $data['status']);
                header("Location: index.php?page=kendaraan");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }
        
        include "views/kendaraan/create.php";
    }

    public function edit($id) {
        $this->authorize(['admin', 'manajer']);
        
        $errors = [];
        $data = $this->model->getById($id);
        if (!$data) {
            echo "Data kendaraan tidak ditemukan.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $data['jenis'] = Sanitizer::text($_POST['jenis']);
            $data['merk'] = Sanitizer::text($_POST['merk']);
            $data['no_plat'] = Sanitizer::alphanum($_POST['no_plat']);
            $data['status'] = $_POST['status'];

            $validator = new Validator($this->factory->getDb());

            $validator->setFieldNames([
                'jenis' => 'Jenis Kendaraan',
                'merk' => 'Merk',
                'no_plat' => 'No. Plat',
                'status' => 'Status'
            ]);

            $rules = [
                'jenis' => 'required',
                'merk' => 'required',
                'no_plat' => "required|unique:kendaraan,no_plat,{$id}", 
                'status' => 'required'
            ];

            if ($validator->validate($data, $rules)) {
                $this->model->update($id, $data['jenis'], $data['merk'], $data['no_plat'], $data['status']);
                header("Location: index.php?page=kendaraan");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }
        
        include "views/kendaraan/edit.php";
    }

    public function delete() { 
        $this->authorize(['admin', 'manajer']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            $id = $_POST['id_to_delete'] ?? null;
            
            if ($id) {
                $this->model->delete($id);
            }
        }
        
        header("Location: index.php?page=kendaraan");
    }

    // --- Recycle Bin (Hanya Admin) ---
    
    public function recycleBin() {
        $this->authorize(['admin']);
        $result = $this->model->getAllDeleted(); 
        include "views/kendaraan/recycle_bin.php";
    }
    
    public function restore($id) {
        $this->authorize(['admin']);
        $this->model->restore($id);
        header("Location: index.php?page=kendaraan&action=recycleBin");
    }

    public function deletePermanent($id) {
        $this->authorize(['admin']);
        $this->model->deletePermanent($id);
        header("Location: index.php?page=kendaraan&action=recycleBin");
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
        header("Location: index.php?page=kendaraan&action=recycleBin");
    }
}
?>