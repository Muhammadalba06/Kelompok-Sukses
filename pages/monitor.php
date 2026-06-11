<?php
/**
 * Warrior Computer - Service Monitoring Page Component
 * File: pages/monitor.php
 * Deskripsi: Menyediakan layout kontainer tabel antrean progres perbaikan laptop 
 * dan komponen pop-up modal pratinjau detail nota transaksi.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman: Mencegah user mengakses langsung file ini tanpa melalui login index.php
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    exit('<div class="p-4 text-xs text-red-600 font-bold uppercase tracking-wider">Akses Ditolak! Anda tidak memiliki otoritas akses langsung.</div>');
}
?>

<div id="page-monitor" class="page-content hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 space-y-4">
        
        <div class="flex items-center gap-2 pb-2">
            <div class="bg-emerald-500/10 text-emerald-600 w-8 h-8 rounded-xl flex items-center justify-center text-sm">
                <i class="bi bi-display"></i>
            </div>
            <div>
                <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Daftar Antrean & Progres Kerja</h5>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Pantau tahapan perbaikan unit laptop pelanggan secara real-time.</p>
            </div>
        </div>

        <div class="overflow-x-auto custom-scroll">
            <table class="w-full text-left border-collapse text-xs min-w-[800px]">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                        <th class="px-4 py-3">Unit / Pelanggan</th>
                        <th class="px-4 py-3">Progres Tahapan</th>
                        <th class="px-4 py-3">Teknisi Penanggung Jawab</th>
                        <th class="px-4 py-3">Modal Sparepart</th>
                        <th class="px-4 py-3">Biaya Akhir</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelMonitor" class="divide-y divide-slate-100 font-medium text-slate-600"></tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalDetailTransaksi" class="hidden fixed inset-0 z-[1060] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs print:hidden animate-fade-in">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50 shrink-0">
            <div class="flex items-center gap-2">
                <i class="bi bi-receipt text-emerald-600 text-lg"></i>
                <h3 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Pratinjau Detail Nota Transaksi</h3>
            </div>
            <button type="button" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer transition" onclick="closeDetailTransaksiModal()">
                <i class="bi bi-x-lg text-base"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto custom-scroll space-y-6 text-xs">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-slate-100 pb-5">
                <div class="space-y-2 border-r border-slate-100 pr-2">
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">ID NOTA:</span><span id="m-id" class="font-extrabold text-slate-900">-</span></div>
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">TANGGAL MASUK:</span><span id="m-tgl" class="font-bold text-slate-700">-</span></div>
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">NAMA PELANGGAN:</span><span id="m-nama" class="font-bold text-slate-800">-</span></div>
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">NO. WHATSAPP:</span><span id="m-wa" class="font-mono text-slate-700">-</span></div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">MEREK & MODEL:</span><span id="m-merk" class="font-extrabold text-slate-900">-</span></div>
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">KELENGKAPAN:</span><span id="m-kelengkapan" class="font-medium text-slate-600">-</span></div>
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">STATUS PROGRES:</span><span id="m-status" class="inline-block bg-blue-50 text-blue-700 font-bold text-[10px] px-2 py-0.5 rounded">-</span></div>
                    <div class="flex justify-between border-b border-slate-50 pb-1"><span class="text-slate-400 font-bold uppercase text-[9px]">TEKNISI HANDLE:</span><span id="m-teknisi" class="font-bold text-slate-700">-</span></div>
                </div>
            </div>

            <div class="bg-red-50/50 border border-red-100 rounded-xl p-3.5">
                <span class="text-[9px] font-extrabold text-red-500 uppercase tracking-wider block mb-1">Keluhan / Kerusakan Awal Pelanggan</span>
                <p id="m-kerusakan" class="text-slate-700 font-medium leading-relaxed">-</p>
            </div>

            <div class="space-y-2">
                <span class="text-[9px] font-extrabold text-slate-400 uppercase tracking-wider block">Rincian Tindakan Penanganan & Suku Cadang</span>
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <table class="w-full border-collapse text-left text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-2.5 w-[10%] text-center">NO</th>
                                <th class="px-4 py-2.5 w-[55%]">DETAIL TINDAKAN PENANGANAN</th>
                                <th class="px-4 py-2.5 w-[15%] text-center">GARANSI</th>
                                <th class="px-4 py-2.5 w-[20%] text-right">ESTIMASI BIAYA</th>
                            </tr>
                        </thead>
                        <tbody id="m-container-tindakan" class="divide-y divide-slate-100 font-medium text-slate-700"></tbody>
                        <tfoot class="bg-slate-50 font-extrabold text-slate-900 border-t border-slate-200">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right uppercase tracking-wider text-[10px] text-slate-500">Grand Total Estimasi Biaya Akhir:</td>
                                <td id="m-total-biaya" class="px-4 py-3 text-right text-sm text-emerald-600 font-black bg-emerald-50/30">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50 flex justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closeDetailTransaksiModal()"
                class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-4 py-2 rounded-xl text-xs transition cursor-pointer uppercase tracking-wider">
                Tutup Kembali
            </button>
            <button type="button" id="m-btn-print-trigger"
                class="bg-[#10b981] hover:bg-[#059669] text-white font-bold px-5 py-2 rounded-xl text-xs shadow-md shadow-emerald-900/10 transition cursor-pointer uppercase tracking-wider flex items-center gap-1.5">
                <i class="bi bi-printer-fill"></i>
                <span>Cetak Nota Fisik</span>
            </button>
        </div>

    </div>
</div>

<script>
    /**
     * Memetakan fungsi penutup modal cadangan agar kompatibel jika ada komponen eksternal 
     * lama yang memanggil nama fungsi pendek ini.
     */
    window.closeDetailModal = () => {
        if(typeof closeDetailTransaksiModal === 'function') {
            closeDetailTransaksiModal();
        } else {
            const modal = document.getElementById('modalDetailTransaksi');
            if(modal) modal.classList.add('hidden');
        }
    };
</script>