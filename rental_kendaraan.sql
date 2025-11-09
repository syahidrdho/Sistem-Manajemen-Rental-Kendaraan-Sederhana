-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2025 pada 06.12
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rental_kendaraan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id_kendaraan` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `merk` varchar(50) NOT NULL,
  `no_plat` varchar(10) NOT NULL,
  `status` enum('tersedia','disewa') DEFAULT 'tersedia',
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kendaraan`
--

INSERT INTO `kendaraan` (`id_kendaraan`, `jenis`, `merk`, `no_plat`, `status`, `deleted_at`) VALUES
(1, 'Mobil', 'Toyota Avanza', 'B 1234 ABC', 'disewa', NULL),
(2, 'Mobil', 'Honda Brio', 'D 5679 DEF', 'disewa', NULL),
(3, 'Mobil', 'Mitsubishi Pajero', 'KT 9101 GH', 'disewa', NULL),
(4, 'Mobil', 'Suzuki Ertiga', 'B 1112 IJK', 'disewa', NULL),
(5, 'Motor', 'Honda Beat', 'DA 1212 JK', 'disewa', NULL),
(6, 'Motor', 'Yamaha Xsr', 'KT 1497 LO', 'tersedia', NULL),
(8, 'Motor', 'Vespa', 'B 1781 MOP', 'disewa', NULL),
(9, 'Mobil', 'Toyota Avanza', 'KT 1234 AB', 'tersedia', NULL),
(10, 'Mobil', 'Mitsubishi Pajero', 'KT 5678 CD', 'tersedia', NULL),
(11, 'Mobil', 'Honda Civic', 'KT 9101 EF', 'disewa', NULL),
(12, 'Mobil', 'Honda Brio', 'KT 2121 GH', 'tersedia', NULL),
(13, 'Mobil', 'Suzuki Ertiga', 'KT 3434 IJ', 'tersedia', NULL),
(14, 'Mobil', 'Toyota Fortuner', 'KT 4545 KL', 'disewa', NULL),
(15, 'Mobil', 'Toyota Vios', 'KT 5656 MN', 'tersedia', NULL),
(16, 'Mobil', 'Daihatsu Ayla', 'KT 7878 OP', 'tersedia', NULL),
(19, 'Mobil', 'Toyota Pajero', 'B 34534', 'tersedia', NULL),
(23, 'Mobil Tes', 'Tes', 'B 11111 AB', 'tersedia', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `no_ktp` varchar(20) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `alamat`, `no_hp`, `no_ktp`, `deleted_at`) VALUES
(1, 'Budi Santoso', 'Jl. Merdeka No. 10, Jakarta', '0812-3456-7890', '3201234567890001', NULL),
(2, 'Citra Lestari', 'Jl. Sudirman Kav. 5, Bandung', '085678901234', '3202987654321002', NULL),
(3, 'Agus Setiawan', 'Jl. Pahlawan No. 15, Surabaya', '087812345678', '3501010101010003', NULL),
(4, 'Syahid', 'Jl. Sei Wain, Balikpapan', '08675674535', '643456788765434567', NULL),
(5, 'Abrar', 'Jln. Balikpapan', '087248561378', '628472846282774284', NULL),
(6, 'Farras', 'Jln. Tomat', '0801293417417', '642147814791241', NULL),
(10, 'Agus Wijaya', 'Jl. G. Subroto No. 88, Balikpapan', '081198765432', '3501234567890003', NULL),
(11, 'Dewi Anggraini', 'Jl. Pahlawan No. 12, Bandung', '087855667788', '3501234567890004', NULL),
(12, 'Eko Prasetyo', 'Jl. Diponegoro No. 23, Semarang', '082123456789', '3501234567890005', NULL),
(13, 'Fitriani', 'Jl. Ahmad Yani No. 34, Yogyakarta', '089912345678', '3501234567890006', NULL),
(14, 'Gilang Ramadhan', 'Jl. Imam Bonjol No. 45, Medan', '081311223344', '3501234567890007', NULL),
(19, 'Muhammad Fadil', 'Jln. Banjar', '62834765473210', '871454781561892424', NULL),
(20, 'Jefri Nichol', 'Jln. Jakarta', '628956285246', '4269268951948682748', NULL),
(21, 'Devin', 'Jln. Balikpapan', '0842-8258-2684', '62492749568992424', NULL),
(22, 'geo', 'Jln. Surabaya', '0832-7842-6832', '5242985287529252', NULL),
(23, 'Sasha', 'Jln. Surabaya', '0839-4753-82526', '6249472956992745282', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_sewa` int(11) DEFAULT NULL,
  `jumlah_bayar` decimal(10,2) DEFAULT NULL,
  `tgl_bayar` date DEFAULT NULL,
  `metode_bayar` enum('tunai','kartu','transfer') NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_sewa`, `jumlah_bayar`, `tgl_bayar`, `metode_bayar`, `deleted_at`) VALUES
