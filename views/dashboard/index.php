<?php
// File: views/dashboard/index.php (Versi Bersih - Siap GitHub)

// Set variabel untuk layout header
$page_title = "Dashboard Ringkasan";
$active_page = "dashboard";

// Panggil layout header
include 'header.php';

// Ambil data dari DashboardController
$summary = $summary ?? [
    'total_pelanggan' => 'N/A', 
    'total_transaksi' => 'N/A', 
    'total_pendapatan' => 0
]; 
$vehicleCounts = $vehicleCounts ?? []; 
?>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-white">Dashboard Ringkasan</h1>
    <p class="text-text-secondary-dark mt-1">Selamat datang! Berikut ringkasan data rental Anda.</p>
</div>


<h2 class="text-2xl font-semibold text-white mb-4">Ketersediaan Aset (Real-Time)</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <?php 
    // Helper function untuk memilih ikon berdasarkan jenis
    function getIconForVehicle($jenis) {
        $jenisLower = strtolower($jenis);
        if (strpos($jenisLower, 'mobil') !== false) return 'directions_car';
        if (strpos($jenisLower, 'motor') !== false) return 'two_wheeler';
        if (strpos($jenisLower, 'truk') !== false) return 'local_shipping';
        return 'key'; // Ikon default
    }

    // Loop untuk menampilkan kartu ketersediaan kendaraan
    if (!empty($vehicleCounts)):
        foreach ($vehicleCounts as $jenis => $jumlah): 
    ?>
    
    <div class="bg-black/30 backdrop-blur-sm rounded-xl shadow-lg p-6 border border-white/10 hover-scale">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-full bg-primary/20 text-primary border border-primary/30">
                <span class="material-symbols-outlined text-3xl"><?= getIconForVehicle($jenis) ?></span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-text-secondary-dark font-medium"><?= htmlspecialchars($jenis) ?> Tersedia</p>
                <p class="text-3xl font-bold text-text-primary-dark"><?= htmlspecialchars($jumlah) ?></p>
            </div>
        </div>
    </div>

    <?php 
        endforeach; 
    else: 
    ?>
    <div class="col-span-1 sm:col-span-2 lg:col-span-4 bg-black/30 backdrop-blur-sm rounded-xl p-6 border border-white/10">
        <p class="text-text-secondary-dark text-center">Tidak ada kendaraan yang tersedia saat ini.</p>
    </div>
    <?php endif; ?>

</div> 
<h2 class="text-2xl font-semibold text-white mb-4">Ringkasan Bisnis</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <div class="bg-black/30 backdrop-blur-sm rounded-xl shadow-lg p-6 border border-white/10 hover-scale">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-full bg-secondary/20 text-secondary border border-secondary/30">
                 <span class="material-symbols-outlined text-3xl">group</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-text-secondary-dark font-medium">Total Pelanggan</p>
                <p class="text-3xl font-bold text-text-primary-dark"><?= htmlspecialchars($summary['total_pelanggan']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-black/30 backdrop-blur-sm rounded-xl shadow-lg p-6 border border-white/10 hover-scale">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-full bg-warning/20 text-warning border border-warning/30">
                <span class="material-symbols-outlined text-3xl">receipt_long</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-text-secondary-dark font-medium">Total Transaksi</p>
                <p class="text-3xl font-bold text-text-primary-dark"><?= htmlspecialchars($summary['total_transaksi']) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-black/30 backdrop-blur-sm rounded-xl shadow-lg p-6 border border-white/10 hover-scale">
        <div class="flex items-center">
            <div class="flex-shrink-0 w-14 h-14 flex items-center justify-center rounded-full bg-danger/20 text-danger border border-danger/30">
                <span class="material-symbols-outlined text-3xl">attach_money</span>
            </div>
            <div class="ml-4">
                <p class="text-sm text-text-secondary-dark font-medium">Total Pendapatan</p>
                <p class="text-3xl font-bold text-text-primary-dark">Rp <?= number_format($summary['total_pendapatan'] ?? 0, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>

</div> 
<?php 
// Panggil layout footer
include 'footer.php'; 
?>