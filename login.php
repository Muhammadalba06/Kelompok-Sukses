<?php
// login.php
session_start();

// Jika user terdeteksi sudah login di session server, langsung arahkan ke dashboard
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warrior Computer Service Monitoring</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 antialiased">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 space-y-6 border border-slate-100">
        <div class="text-center space-y-2">
            <div class="inline-flex bg-emerald-50 border-2 border-[#10b981] w-12 h-12 rounded-xl items-center justify-center text-[#10b981] text-xl font-bold shadow-sm shadow-emerald-100 mx-auto">
                <i class="bi bi-laptop"></i>
            </div>
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">Warrior Computer</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Service Monitoring System</p>
            </div>
        </div>

        <div id="errorAlert" class="hidden bg-red-50 border-l-4 border-red-500 p-3.5 rounded-r-xl transition-all duration-300">
            <div class="flex items-start">
                <div class="shrink-0 text-red-500 mt-0.5">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="ml-3">
                    <p id="errorText" class="text-xs text-red-700 font-semibold"></p>
                </div>
            </div>
        </div>

        <form id="formLogin" onsubmit="handleFormLogin(event)" class="space-y-5">
            <div>
                <label for="username" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" id="username" required autocomplete="username"
                        class="w-full bg-[#f8fafc] border border-[#e2e8f0] pl-9 pr-4 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition" 
                        placeholder="Masukkan username">
                </div>
            </div>

            <div>
                <label for="password" class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 text-sm">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" id="password" required autocomplete="current-password"
                        class="w-full bg-[#f8fafc] border border-[#e2e8f0] pl-9 pr-4 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition" 
                        placeholder="••••••••">
                </div>
            </div>

            <button type="submit" id="btnLogin"
                class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-bold py-2.5 px-4 rounded-xl text-xs tracking-wider shadow-md shadow-emerald-100 hover:shadow-lg transition duration-200 mt-2 flex items-center justify-center gap-2 cursor-pointer">
                <span id="btnText">LOGIN</span>
                <i class="bi bi-box-arrow-in-right text-sm"></i>
            </button>
        </form>
    </div>

    <script>
        function handleFormLogin(e) {
            e.preventDefault();
            
            const user = document.getElementById('username').value.trim();
            const pass = document.getElementById('password').value;
            const alertDiv = document.getElementById('errorAlert');
            const alertText = document.getElementById('errorText');
            const btnLogin = document.getElementById('btnLogin');
            const btnText = document.getElementById('btnText');

            // Reset status tampilan tombol dan alert
            alertDiv.classList.add('hidden');
            btnText.innerText = "MEMPROSES...";
            btnLogin.disabled = true;

            // Memasukkan parameter POST action tanpa input role manual
            const formData = new FormData();
            formData.append('action', 'login_auth');
            formData.append('username', user);
            formData.append('password', pass);

            // Kirim data via AJAX Fetch background request
            fetch('api/proses.php', {
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) throw new Error('Koneksi server bermasalah');
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    // Set parameter Session Storage client side agar sinkron dengan index.php
                    sessionStorage.setItem('isLoggedIn', 'true');
                    sessionStorage.setItem('currentUser', data.username);
                    sessionStorage.setItem('currentRole', data.role);
                    
                    alert('Login Berhasil! Selamat datang ' + data.nama + '.');
                    window.location.href = 'index.php';
                } else {
                    alertText.innerText = data.error || "Username atau Password yang Anda masukkan salah.";
                    alertDiv.classList.remove('hidden');
                    btnText.innerText = "LOGIN";
                    btnLogin.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alertText.innerText = "Gagal terhubung ke database. Periksa kembali config/db.php Anda.";
                alertDiv.classList.remove('hidden');
                btnText.innerText = "LOGIN";
                btnLogin.disabled = false;
            });
        }
    </script>
</body>
</html>