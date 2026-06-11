<?php
/**
 * Warrior Computer - System Core Head & Security Gateway
 * File: includes/head.php
 * Deskripsi: Memulai session sistem, mengunci gerbang hak akses login (autentikasi),
 * memuat koneksi basis data, dan menginisialisasi tag <head> HTML.
 */

// 1. MEMULAI ENGINES SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. SECURITY GATEWAY: Jika tidak ada session login aktif, tendang kembali ke login.php
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// 3. LOAD CONFIGURATION & DATABASE CONNECTION
require_once 'config/db.php';

// 4. INIDIKATOR OTORISASI HAK AKSES REAL-TIME
// Ambil data session role secara real-time dari server PHP
$currentRole = $_SESSION['role'] ?? 'front_admin';

// Deteksi apakah pengguna yang masuk merupakan Teknisi Toko (Nico/Bahri/Ono)
$isTeknisi = ($currentRole !== 'front_admin' && $currentRole !== 'direktur');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warrior Computer - Dashboard Servis</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        
        /* Custom Scrollbar Utility Layout */
        .custom-scroll::-webkit-scrollbar { 
            width: 4px; 
            height: 4px; 
        }
        .custom-scroll::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 10px; 
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Utility Rule untuk menyembunyikan halaman konten default sebelum diatur JS Navigasi */
        .page-content.hidden {
            display: none !important;
        }
    </style>
</head>