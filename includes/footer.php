<?php
/**
 * Warrior Computer - System Footer & Script Loader
 * File: includes/footer.php
 * Deskripsi: Menutup tag struktur HTML, menginjeksi session PHP ke global window,
 * dan memuat aset JavaScript secara modular berurutan.
 */
?>

    <script>
        /**
         * Mengunci data session dari server PHP ke dalam Global Object Window Browser.
         * Hal ini membuat script modular (.js) di luar lingkungan PHP tetap dapat 
         * mengenali Role Pengguna secara real-time demi keamanan UI dan validasi data.
         */
        window.PHP_SESSION_ROLE = '<?php echo $_SESSION['role'] ?? "front_admin"; ?>';
        window.PHP_IS_LOGGED_IN = <?php echo isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] ? "true" : "false"; ?>;
    </script>

    <script src="assets/js/dashboard.js"></script>

    <script src="assets/js/monitor.js"></script>

    <script src="assets/js/kasir.js"></script>

    <script src="assets/js/keuangan.js"></script>

    <script>
        // Log status sinkronisasi sistem modular pada console browser saat DOM siap
        console.log("=== Warrior Computer Modular JS System Loaded ===");
        console.log("Authorized Role : " + window.PHP_SESSION_ROLE);
        console.log("Session Status  : " + (window.PHP_IS_LOGGED_IN ? "ACTIVE" : "INACTIVE"));
    </script>
</body>
</html>