(1, 1, 600000.00, '2025-10-01', 'transfer', NULL),
(2, 2, 2000000.00, '2025-09-28', 'kartu', NULL),
(4, 3, 700000.00, '2025-10-02', 'transfer', NULL),
(5, 5, 300000.00, '2025-10-06', 'tunai', NULL),
(6, 6, 250000.00, '2025-10-09', 'transfer', NULL),
(7, 7, 150000.00, '2025-10-09', 'tunai', NULL),
(14, 19, 450000.00, '2025-10-11', 'transfer', NULL),
(15, 20, 350000.00, '2025-10-11', 'tunai', NULL),
(16, 21, 150000.00, '2025-10-11', 'tunai', NULL),
(18, 23, 300000.00, '2025-10-11', 'transfer', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `id_kendaraan` int(11) NOT NULL,
  `nama_peminjam` varchar(100) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengembalian`
--

CREATE TABLE `pengembalian` (
  `id_pengembalian` int(11) NOT NULL,
  `id_sewa` int(11) DEFAULT NULL,
  `tgl_dikembalikan` date DEFAULT NULL,
  `denda` decimal(10,2) DEFAULT 0.00,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengembalian`
--

INSERT INTO `pengembalian` (`id_pengembalian`, `id_sewa`, `tgl_dikembalikan`, `denda`, `deleted_at`) VALUES
(1, 1, '2025-10-03', 1.00, NULL),
(3, 2, '2025-09-28', 0.00, NULL),
(4, 3, '2025-10-04', 0.00, NULL),
(5, 5, '2025-10-24', 0.00, NULL),
(6, 6, '2025-10-15', 0.00, NULL),
(7, 7, '2025-10-16', 0.00, NULL),
(13, 19, '2025-10-25', 0.00, NULL),
(14, 20, '2025-10-22', 0.00, NULL),
(15, 21, '2025-10-14', 0.00, NULL),
(17, 23, '2025-10-14', 0.00, NULL),
(22, 28, '2025-11-06', 1.00, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_sewa`
--

CREATE TABLE `transaksi_sewa` (
  `id_sewa` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `id_kendaraan` int(11) DEFAULT NULL,
  `tgl_sewa` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `total_biaya` decimal(10,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_sewa`
--

INSERT INTO `transaksi_sewa` (`id_sewa`, `id_pelanggan`, `id_kendaraan`, `tgl_sewa`, `tgl_kembali`, `total_biaya`, `deleted_at`) VALUES
(1, 1, 2, '2025-10-01', '2025-10-03', 600000.00, NULL),
(2, 2, 3, '2025-09-28', '2025-10-02', 2000000.00, NULL),
(3, 3, 1, '2025-10-02', '2025-10-04', 700000.00, NULL),
(5, 4, 8, '2025-10-06', '2025-10-24', 300000.00, NULL),
(6, 5, 4, '2025-10-09', '2025-10-15', 250000.00, NULL),
(7, 6, 5, '2025-10-09', '2025-10-16', 150000.00, NULL),
(19, 10, 14, '2025-10-10', '2025-10-25', 450000.00, NULL),
(20, 11, 14, '2025-10-11', '2025-10-22', 350000.00, NULL),
(21, 12, 16, '2025-10-10', '2025-10-14', 150000.00, NULL),
(23, 14, 12, '2025-10-11', '2025-10-14', 300000.00, NULL),
(28, 23, 6, '2025-11-06', '2025-11-08', 100000.00, NULL),
(29, 22, 4, '2025-11-06', '2025-11-08', 100000.00, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manajer','karyawan') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `username`, `password`, `role`) VALUES
(1, 'Edo', 'Edo', '$2y$10$S6Ap.J2jwqjrFxO9H.Lvh.o5qj8d04UEgK24HdTU2vUbefJPlBIx.', 'admin'),
(2, 'Syahid Ridho', 'Syahid', '$2y$10$j3gK9qAXoHuLteKsPj6UweHiPN9/ocnaPBYHFg84PiY3/jI2acv46', 'karyawan'),
(3, 'Alexa', 'Alexa', '$2y$10$UbBHtnvdXHXfSgfaqTbfAu3KhODR5zLjjc.54WSb/sN6VUjK70afC', 'manajer');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id_kendaraan`),
  ADD UNIQUE KEY `no_plat` (`no_plat`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD UNIQUE KEY `no_ktp` (`no_ktp`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_sewa` (`id_sewa`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_kendaraan` (`id_kendaraan`);

--
-- Indeks untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD PRIMARY KEY (`id_pengembalian`),
  ADD KEY `id_sewa` (`id_sewa`);

--
-- Indeks untuk tabel `transaksi_sewa`
--
ALTER TABLE `transaksi_sewa`
  ADD PRIMARY KEY (`id_sewa`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `id_kendaraan` (`id_kendaraan`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id_kendaraan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  MODIFY `id_pengembalian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `transaksi_sewa`
--
ALTER TABLE `transaksi_sewa`
  MODIFY `id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `transaksi_sewa` (`id_sewa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id_kendaraan`);

--
-- Ketidakleluasaan untuk tabel `pengembalian`
--
ALTER TABLE `pengembalian`
  ADD CONSTRAINT `pengembalian_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `transaksi_sewa` (`id_sewa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi_sewa`
--
ALTER TABLE `transaksi_sewa`
  ADD CONSTRAINT `transaksi_sewa_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_sewa_ibfk_2` FOREIGN KEY (`id_kendaraan`) REFERENCES `kendaraan` (`id_kendaraan`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
