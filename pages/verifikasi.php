<?php
// pages/verifikasi.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Proteksi Otoritas Akses: Hanya pengguna front_admin atau direktur yang boleh membuka halaman ini
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true || ($_SESSION['role'] !== 'front_admin' && $_SESSION['role'] !== 'direktur')) {
    exit('<div class="p-4 text-xs text-red-600 font-bold uppercase tracking-wider">Akses Ditolak! Halaman ini dikunci khusus untuk Administrator Sistem.</div>');
}
?>
<!-- Container Halaman Mandiri Verifikasi Kasir & Penyerahan Unit -->
<div id="page-verifikasi" class="page-content hidden">
    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
        <!-- Header Halaman -->
        <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
            <div class="bg-emerald-500/10 text-emerald-600 w-8 h-8 rounded-xl flex items-center justify-center text-sm">
                <i class="bi bi-cash-coin"></i>
            </div>
            <div>
                <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Kasir Verifikasi & Penyerahan Laptop</h5>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Selesaikan pelunasan pembayaran pelanggan dan sahkan penyerahan unit laptop yang telah diperbaiki.</p>
            </div>
        </div>

        <!-- Tabel Antrean Unit Siap Diambil -->
        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-left border-collapse text-xs min-w-[750px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                        <th class="px-4 py-3">ID Nota / Pelanggan</th>
                        <th class="px-4 py-3">Merek & Model Unit</th>
                        <th class="px-4 py-3">Teknisi Handle</th>
                        <th class="px-4 py-3">Total Tagihan</th>
                        <th class="px-4 py-3">Status Saat Ini</th>
                        <th class="px-4 py-3 text-center">Tindakan Kasir</th>
                    </tr>
                </thead>
                <tbody id="tabelVerifikasiKasir" class="divide-y divide-slate-100 font-medium text-slate-600">
                    <!-- Data unit yang belum selesai akan dirender otomatis oleh JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL INTEGRASI: KASIR PELUNASAN & HITUNG KEMBALIAN -->
<!-- ======================================================== -->
<div id="modalKasirMandiri" class="hidden fixed inset-0 z-[1060] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs print:hidden animate-fade-in">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all flex flex-col">
        
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
            <div class="flex items-center gap-2">
                <i class="bi bi-wallet2 text-emerald-600 text-lg"></i>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Pelunasan Nota Kasir Warrior</h3>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition" onclick="closeKasirMandiriModal()">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>

        <form id="formKasirMandiri" class="p-6 space-y-4 text-xs">
            <input type="hidden" id="km-id">
            
            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 space-y-2">
                <div class="flex justify-between"><span class="text-slate-400 font-bold">ID NOTA:</span><span id="km-txt-id" class="font-extrabold text-slate-900">-</span></div>
                <div class="flex justify-between"><span class="text-slate-400 font-bold">PELANGGAN:</span><span id="km-txt-nama" class="font-bold text-slate-800">-</span></div>
                <div class="flex justify-between"><span class="text-slate-400 font-bold">UNIT LAPTOP:</span><span id="km-txt-merk" class="font-medium text-slate-700">-</span></div>
                <div class="flex justify-between border-t border-slate-200 pt-2"><span class="text-slate-500 font-extrabold">TOTAL TAGIHAN:</span><span id="km-txt-tagihan" class="font-black text-slate-900 text-sm">-</span></div>
            </div>

            <!-- Input Modal Sparepart -->
            <div>
                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Biaya Modal Suku Cadang / Sparepart Toko (Rp)</label>
                <div class="flex">
                    <span class="bg-slate-100 border border-slate-200 border-r-0 px-3 py-2 text-slate-500 rounded-l-lg flex items-center font-bold">Rp</span>
                    <input type="number" id="km-modal" required min="0" value="0" oninput="hitungMundurKembalian()"
                        class="w-full bg-[#f8fafc] border border-slate-200 px-3 py-2 text-xs font-bold text-red-600 rounded-r-lg outline-none focus:bg-white focus:border-[#10b981] transition">
                </div>
            </div>

            <!-- Input Cash Pembayaran -->
            <div>
                <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Jumlah Uang Tunai Diterima (Rp)</label>
                <div class="flex">
                    <span class="bg-slate-100 border border-slate-200 border-r-0 px-3 py-2 text-slate-500 rounded-l-lg flex items-center font-bold">Rp</span>
                    <input type="number" id="km-bayar" required min="0" oninput="hitungMundurKembalian()"
                        class="w-full bg-[#f8fafc] border border-slate-200 px-3 py-2 text-xs font-black text-slate-900 rounded-r-lg outline-none focus:bg-white focus:border-[#10b981] transition" placeholder="Masukkan nominal cash">
                </div>
            </div>

            <!-- Kembalian Area -->
            <div class="flex justify-between items-center p-3 bg-emerald-50 border border-emerald-100 rounded-xl">
                <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-wider">Uang Kembalian Pelanggan</span>
                <span id="km-txt-kembalian" class="text-sm font-black text-emerald-700">Rp 0</span>
            </div>

            <!-- Buttons -->
            <div class="pt-2 border-t border-slate-100 flex justify-end gap-2">
                <button type="button" onclick="closeKasirMandiriModal()" class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-4 py-2 rounded-xl uppercase tracking-wider cursor-pointer">Batal</button>
                <button type="submit" class="bg-[#10b981] hover:bg-[#059669] text-white font-bold px-5 py-2 rounded-xl uppercase tracking-wider shadow-md shadow-emerald-900/10 cursor-pointer">Sahkan Pembayaran & Keluar</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.closeKasirMandiriModal = () => {
        const modal = document.getElementById('modalKasirMandiri');
        if(modal) modal.classList.add('hidden');
    };
</script>