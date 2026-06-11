<?php
/**
 * Warrior Computer - Modal Kasir Mandiri Component
 * File: includes/modal-kasir.php
 * Deskripsi: Menyediakan antarmuka modal pelunasan pembayaran kasir,
 * kalkulator uang kembalian instan, dan pencatatan modal sparepart akhir.
 */
?>

<div id="modalKasirMandiri" class="hidden fixed inset-0 z-[1050] overflow-y-auto print:hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeKasirMandiriModal()"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <form id="formKasirMandiri" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-[#e2e8f0]">
            
            <input type="hidden" id="km-id">

            <div class="bg-emerald-600 px-5 py-4 flex items-center justify-between text-white">
                <div class="flex items-center gap-2">
                    <i class="bi bi-cash-coin text-xl"></i>
                    <div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-emerald-200 leading-tight">Pelunasan Transaksi</h3>
                        <span id="km-txt-id" class="text-sm font-extrabold tracking-wide text-white">-</span>
                    </div>
                </div>
                <button type="button" class="text-emerald-200 hover:text-white transition text-2xl font-bold cursor-pointer leading-none p-1" onclick="closeKasirMandiriModal()">&times;</button>
            </div>

            <div class="bg-white px-5 py-5 space-y-4">
                
                <div class="bg-slate-50 border border-slate-200 p-3 rounded-xl text-xs space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-slate-500 font-medium">Nama Pelanggan:</span>
                        <strong id="km-txt-nama" class="text-slate-900">-</strong>
                    </div>
                    <div class="flex justify-between border-t border-slate-200/60 pt-1.5">
                        <span class="text-slate-500 font-medium">Unit Laptop:</span>
                        <span id="km-txt-merk" class="text-slate-800 font-bold">-</span>
                    </div>
                </div>

                <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                    <span class="block text-[10px] font-bold text-emerald-700 uppercase tracking-widest mb-0.5">TOTAL NOMINAL TAGIHAN</span>
                    <div id="km-txt-tagihan" class="text-2xl font-black text-emerald-600 tracking-tight">Rp 0</div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Modal Sparepart Suku Cadang (Rp)
                    </label>
                    <div class="flex rounded-lg shadow-xs">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-semibold">Rp</span>
                        <input type="number" id="km-modal" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-900 bg-[#f8fafc] focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition" placeholder="0" min="0" required>
                    </div>
                    <p class="text-[10px] text-slate-400 font-medium leading-tight">
                        *Isi nominal modal riil toko untuk kalkulasi bagi hasil komisi 35% teknisi.
                    </p>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Jumlah Uang Tunai / Cash (Rp)
                    </label>
                    <div class="flex rounded-lg shadow-xs">
                        <span class="inline-flex items-center px-3 rounded-l-xl border border-r-0 border-slate-200 bg-slate-50 text-slate-500 text-xs font-semibold">Rp</span>
                        <input type="number" id="km-bayar" oninput="hitungMundurKembalian()" class="block w-full min-w-0 flex-1 rounded-none rounded-r-xl border border-slate-200 px-3 py-2 text-sm font-black text-slate-900 bg-[#f8fafc] focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none transition" placeholder="Masukkan nominal pembayaran..." min="0" required>
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-dashed border-slate-200 pt-3 px-1">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Uang Kembalian :</span>
                    <div id="km-txt-kembalian" class="text-sm font-black text-slate-700">Rp 0</div>
                </div>

            </div>

            <div class="bg-slate-50 px-5 py-3.5 flex flex-col sm:flex-row-reverse gap-2 border-t border-[#e2e8f0]">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-black text-white shadow-xs hover:bg-emerald-700 transition uppercase tracking-wider cursor-pointer">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Konfirmasi Lunas</span>
                </button>
                <button type="button" class="w-full sm:w-auto inline-flex items-center justify-center rounded-xl bg-white px-4 py-2 text-xs font-bold text-slate-700 border border-slate-200 shadow-xs hover:bg-slate-50 transition uppercase tracking-wider cursor-pointer" onclick="closeKasirMandiriModal()">
                    Batalkan
                </button>
            </div>

        </form>
    </div>
</div>