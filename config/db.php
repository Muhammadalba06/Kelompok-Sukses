<?php
/**
 * Warrior Computer - Database Connection Configuration
 * File: config/db.php
 * Deskripsi: Menginisialisasi koneksi server MySQL menggunakan driver MySQLi 
 * dengan pendekatan Object-Oriented (OO).
 */

// PROTEKSI OUTPUT: Membuka buffer output untuk menahan spasi liar/siluman agar tidak bocor ke output JSON AJAX
if (ob_get_level() == 0) {
    ob_start();
}

$host   = "localhost";
$user   = "root";             // User default bawaan XAMPP
$pass   = "";                 // Password default bawaan XAMPP (kosong)
$db     = "warrior_computer"; // Nama database Anda di phpMyAdmin

// Membuat koneksi menggunakan ekstensi MySQLi (Object-Oriented)
$conn = new mysqli($host, $user, $pass, $db);

// Memeriksa status keberhasilan koneksi ke server database
if ($conn->connect_error) {
    // Jika gagal, set header JSON dan kirim pesan error yang valid agar tidak merusak JS
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => "Koneksi Database Gagal: " . $conn->connect_error
    ]);
    exit;
}

// Set charset ke utf8mb4 agar penyimpanan data karakter khusus/simbol aman
$conn->set_charset("utf8mb4");
?>