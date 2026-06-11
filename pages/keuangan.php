<?php
// pages/keuangan.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Proteksi Halaman
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    exit('<div class="p-4 text-xs text-red-600 font-bold uppercase tracking-wider">Akses Ditolak!</div>');
}
?>
<div id="page-keuangan" class="page-content hidden">
    
    <div id="locked-state" class="hidden text-center py-12">
        <div class="max-w-md mx-auto bg-white rounded-2xl p-6 border border-slate-200 shadow-xl">
            <div class="inline-flex bg-emerald-50 text-emerald-500 w-14 h-14 rounded-full items-center justify-center text-3xl mb-4">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h4 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider">Laporan Terkunci</h4>
            <p class="text-xs text-slate-500 font-medium mt-1 leading-normal">Fitur ini hanya dapat diakses oleh Direktur atau Teknisi yang berwenang.</p>
        </div>
    </div>

    <div id="unlocked-state" class="space-y-6">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-5 border border-[#e2e8f0] rounded-xl shadow-xs">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Pemasukan Kotor</span>
                <h2 class="text-xl font-extrabold text-slate-800 tracking-tight mt-2" id="tot-kotor">Rp 0</h2>
            </div>
            <div class="bg-white p-5 border-l-4 border-l-red-500 border-y border-r border-[#e2e8f0] rounded-r-xl shadow-xs">
                <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Total Pengeluaran Modal Part</span>
                <h2 class="text-xl font-extrabold text-red-600 tracking-tight mt-2" id="tot-modal">Rp 0</h2>
            </div>
            <div class="bg-[#10b981] p-5 rounded-xl shadow-lg shadow-emerald-500/20">
                <span class="text-[10px] font-bold text-emerald-100 uppercase tracking-wider" id="label-komisi-title">Komisi Anda (35%)</span>
                <h2 class="text-xl font-black text-white tracking-tight mt-2" id="tot-komisi">Rp 0</h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
            <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                <i class="bi bi-calendar3 text-emerald-600"></i> <span>Rangkuman Gaji Bulanan Teknisi</span>
            </h5>
            <div class="overflow-x-auto custom-scroll">
                <table class="w-full text-left border-collapse text-xs min-w-[600px]">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="px-4 py-3">Bulan</th>
                            <th class="px-4 py-3">Teknisi Nico (35%)</th>
                            <th class="px-4 py-3">Teknisi Bahri (35%)</th>
                            <th class="px-4 py-3">Teknisi Ono (35%)</th>
                            <th class="px-4 py-3 bg-emerald-50/50 text-emerald-700">Total Pengeluaran</th>
                        </tr>
                    </thead>
                    <tbody id="tabelRekapBulanan" class="divide-y divide-slate-100 font-medium text-slate-700">
                        </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
            <div class="flex items-center justify-between">
                <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <i class="bi bi-clipboard-data text-emerald-600"></i> <span>Rincian Komisi Per Unit</span>
                </h5>
                <span class="inline-block bg-emerald-100 text-emerald-700 uppercase tracking-wider font-extrabold text-[9px] px-2.5 py-1.5 rounded" id="access-badge">View</span>
            </div>
            <div class="overflow-x-auto custom-scroll">
                <table class="w-full text-left border-collapse text-xs min-w-[650px]">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                            <th class="px-4 py-3">Unit Laptop</th>
                            <th class="px-4 py-3">Teknisi</th>
                            <th class="px-4 py-3">Grand Biaya</th>
                            <th class="px-4 py-3">Modal Part</th>
                            <th class="px-4 py-3">Laba Bersih</th>
                            <th class="px-4 py-3 text-emerald-700 font-bold">Komisi (35%)</th>
                        </tr>
                    </thead>
                    <tbody id="tabelKeuangan" class="divide-y divide-slate-100 font-medium text-slate-600">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>