<?php
// File: controllers/UserController.php (Versi Bersih - Siap GitHub)

require_once "controllers/BaseController.php";

class UserController extends BaseController {
    
    private $model;

    public function __construct($factory) {
        parent::__construct($factory);
        $this->model = $this->factory->getModel('User'); 
    }

    // Menampilkan daftar semua pengguna
    public function index() {
        $this->authorize(['admin']); // Hanya Admin
        
        $result = $this->model->getAll(); 
        include "views/users/index.php";
    }

    // Menampilkan dan memproses form tambah pengguna baru
    public function create() {
        $this->authorize(['admin']); // Hanya Admin
        
        $errors = [];
        $data = ['nama_lengkap' => '', 'username' => '', 'role' => 'karyawan'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();

            $data['nama_lengkap'] = trim($_POST['nama_lengkap']);
            $data['username'] = trim($_POST['username']);
            $data['password'] = $_POST['password']; // Password wajib diisi saat create
            $data['role'] = $_POST['role'];

            $validator = new Validator($this->factory->getDb());
            $rules = [
                'nama_lengkap' => 'required',
                'username' => 'required|unique:users,username',
                'password' => 'required|min:6', // Wajib diisi saat create
                'role' => 'required|in:admin,manajer,karyawan'
            ];

            if ($validator->validate($data, $rules)) {
                // Hash password sebelum disimpan
                $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
                $this->model->create($data['nama_lengkap'], $data['username'], $hashed_password, $data['role']);
                header("Location: index.php?page=users");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }
        
        include "views/users/create.php";
    }

    // Menampilkan dan memproses form edit pengguna
    public function edit($id) {
        $this->authorize(['admin']); // Hanya Admin
        
        $errors = [];
        $data = $this->model->getById($id); 

        if (!$data) {
            echo "Data user tidak ditemukan.";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            CSRF::verifyOrFail();
            
            // Ambil data dari POST
            $data['nama_lengkap'] = trim($_POST['nama_lengkap']);
            $data['username'] = trim($_POST['username']);
            $data['role'] = $_POST['role'];
            $data['password'] = $_POST['password']; // Password baru (opsional)

            $validator = new Validator($this->factory->getDb());
            $rules = [
                'nama_lengkap' => 'required',
                'username' => "required|unique:users,username,{$id}", // Cek unique, kecualikan ID ini
                'role' => 'required|in:admin,manajer,karyawan'
            ];
            
            // Logika Password Opsional:
            // Hanya validasi password jika diisi
            if (!empty($data['password'])) {
                $rules['password'] = 'min:6';
            }

            if ($validator->validate($data, $rules)) {
                // Kirim password (kosong atau baru) ke model
                // Model akan menangani logika HASH jika password tidak kosong
                $this->model->update($id, $data['nama_lengkap'], $data['username'], $data['role'], $data['password']);
                
                header("Location: index.php?page=users");
                exit();
            } else {
                $errors = $validator->getErrors();
            }
        }
        
        include "views/users/edit.php";
    }

    // Menghapus pengguna
    public function delete($id) {
        $this->authorize(['admin']); // Hanya Admin
        
        // Proteksi: Mencegah admin menghapus akunnya sendiri
        if ($id == $_SESSION['user_id']) {
            http_response_code(403);
            die("Error: Anda tidak bisa menghapus akun Anda sendiri.");
        }

        $this->model->delete($id); 
        header("Location: index.php?page=users");
    }
}
?>