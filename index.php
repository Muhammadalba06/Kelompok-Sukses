<?php
// index.php
/**
 * Warrior Computer - Main Dashboard Engine
 * File: index.php
 * Deskripsi: Orchestrator utama yang menyatukan seluruh komponen layout,
 * halaman kerja (pages), dan modal asinkronus.
 */

// Proteksi session dan koneksi DB dipindah ke includes/head.php
include_once 'includes/head.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    </head>
<body class="bg-[#f8fafc] text-[#1e293b] min-h-screen overflow-hidden antialiased">

    <div id="sidebarOverlay" class="hidden fixed inset-0 bg-[#0f172a]/40 backdrop-blur-xs z-[1040] print:hidden" onclick="toggleSidebar()"></div>

    <?php include_once 'includes/sidebar.php'; ?>

    <div class="main-content lg:ml-[260px] min-h-screen flex flex-col transition-all duration-300 ease-in-out print:hidden">
        
        <?php include_once 'includes/header.php'; ?>

        <main class="p-4 lg:p-6 overflow-y-auto flex-grow h-[calc(100vh-64px)] custom-scroll">
            <?php 
            // 1. Halaman Input Data Servis Baru (Hanya untuk Admin & Direktur)
            if (!$isTeknisi) { 
                include_once 'pages/input.php'; 
            } else { 
                echo '<div id="page-input" class="page-content hidden"><div class="p-6 bg-red-50 text-red-600 font-bold rounded-xl border border-red-200 text-xs uppercase tracking-wider">AKSES DITOLAK! HALAMAN INI DIKUNCI KHUSUS ADMINISTRATOR SISTEM.</div></div>'; 
            }
            
            // 2. Halaman Monitoring Proses & Pekerjaan (Terbuka untuk Semua Role)
            include_once 'pages/monitor.php';
            
            // 3. Halaman Lembar Verifikasi Selesai & Kasir (Hanya untuk Admin & Direktur)
            if (!$isTeknisi) { 
                include_once 'pages/verifikasi.php'; 
            } else { 
                echo '<div id="page-verifikasi" class="page-content hidden"><div class="p-6 bg-red-50 text-red-600 font-bold rounded-xl border border-red-200 text-xs uppercase tracking-wider">AKSES DITOLAK! HALAMAN VERIFIKASI FINANSIAL DIKUNCI.</div></div>'; 
            }
            
            // 4. Halaman Laporan Keuangan & Laba Komisi 
            include_once 'pages/keuangan.php';
            
            // 5. UPDATE BARU: Halaman Arsip Riwayat Laporan Transaksi Lunas Pelunasan
            include_once 'pages/laporan.php';
            
            // 6. Halaman Manajemen Pengguna Sistem (Hanya Spesifik Hak Akses front_admin)
            if ($currentRole === 'front_admin') { 
                include_once 'pages/pengguna.php'; 
            } else { 
                echo '<div id="page-pengguna" class="page-content hidden"><div class="p-6 bg-red-50 text-red-600 font-bold rounded-xl border border-red-200 text-xs uppercase tracking-wider">AKSES DITOLAK! MANAJEMEN PENGGUNA HANYA UNTUK UTAMA ADMINISTRATOR.</div></div>'; 
            }
            ?> 
        </main>
    </div>

    <?php include_once 'includes/modal-detail.php'; ?>

    <?php include_once 'includes/modal-kasir.php'; ?>

    <div id="print-area-wrapper">
        <?php include_once 'includes/print-nota.php'; ?>
    </div>
    
    <?php include_once 'includes/footer.php'; ?> 

</body>
</html>