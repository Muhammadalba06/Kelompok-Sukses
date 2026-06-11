<?php
/**
 * Warrior Computer - Modal Detail Transaksi Component
 * File: includes/modal-detail.php
 * Deskripsi: Menyediakan template modal pop-up interaktif untuk melihat 
 * rincian lengkap nota servis, riwayat kelengkapan, rincian tindakan,
 * serta fitur pembaruan modal suku cadang/sparepart secara langsung.
 */
?>

<div id="modalDetailTransaksi" class="hidden fixed inset-0 z-[1060] overflow-y-auto print:hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeDetailTransaksiModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-[#e2e8f0]">
            
            <div class="bg-[#0f172a] px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i class="bi bi-file-earmark-text text-emerald-400 text-lg"></i>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 leading-tight">Rincian Dokumen</h3>
                        <span id="m-id" class="text-sm font-extrabold text-white tracking-wide">-</span>
                    </div>
                </div>
                <button type="button" class="text-slate-400 hover:text-white transition text-2xl font-bold cursor-pointer leading-none p-1" onclick="closeDetailTransaksiModal()">&times;</button>
            </div>

            <div class="bg-white px-5 py-5 space-y-4 max-h-[calc(100vh-210px)] overflow-y-auto custom-scroll">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-[#f8fafc] border border-[#e2e8f0] p-3 rounded-xl space-y-2">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-[#e2e8f0] pb-1">Data Pelanggan</div>
                        <div class="text-xs"><span class="font-semibold text-slate-500 inline-block w-20">Nama</span>: <strong id="m-nama" class="text-slate-900">-</strong></div>
                        <div class="text-xs"><span class="font-semibold text-slate-500 inline-block w-20">No. WhatsApp</span>: <span id="m-wa" class="text-slate-800 font-medium">-</span></div>
                        <div class="text-xs"><span class="font-semibold text-slate-500 inline-block w-20">Tgl. Masuk</span>: <span id="m-tgl" class="text-slate-800 font-medium">-</span></div>
                    </div>

                    <div class="bg-[#f8fafc] border border-[#e2e8f0] p-3 rounded-xl space-y-2">
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-[#e2e8f0] pb-1">Status Penugasan</div>
                        <div class="text-xs"><span class="font-semibold text-slate-500 inline-block w-20">Unit Laptop</span>: <strong id="m-merk" class="text-slate-900">-</strong></div>
                        <div class="text-xs"><span class="font-semibold text-slate-500 inline-block w-20">Teknisi</span>: <span id="m-teknisi" class="text-blue-600 font-bold">-</span></div>
                        <div class="text-xs">
                            <span class="font-semibold text-slate-500 inline-block w-20">Status Kerja</span>: 
                            <span id="m-status" class="inline-block px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-bold text-[10px] border border-amber-200 uppercase tracking-wide">-</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2 bg-slate-50 border border-slate-200 rounded-xl p-3.5 space-y-2.5">
                    <div class="text-xs">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kelengkapan Unit (Bawaan)</span>
                        <p id="m-kelengkapan" class="text-slate-800 font-medium text-xs leading-relaxed">-</p>
                    </div>
                    <div class="border-t border-dashed border-slate-200 pt-2">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Deskripsi Keluhan / Kerusakan</span>
                        <p id="m-kerusakan" class="text-red-600 font-bold text-xs leading-relaxed bg-red-50/50 p-2 rounded-lg border border-red-100/60">-</p>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rincian Komponen Suku Cadang & Jual Jasa</label>
                    <div class="border border-[#e2e8f0] rounded-xl overflow-hidden shadow-xs">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100 text-[10px] font-bold text-slate-500 border-b border-[#e2e8f0] uppercase tracking-wider">
                                    <th class="px-3 py-2 text-center w-[8%]">No</th>
                                    <th class="px-3 py-2 w-[47%]">Item Tindakan / Sparepart</th>
                                    <th class="px-3 py-2 w-[25%]">Modal Suku Cadang</th>
                                    <th class="px-3 py-2 text-right w-[20%]">Harga Jual</th>
                                </tr>
                            </thead>
                            <tbody id="m-container-tindakan" class="divide-y divide-[#e2e8f0] bg-white text-xs">
                                </tbody>
                            <tfoot>
                                <tr class="bg-emerald-50/40 font-bold border-t border-[#e2e8f0]">
                                    <td colspan="3" class="px-4 py-3 text-right text-slate-700 text-xs uppercase tracking-wider">Total Estimasi Biaya Jual Pelanggan:</td>
                                    <td id="m-total-biaya" class="px-4 py-3 text-right text-sm font-black text-emerald-600 whitespace-nowrap">-</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>

            <div class="bg-slate-50 px-5 py-3.5 flex flex-col sm:flex-row-reverse gap-2 border-t border-[#e2e8f0]">
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto justify-end">
                    <button id="btn-update-modal-part" type="button" class="hidden w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition uppercase tracking-wider cursor-pointer">
                        <i class="bi bi-save-fill"></i>
                        <span>Simpan Perubahan Modal</span>
                    </button>

                    <button id="m-btn-print-trigger" type="button" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-slate-800 transition uppercase tracking-wider cursor-pointer">
                        <i class="bi bi-printer-fill"></i>
                        <span>Cetak Nota Fisik</span>
                    </button>
                </div>
                
                <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-xs font-bold text-slate-700 border border-slate-200 shadow-xs hover:bg-slate-50 transition uppercase tracking-wider cursor-pointer" onclick="closeDetailTransaksiModal()">
                    Kembali
                </button>
            </div>

        </div>
    </div>
</div>

<script>
// Fungsi global helper shortcut penutup modal detail
window.closeDetailTransaksiModal = function() {
    const modal = document.getElementById('modalDetailTransaksi');
    if (modal) modal.classList.add('hidden');
};
</script>