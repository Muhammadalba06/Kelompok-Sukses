<?php
// logout.php
session_start();

// 1. Hapus semua variabel session di server PHP
$_SESSION = array();

// 2. Jika menggunakan cookie session, hancurkan cookie-nya juga
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hancurkan session server secara total
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logging Out...</title>
</head>
<body>
    <script>
        // 4. Bersihkan sessionStorage di sisi client browser agar UI JavaScript ikut tersinkronisasi
        sessionStorage.clear();
        
        // 5. Berikan notifikasi singkat dan tendang balik ke halaman login
        alert('Anda telah berhasil keluar dari sistem.');
        window.location.href = 'login.php';
    </script>
</body>
</html>