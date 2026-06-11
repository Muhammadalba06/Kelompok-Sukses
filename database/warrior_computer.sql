-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Bulan Mei 2026 pada 13.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warrior_computer`
--
CREATE DATABASE IF NOT EXISTS `warrior_computer` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `warrior_computer`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--
-- Catatan: Password di bawah ini menggunakan contoh teks biasa agar sinkron dengan file login.php Anda. 
-- Jika nanti menerapkan password_hash(), panjang kolom 255 di atas sudah sangat aman menampung string hash.

INSERT INTO `pengguna` (`id`, `username`, `nama`, `role`, `password`, `created_at`) VALUES
(1, 'front_admin', 'Administrator', 'front_admin', 'depan123', '2026-05-30 05:24:32'),
(2, 'direktur', 'Direktur (Mulyanto)', 'direktur', 'Warrior123', '2026-05-30 05:24:32'),
(3, 'nico', 'Teknisi: Nico', 'Nico', 'nico123', '2026-05-30 05:24:32'),
(4, 'bahri', 'Teknisi: Bahri', 'Bahri', 'bahri123', '2026-05-30 05:24:32'),
(5, 'ono', 'Teknisi: Ono', 'Ono', 'ono123', '2026-05-30 05:24:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `servis`
--

DROP TABLE IF EXISTS `servis`;
CREATE TABLE `servis` (
  `id` varchar(20) NOT NULL,
  `tgl` date NOT NULL,                                 -- Diubah menjadi DATE agar pencatatan laporan keuangan & filter waktu akurat
  `nama` varchar(100) NOT NULL,
  `wa` varchar(20) NOT NULL,
  `merk` varchar(100) NOT NULL,
  `kelengkapan` text DEFAULT NULL,
  `kerusakan` text DEFAULT NULL,
  `penanganan` text DEFAULT NULL,
  `garansi` varchar(50) DEFAULT NULL,
  `biaya` int(11) NOT NULL DEFAULT 0,
  `modal` int(11) NOT NULL DEFAULT 0,                  -- Modal suku cadang/sparepart (diisi opsional setelah unit dicek teknisi)
  `status` varchar(50) DEFAULT 'Cek',                  -- 'Cek', 'Pengerjaan', 'Batal', 'Selesai', 'Diambil'
  `teknisi` varchar(50) DEFAULT '',
  `meja_teknisi` varchar(50) DEFAULT '',               -- Ditambahkan: Baru diisi saat teknisi mengambil unit dari ruang penyimpanan
  `tgl_selesai` date DEFAULT NULL,                     -- Ditambahkan: Mempermudah perhitungan masa berlaku garansi nota pelanggan
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `servis`
--
-- Format tanggal disesuaikan menjadi standar SQL (YYYY-MM-DD) agar query date-range berfungsi maksimal.

INSERT INTO `servis` (`id`, `tgl`, `nama`, `wa`, `merk`, `kelengkapan`, `kerusakan`, `penanganan`, `garansi`, `biaya`, `modal`, `status`, `teknisi`, `meja_teknisi`, `tgl_selesai`, `created_at`) VALUES
('W-129381', '2026-05-16', 'Budi Setiawan', '085117507998', 'Asus ROG Zephyrus G14', 'Unit + Charger', 'Keyboard double typing dan baterai drop', 'Ganti keyboard & kalibrasi baterai', '1 Bulan', 1200000, 450000, 'Selesai', 'Nico', 'Meja 01', '2026-05-18', '2026-05-16 03:11:12'),
('W-129382', '2026-05-15', 'Dewi Lestari', '081298453311', 'Lenovo Ideapad Slim 3', 'Unit Only', 'Sering BSOD (Blue Screen)', 'Instal ulang OS & ganti NVMe SSD 512GB', '3 Bulan', 850000, 300000, 'Selesai', 'Bahri', 'Meja 03', '2026-05-15', '2026-05-15 02:15:30'),
('W-129383', '2026-05-14', 'Ahmad Subarjo', '085277884433', 'Acer Swift 3', 'Unit + Charger + Tas', 'Engsel patah sebelah kanan', 'Rekondisi dudukan engsel & ganti cover LCD', '1 Bulan', 450000, 50000, 'Selesai', 'Ono', 'Meja 02', '2026-05-14', '2026-05-14 01:20:00'),
('W-129384', '2026-05-16', 'Siti Rahma', '089533221100', 'Macbook Air M1 2020', 'Unit + Charger', 'Mati total terkena air', 'Pembersihan korosi & perbaikan jalur logic board', '1 Bulan', 2500000, 200000, 'Pengerjaan', 'Nico', 'Meja 01', NULL, '2026-05-16 04:00:00');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;