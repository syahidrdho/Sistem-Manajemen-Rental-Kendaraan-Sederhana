<?php
// File: config.php (Versi Ultra-Bersih - Siap GitHub)

// Mulai session di paling atas
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Koneksi Database
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'rental_kendaraan'; 
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// 2. Muat file Inti (Core)
require_once "core/CSRF.php";
require_once "core/Validator.php";
require_once "core/Sanitizer.php";
require_once "core/DateHelper.php";

?>