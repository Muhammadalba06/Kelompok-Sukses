<?php
// includes/sidebar.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Mengambil role secara dinamis dari database melalui session server
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : 'front_admin';
?>
<aside class="sidebar fixed inset-y-0 left-0 w-[260px] bg-[#0b0f19] text-white flex flex-col z-[1050] transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0 print:hidden">
    
    <div class="p-5 flex items-center justify-between border-b border-white/5 shrink-0">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-500/15 border-2 border-[#10b981] w-9 h-9 rounded-xl flex items-center justify-center text-[#10b981] text-base">
                <i class="bi bi-laptop"></i>
            </div>
            <div class="leading-tight">
                <h4 class="text-sm font-extrabold tracking-wide text-white">WARRIOR</h4>
                <p class="text-[10px] text-[#10b981] font-bold tracking-widest uppercase">COMPUTER</p>
            </div>
        </div>
        <button class="lg:hidden text-slate-400 hover:text-white p-1 cursor-pointer" onclick="toggleSidebar()">
            <i class="bi bi-x-lg text-sm"></i>
        </button>
    </div>

    <div class="p-4 flex-grow overflow-y-auto custom-scroll space-y-1">
        
        <?php if ($userRole === 'front_admin' || $userRole === 'direktur'): ?>
        <button onclick="showPage('page-input')" id="btn-input-nav" class="w-full bg-[#10b981] hover:bg-[#059669] text-white text-xs font-bold py-2.5 px-4 rounded-xl flex items-center gap-2.5 transition uppercase tracking-wider cursor-pointer mb-4 shadow-md shadow-emerald-950/20">
            <i class="bi bi-plus-lg text-sm stroke-[2]"></i>
            <span>Input Data Baru</span>
        </button>
        <?php endif; ?>

        <a onclick="showPage('page-monitor')" id="btn-monitor" class="nav-link-custom flex items-center gap-3 text-slate-400 hover:text-white hover:bg-white/5 px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
            <i class="bi bi-display text-sm"></i>
            <span>Monitoring Proses</span>
        </a>

        <?php if ($userRole === 'front_admin' || $userRole === 'direktur'): ?>
        <a onclick="showPage('page-verifikasi')" id="btn-verifikasi" class="nav-link-custom flex items-center gap-3 text-slate-400 hover:text-white hover:bg-white/5 px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
            <i class="bi bi-cash-coin text-sm"></i>
            <span>Verifikasi & Kasir</span>
        </a>
        <?php endif; ?>

        <?php if ($userRole === 'front_admin' || $userRole === 'direktur'): ?>
        <a onclick="showPage('page-laporan')" id="btn-laporan" class="nav-link-custom flex items-center gap-3 text-slate-400 hover:text-white hover:bg-white/5 px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
            <i class="bi bi-journal-check text-sm"></i>
            <span>Laporan Transaksi</span>
        </a>
        <?php endif; ?>

        <?php if ($userRole === 'direktur' || $userRole === 'Nico' || $userRole === 'Bahri' || $userRole === 'Ono'): ?>
        <a onclick="showPage('page-keuangan')" id="btn-keuangan" class="nav-link-custom flex items-center gap-3 text-slate-400 hover:text-white hover:bg-white/5 px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
            <i class="bi bi-wallet2 text-sm"></i>
            <span>Laporan Komisi</span>
        </a>
        <?php endif; ?>

        <?php if ($userRole === 'front_admin'): ?>
        <a onclick="showPage('page-pengguna')" id="btn-pengguna" class="nav-link-custom flex items-center gap-3 text-slate-400 hover:text-white hover:bg-white/5 px-4 py-2.5 rounded-xl text-xs font-semibold transition cursor-pointer">
            <i class="bi bi-people-fill text-sm"></i>
            <span>Manajemen Pengguna</span>
        </a>
        <?php endif; ?>

    </div>

    <div class="p-4 bg-black/20 border-t border-white/5 shrink-0">
        <div class="bg-white/3 border border-white/5 rounded-xl p-3 flex flex-col gap-2.5">
            <div class="flex items-center gap-2.5">
                <div class="bg-[#10b981]/20 text-[#10b981] w-7 h-7 rounded-lg flex items-center justify-center text-xs">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <div class="overflow-hidden">
                    <label class="text-[8px] text-slate-400 uppercase tracking-widest font-extrabold block mb-0.5">Akses Sebagai</label>
                    <span id="current-user-display" class="text-xs font-bold text-white truncate block">
                        <?php echo isset($_SESSION['nama_user']) ? htmlspecialchars($_SESSION['nama_user']) : 'Administrator'; ?>
                    </span>
                </div>
            </div>
            
            <a href="logout.php" class="w-full text-center bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white text-[10px] font-bold py-1.5 rounded-lg transition border border-red-500/20 tracking-wider uppercase block cursor-pointer">
                <i class="bi bi-box-arrow-left mr-1"></i> Keluar Sistem
            </a>
        </div>
    </div>
</aside>