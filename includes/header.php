<?php
/**
 * Warrior Computer - Dashboard Top Header Bar
 * File: includes/header.php
 * Deskripsi: Menyediakan bar navigasi atas, pemicu menu responsif mobile,
 * judul halaman dinamis, dan komponen penampil tanggal.
 */
?>

<header class="bg-white px-4 lg:px-6 h-16 border-b border-[#e2e8f0] flex items-center justify-between shrink-0">
    <div class="flex items-center gap-3">
        <button class="lg:hidden p-2 text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg transition cursor-pointer" 
                onclick="toggleSidebar()" 
                aria-label="Buka Menu Navigasi">
            <i class="bi bi-list text-xl"></i>
        </button>
        
        <h1 id="page-title" class="text-sm lg:text-lg font-extrabold text-[#0f172a] tracking-tight">
            Dashboard
        </h1>
    </div>

    <div class="hidden sm:block">
        <span id="current-date" class="text-xs font-bold text-slate-500 uppercase tracking-wider"></span>
    </div>
</header>