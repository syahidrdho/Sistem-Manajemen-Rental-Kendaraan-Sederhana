<?php
// File: core/CSRF.php (Versi Ultra-Bersih - Siap GitHub)

// Class untuk menangani Cross-Site Request Forgery (CSRF).
class CSRF {
    
    private static $token_name = 'csrf_token';

    public static function generateToken() {
        if (empty($_SESSION[self::$token_name])) {
            try {
                $_SESSION[self::$token_name] = bin2hex(random_bytes(32));
            } catch (Exception $e) {
                // Fallback jika random_bytes gagal
                $_SESSION[self::$token_name] = md5(uniqid(rand(), true));
            }
        }
        return $_SESSION[self::$token_name];
    }

    // Alias untuk generateToken() agar mudah dipanggil di view
    public static function getToken() {
        return self::generateToken();
    }

    public static function verifyOrFail() {
        $token = $_POST[self::$token_name] ?? null;
        $session_token = $_SESSION[self::$token_name] ?? null;

        if (empty($token) || empty($session_token) || !hash_equals($session_token, $token)) {
            http_response_code(403);
            die("Error 403: Invalid CSRF Token. Aksi dibatalkan. Silakan kembali dan coba lagi.");
        }
        
        // Hapus token setelah dipakai (mencegah replay attacks)
        unset($_SESSION[self::$token_name]);
    }
}
?>