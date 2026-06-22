<?php
// index.php
/**
 * Warrior Computer - Main Dashboard Engine
 * File: index.php
 * Deskripsi: Orchestrator utama yang menyatukan seluruh komponen layout,
 * halaman kerja (pages), dan modal asinkronus dengan proteksi SPA aman.
 */

// Proteksi session dan koneksi DB dipindah ke includes/head.php
include_once 'includes/head.php'; 

/**
 * ============================================================================
 * ENGINE STANDARDISASI ROLE & AKOMODASI MULTI-ROLE (FIX AKSES DITOLAK)
 * ============================================================================
 * Menjamin akun dengan string 'Administrator' ataupun 'front_admin' memiliki 
 * tingkat otoritas yang setara di mata sistem layout utama index.
 */
// 1. Ambil data role aktif dari session jika variabel penentu belum terbentuk
if (!isset($currentRole)) {
    $currentRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';
}

// 2. Lakukan standarisasi string penamaan role admin
$isAdmin = ($currentRole === 'front_admin' || $currentRole === 'Administrator' || $currentRole === 'admin_depan');
$isDirektur = ($currentRole === 'direktur');

// 3. Set ulang flag teknisi agar tidak terjadi salah blokir halaman input & verifikasi
$isTeknisi = ($currentRole === 'teknisi');
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
            /**
             * ULTRA SAFE SPA ROUTER (SINKRONISASI AKTIF & AMAN):
             * Jika user memiliki hak akses, panggil file halaman asli.
             * Jika user ditolak (Teknisi), render DIV kosong tanpa peringatan teks/notifikasi merah.
             * Strategi ini mencegah JavaScript crash sekaligus melenyapkan pemberitahuan "Akses Terbatas".
             */

            // 1. Halaman Input Data Servis Baru
            if ($isAdmin || $isDirektur) { 
                include_once 'pages/input.php'; 
            } else { 
                // Diubah menjadi div kosong tersembunyi agar pesan error hilang total dari pandangan teknisi
                echo '<div id="page-input" class="page-content hidden"></div>'; 
            }
            
            // 2. Halaman Monitoring Proses & Pekerjaan (Terbuka untuk Semua Karyawan - Real-time)
            include_once 'pages/monitor.php';
            
            // 3. Halaman Lembar Verifikasi Selesai & Kasir
            if ($isAdmin || $isDirektur) { 
                include_once 'pages/verifikasi.php'; 
            } else { 
                // Diubah menjadi div kosong tersembunyi untuk mencegah distorsi UI
                echo '<div id="page-verifikasi" class="page-content hidden"></div>'; 
            }
            
            // 4. Halaman Laporan Keuangan & Laba Komisi (Direktur & Hak Khusus Teknisi)
            include_once 'pages/keuangan.php';
            
            // 5. Halaman Arsip Riwayat Laporan Transaksi Lunas Pelunasan
            include_once 'pages/laporan.php';
            
            // 6. Halaman Manajemen Pengguna Sistem
            if ($isAdmin || $isDirektur) { 
                include_once 'pages/pengguna.php'; 
            } else { 
                // Diubah menjadi div kosong tersembunyi demi keamanan data user
                echo '<div id="page-pengguna" class="page-content hidden"></div>'; 
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