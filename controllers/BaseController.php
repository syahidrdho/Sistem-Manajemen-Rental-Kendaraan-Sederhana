<?php
// File: controllers/BaseController.php (Versi Bersih - Siap GitHub)

/**
 * Controller induk yang berisi fungsi hak akses (RBAC).
 * Semua controller lain (kecuali Auth) akan extends ke sini.
 */
class BaseController {
    
    protected $factory;
    protected $role;

    public function __construct($factory) {
        $this->factory = $factory;
        
        // Ambil role dari session saat controller dibuat
        $this->role = $_SESSION['role'] ?? null; 
    }

    // "Penjaga" Hak Akses (Authorization)
    // Memeriksa apakah role user ada di dalam array $allowedRoles.
    protected function authorize(array $allowedRoles) {
        
        // Jika role user tidak ada di dalam array $allowedRoles
        if (!in_array($this->role, $allowedRoles)) {
            
            // Tampilkan halaman 403 (Akses Ditolak)
            http_response_code(403);
            include 'views/errors/403.php'; 
            exit;
        }
    }
}
?>