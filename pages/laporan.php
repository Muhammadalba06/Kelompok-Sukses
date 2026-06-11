<?php
// pages/laporan.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['is_logged_in'])) {
    exit('Akses ditolak.');
}
?>
<div id="page-laporan" class="page-content hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Arsip Laporan Transaksi Lunas</h5>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Daftar rekam medis seluruh unit laptop yang telah diserahkan dan diselesaikan pembayarannya.</p>
            </div>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <i class="bi bi-search text-xs"></i>
                </span>
                <input type="text" id="search-laporan" oninput="searchTabelLaporan()" class="bg-white border border-slate-200 pl-8 pr-3 py-1.5 rounded-xl text-xs font-medium text-slate-700 outline-none focus:border-emerald-500 w-full sm:w-56 transition" placeholder="Cari nota atau nama...">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                        <th class="px-4 py-3 w-[25%]">Unit / Pelanggan</th>
                        <th class="px-4 py-3 text-center w-[20%]">Teknisi Handle</th>
                        <th class="px-4 py-3 text-right w-[15%]">Modal Part</th>
                        <th class="px-4 py-3 text-right w-[15%]">Total Bayar</th>
                        <th class="px-4 py-3 text-center w-[15%]">Status</th>
                        <th class="px-4 py-3 text-center w-[10%]">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelArsipLaporan" class="divide-y divide-slate-100 text-xs text-slate-600">
                    </tbody>
            </table>
        </div>
        
    </div>
</div>