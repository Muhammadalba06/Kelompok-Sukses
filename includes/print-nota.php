<style>
@media print {
    @page {
        size: A4 portrait;
        margin: 0; /* Menghilangkan margin bawaan browser untuk menghapus teks di sudut halaman */
    }
    body {
        background-color: #fff;
        margin: 0;
        padding: 0;
        -webkit-print-color-adjust: exact; /* Memastikan warna Tailwind tetap muncul saat dicetak */
        print-color-adjust: exact;
    }
    .print-border-clean {
        border: 1px solid #000000 !important;
        border-radius: 0.75rem !important;
    }
}
</style>

<div id="print-section" class="hidden print:block print:absolute print:inset-0 bg-white text-black w-[210mm] min-h-[297mm] mx-auto p-[15mm] box-border font-mono text-xs leading-relaxed">
    <div class="border border-black p-6 rounded-xl box-border space-y-6 bg-white print-border-clean flex flex-col justify-between min-h-[267mm]">
        
        <div class="space-y-6">
            <div class="text-center space-y-1 pb-3 border-b-2 border-black">
                <h4 class="text-xl font-black tracking-wider uppercase">WARRIOR COMPUTER</h4>
                <p class="text-xs font-semibold text-slate-700">Service Laptop & Computer Jambi</p>
                <p class="text-[10px] font-mono text-slate-500">Jl. Muaro Jambi - Realtime Service Hub</p>
            </div>

            <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-xs pb-2">
                <div>
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">ID Nota / Tanda Terima</span>
                    <strong id="p-id" class="text-slate-900 tracking-wide text-sm">-</strong>
                </div>
                <div class="text-right">
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Tanggal Masuk</span>
                    <span id="p-tgl" class="font-medium text-slate-800 text-sm">-</span>
                </div>
                <div class="mt-1">
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Nama Pelanggan</span>
                    <strong id="p-nama" class="text-slate-900 uppercase text-sm">-</strong>
                </div>
                <div class="text-right mt-1">
                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">No. WhatsApp</span>
                    <span id="p-wa" class="font-medium text-slate-800 text-sm">-</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 bg-slate-50 p-4 rounded-lg border border-slate-200 text-xs">
                <div class="space-y-2">
                    <div>
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Unit Device</span>
                        <strong id="p-merk" class="text-slate-800 text-sm">-</strong>
                    </div>
                    <div class="border-t border-dashed border-slate-200 pt-2">
                        <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Kelengkapan</span>
                        <p id="p-kelengkapan" class="text-slate-700 font-medium font-sans text-sm mt-0.5">-</p>
                    </div>
                </div>
                <div class="flex flex-col justify-center border-l border-dashed border-slate-300 pl-6">
                    <span class="block text-[9px] font-extrabold text-slate-400 uppercase tracking-wider">Keluhan / Kerusakan</span>
                    <p id="p-kerusakan" class="text-red-600 font-bold font-sans text-sm mt-0.5">-</p>
                </div>
            </div>

            <div class="space-y-2 pt-2">
                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wide">Rincian Perbaikan & Jual Sparepart</span>
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="border-b-2 border-black text-[10px] font-bold text-slate-600">
                            <th class="py-2 text-left w-[60%]">Deskripsi Tindakan</th>
                            <th class="py-2 text-center w-[15%]">Garansi</th>
                            <th class="py-2 text-right w-[25%]">Harga</th>
                        </tr>
                    </thead>
                    <tbody id="p-container-tindakan" class="divide-y divide-slate-200">
                        </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-black font-black text-sm">
                            <td colspan="2" class="py-3 text-right text-slate-800 uppercase tracking-wider">Total Biaya Akhir:</td>
                            <td id="p-biaya" class="py-3 text-right text-emerald-700 whitespace-nowrap text-base">-</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="text-center pt-4 border-t border-dashed border-black space-y-1.5 text-[10px] text-slate-500 font-sans">
            <p class="font-bold text-slate-700 uppercase tracking-wider text-xs">Terima Kasih Atas Kepercayaan Anda</p>
            <p class="leading-relaxed px-4">Syarat Klaim Garansi: Wajib membawa nota fisik ini & segel toko Warrior Computer tidak rusak/robek.</p>
            <div class="pt-2 text-[8px] font-mono text-slate-400 tracking-tight">Powered by Warrior Central SPA Monitoring Core v2.0</div>
        </div>

    </div>
</div>