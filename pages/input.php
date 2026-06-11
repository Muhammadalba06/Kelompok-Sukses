<?php
// pages/input.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Proteksi Halaman: Mencegah user mengakses langsung file ini tanpa melalui login index.php
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    exit('<div class="p-4 text-xs text-red-600 font-bold uppercase tracking-wider">Akses Ditolak! Anda tidak memiliki otoritas akses langsung.</div>');
}
?>
<div id="page-input" class="page-content hidden">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 lg:p-6">
        
        <div class="flex items-center gap-2 pb-5 border-b border-slate-100 mb-5">
            <div class="bg-emerald-500/10 text-emerald-600 w-8 h-8 rounded-xl flex items-center justify-center text-sm">
                <i class="bi bi-file-earmark-plus-fill"></i>
            </div>
            <div>
                <h5 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">Formulir Penerimaan Unit Servis</h5>
                <p class="text-[10px] text-slate-400 font-medium mt-0.5">Pencatatan spesifikasi, kelengkapan, dan rincian awal kerusakan laptop pelanggan.</p>
            </div>
        </div>

        <form id="formServis" class="space-y-5">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tanggal" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Tanggal Masuk</label>
                    <input type="date" id="tanggal" required
                        class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition">
                </div>
                <div>
                    <label for="merk" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Merek & Model Laptop</label>
                    <input type="text" id="merk" required
                        class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition"
                        placeholder="Contoh: Asus ROG Strix G15">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nama" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Nama Pelanggan</label>
                    <input type="text" id="nama" required
                        class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition"
                        placeholder="Nama Pelanggan">
                </div>
                <div>
                    <label for="wa" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Nomor WhatsApp</label>
                    <input type="text" id="wa" required
                        class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition"
                        placeholder="0851-XXXX-XXXX">
                </div>
            </div>

            <div>
                <label for="kelengkapan" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Kelengkapan</label>
                <input type="text" id="kelengkapan"
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition"
                    placeholder="Cth: Charger, Tas, Dus, dll.">
            </div>

            <div>
                <label for="kerusakan" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Deskripsi Kerusakan</label>
                <textarea id="kerusakan" rows="2" required
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] focus:ring-3 focus:ring-[#10b981]/10 outline-none transition resize-none"
                    placeholder="Gejala kerusakan unit..."></textarea>
            </div>

            <div class="border-t border-dashed border-slate-200 pt-4 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider block">Penanganan Sementara / Tindakan, Garansi & Biaya Jual</label>
                    <button type="button" id="btn-tambah-penanganan"
                        class="text-[#10b981] hover:text-[#059669] text-xs font-bold flex items-center gap-1.5 transition duration-150 cursor-pointer">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>TAMBAH TINDAKAN</span>
                    </button>
                </div>

                <div id="container-penanganan" class="space-y-2">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2 penanganan-row items-center">
                        <div class="md:col-span-6 w-full">
                            <input type="text" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] outline-none transition input-penanganan" 
                                placeholder="Contoh: Ganti LCD / Keyboard Rusak" required>
                        </div>
                        <div class="md:col-span-2 w-full">
                            <input type="text" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] outline-none transition input-garansi-item" 
                                list="garansi-item-presets" placeholder="Garansi item" value="1 Bulan" required>
                        </div>
                        
                        <input type="hidden" class="input-modal-item" value="0">

                        <div class="md:col-span-3 w-full">
                            <div class="flex">
                                <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 px-2 py-2 text-[10px] font-extrabold text-slate-400 rounded-l-lg flex items-center">J: Rp</span>
                                <input type="number" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 text-xs font-bold text-emerald-600 rounded-r-lg outline-none focus:bg-white focus:border-[#10b981] transition input-biaya-item" 
                                    placeholder="Jual" value="0" min="0" required>
                            </div>
                        </div>
                        <div class="md:col-span-1 w-full text-right">
                            <button type="button" class="w-full bg-red-50 text-red-500 rounded-lg py-2 font-bold hover:bg-red-500 hover:text-white transition disabled:opacity-40 disabled:cursor-not-allowed btn-hapus-penanganan" disabled>&times;</button>
                        </div>
                    </div>
                </div>
            </div>

            <datalist id="garansi-item-presets">
                <option value="Tanpa Garansi">
                <option value="1 Minggu">
                <option value="2 Minggu">
                <option value="1 Bulan">
                <option value="3 Bulan">
                <option value="6 Bulan">
            </datalist>

            <input type="hidden" id="modal" value="0">
            
            <div class="border-t border-slate-100 pt-4">
                <label for="biaya" class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1.5">Estimasi Total Biaya Jual (Rp)</label>
                <input type="number" id="biaya" value="0" required readonly
                    class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-xl text-xs font-black text-emerald-600 select-all cursor-not-allowed outline-none">
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit" 
                    class="w-full bg-[#10b981] hover:bg-[#059669] text-white font-bold py-2.5 px-4 rounded-xl text-xs tracking-wider shadow-md shadow-emerald-950/10 hover:shadow-lg transition duration-200 cursor-pointer uppercase flex items-center justify-center gap-2">
                    <i class="bi bi-printer-fill"></i>
                    <span>Simpan & Generate Nota</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    // Penutupan skrip terisolasi (IIFE Scope) agar mencegah polusi global data state
    const container = document.getElementById("container-penanganan");
    const btnTambah = document.getElementById("btn-tambah-penanganan");
    const inputTotalBiaya = document.getElementById("biaya");
    const inputTotalModal = document.getElementById("modal");

    if (!container || !btnTambah || !inputTotalBiaya || !inputTotalModal) return;

    // Fungsi otomatisasi hitung akumulasi bercabang (Dual Calculator Engine)
    function hitungAkumulasiFinansial() {
        // 1. Kalkulasi akumulasi biaya jual per item tindakan pengerjaan
        const semuaInputBiayaItem = container.querySelectorAll(".input-biaya-item");
        let totalBiaya = 0;
        semuaInputBiayaItem.forEach(input => {
            totalBiaya += parseInt(input.value) || 0;
        });
        inputTotalBiaya.value = totalBiaya;

        // 2. Kalkulasi akumulasi modal dasar pengadaan sparepart terpisah (Hidden Mode)
        const semuaInputModalItem = container.querySelectorAll(".input-modal-item");
        let totalModal = 0;
        semuaInputModalItem.forEach(input => {
            totalModal += parseInt(input.value) || 0;
        });
        inputTotalModal.value = totalModal;
    }

    // Delegasi masukan input dinamis (Event Delegation)
    container.addEventListener("input", function (e) {
        if (e.target.classList.contains("input-biaya-item") || e.target.classList.contains("input-modal-item")) {
            hitungAkumulasiFinansial();
        }
    });

    // Event penambahan baris tindakan baru
    btnTambah.addEventListener("click", function () {
        const newRow = document.createElement("div");
        newRow.className = "grid grid-cols-1 md:grid-cols-12 gap-2 penanganan-row items-center";
        newRow.innerHTML = `
            <div class="md:col-span-6 w-full">
                <input type="text" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] outline-none transition input-penanganan" placeholder="Nama Penanganan/Sparepart..." required>
            </div>
            <div class="md:col-span-2 w-full">
                <input type="text" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] outline-none transition input-garansi-item" list="garansi-item-presets" placeholder="Garansi" value="1 Bulan" required>
            </div>
            
            <input type="hidden" class="input-modal-item" value="0">

            <div class="md:col-span-3 w-full">
                <div class="flex">
                    <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 px-2.5 py-2 text-[10px] font-extrabold text-slate-400 rounded-l-lg flex items-center">J: Rp</span>
                    <input type="number" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 text-xs font-bold text-emerald-600 rounded-r-lg outline-none focus:bg-white focus:border-[#10b981] transition input-biaya-item" placeholder="Biaya" value="0" min="0" required>
                </div>
            </div>
            <div class="md:col-span-1 w-full text-right">
                <button type="button" class="w-full bg-red-50 text-red-500 rounded-lg py-2 font-bold hover:bg-red-500 hover:text-white transition btn-hapus-penanganan">&times;</button>
            </div>
        `;
        container.appendChild(newRow);
        toggleHapusButtonState();
        hitungAkumulasiFinansial();
    });

    // Event penghapusan baris tindakan pengerjaan
    container.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-hapus-penanganan") || e.target.closest(".btn-hapus-penanganan")) {
            const targetButton = e.target.classList.contains("btn-hapus-penanganan") ? e.target : e.target.closest(".btn-hapus-penanganan");
            const rows = container.querySelectorAll(".penanganan-row");
            if (rows.length > 1) {
                targetButton.closest(".penanganan-row").remove();
            }
            toggleHapusButtonState();
            hitungAkumulasiFinansial();
        }
    });

    // Kontrol tombol disabled ketika tersisa tunggal
    function toggleHapusButtonState() {
        const rows = container.querySelectorAll(".penanganan-row");
        rows.forEach(row => {
            const btnHapus = row.querySelector(".btn-hapus-penanganan");
            if (btnHapus) {
                if (rows.length === 1) {
                    btnHapus.setAttribute("disabled", "disabled");
                    btnHapus.classList.add("opacity-40", "cursor-not-allowed");
                } else {
                    btnHapus.removeAttribute("disabled");
                    btnHapus.classList.remove("opacity-40", "cursor-not-allowed");
                }
            }
        });
    }
})();
</script>