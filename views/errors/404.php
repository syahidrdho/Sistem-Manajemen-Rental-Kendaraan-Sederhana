<?php
// File: views/errors/404.php
// (Asumsi header.php ada di root)
$page_title = "404 Not Found";
include 'header.php';
?>

<div class="text-center">
    <h1 class="text-9xl font-bold text-danger">404</h1>
    <p class="text-3xl font-medium text-white mt-4">Halaman Tidak Ditemukan</p>
    <p class="text-text-secondary-dark mt-2">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan.</p>
    <div class="mt-8">
        <a href="index.php?page=dashboard" class="btn-primary">
            <span class="material-symbols-outlined text-base">home</span>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?php
include 'footer.php';
?>