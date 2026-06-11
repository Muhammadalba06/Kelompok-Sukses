/**
 * Warrior Computer - Kasir & Verification System
 * File: assets/js/kasir.js
 * Deskripsi: Menangani rendering antrean kasir, kalkulasi uang kembalian,
 * dan sinkronisasi status pembayaran dengan halaman Laporan Transaksi.
 */

// --- 1. RENDER TABEL VERIFIKASI KASIR ---
function renderVerifikasiKasir() {
    const tbody = document.getElementById('tabelVerifikasiKasir');
    if (!tbody) return;
    tbody.innerHTML = '';

    // KUNCI RE-FILTER: Hanya menyaring unit yang status pengerjaannya sudah Selesai (Menunggu Pembayaran)
    // Setelah kasir melakukan pelunasan (Lunas), unit otomatis bersih dari tabel kasir ini.
    const antreanKasir = db.filter(item => {
        if (!item.status) return false;
        const cleanStatus = item.status.trim();
        // Hanya muncul jika statusnya 'Selesai' atau 'Selesai / Siap Ambil'
        return cleanStatus === 'Selesai' || cleanStatus === 'Selesai / Siap Ambil' || cleanStatus === 'Siap Ambil';
    });

    if (antreanKasir.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-5 text-center text-slate-400 italic text-xs">Semua transaksi lunas. Tidak ada laptop yang menunggu verifikasi kasir.</td></tr>`;
        return;
    }

    antreanKasir.forEach(item => {
        const nominalBiaya = parseInt(item.biaya) || 0;
        
        tbody.innerHTML += `
            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50/60 transition text-xs">
                <td class="px-4 py-3">
                    <div class="font-extrabold text-slate-900">${item.id}</div>
                    <div class="text-slate-500 mt-0.5" style="font-size:11px;">Pemilik: ${item.nama}</div>
                </td>
                <td class="px-4 py-3 font-semibold text-slate-800 uppercase">${item.merk}</td>
                <td class="px-4 py-3 text-blue-600 font-bold">${item.teknisi || '<span class="text-slate-400 italic">Belum diserahkan</span>'}</td>
                <td class="px-4 py-3 font-black text-slate-900 font-mono">Rp ${nominalBiaya.toLocaleString('id-ID')}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded bg-amber-50 text-amber-700 font-bold text-[10px] uppercase tracking-wider">SIAP AMBIL</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button onclick='bukaModalKasirMandiri(${JSON.stringify(item)})' class="bg-emerald-500 hover:bg-emerald-600 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition uppercase tracking-wider flex items-center justify-center gap-1 mx-auto cursor-pointer">
                        <i class="bi bi-cash-coin"></i>
                        <span>Proses Pelunasan</span>
                    </button>
                </td>
            </tr>
        `;
    });
}

// --- 2. MODAL KASIR MANDIRI CONTROLLER ---
window.bukaModalKasirMandiri = (item) => {
    // Simpan nominal tagihan saat ini ke variabel global (di dashboard.js)
    tagihanMandiriKasir = parseInt(item.biaya) || 0;
    
    // Pemetaan data unit ke elemen form di dalam modal kasir
    document.getElementById('km-id').value = item.id;
    document.getElementById('km-txt-id').innerText = item.id;
    document.getElementById('km-txt-nama').innerText = item.nama;
    document.getElementById('km-txt-merk').innerText = item.merk;
    document.getElementById('km-txt-tagihan').innerText = "Rp " + tagihanMandiriKasir.toLocaleString('id-ID');
    
    // Set field modal sparepart (biaya modal toko yang dikeluarkan)
    document.getElementById('km-modal').value = item.modal || 0;
    
    // Reset input bayar dan teks kembalian awal
    document.getElementById('km-bayar').value = '';
    document.getElementById('km-txt-kembalian').innerText = 'Rp 0';
    document.getElementById('km-txt-kembalian').className = "text-sm font-black text-slate-700";

    const modal = document.getElementById('modalKasirMandiri');
    if (modal) modal.classList.remove('hidden');
};

window.closeKasirMandiriModal = () => {
    const modal = document.getElementById('modalKasirMandiri');
    if (modal) modal.classList.add('hidden');
};

// --- 3. KALKULATOR KEMBALIAN REAL-TIME ---
window.hitungMundurKembalian = () => {
    const cash = parseInt(document.getElementById('km-bayar').value) || 0;
    const sisa = cash - tagihanMandiriKasir;
    const containerText = document.getElementById('km-txt-kembalian');

    if (!containerText) return;

    if (sisa >= 0) {
        containerText.innerText = "Rp " + sisa.toLocaleString('id-ID');
        containerText.className = "text-sm font-black text-emerald-700";
    } else {
        containerText.innerText = "Kurang Rp " + Math.abs(sisa).toLocaleString('id-ID');
        containerText.className = "text-sm font-black text-red-600";
    }
};

// --- 4. ENGINE PARSING & PRINT NOTA FISIK STRUK THERMAL ---
window.printNota = (item) => {
    document.getElementById('p-nama').innerText = item.nama;
    document.getElementById('p-wa').innerText = item.wa;
    document.getElementById('p-tgl').innerText = item.tgl;
    document.getElementById('p-id').innerText = item.id;
    document.getElementById('p-merk').innerText = item.merk;
    document.getElementById('p-kelengkapan').innerText = item.kelengkapan || '-';
    document.getElementById('p-kerusakan').innerText = item.kerusakan;
    document.getElementById('p-biaya').innerText = "Rp " + (parseInt(item.biaya) || 0).toLocaleString('id-ID');

    const pContainer = document.getElementById('p-container-tindakan');
    if (pContainer) {
        pContainer.innerHTML = '';
        const penangananRaw = item.penanganan || '';
        
        if (penangananRaw.includes('Jual:') || penangananRaw.includes('Modal:')) {
            const listTindakan = penangananRaw.split('), ');
            
            listTindakan.forEach(teks => {
                let cleanTeks = teks.trim();
                if (!cleanTeks.endsWith(')')) cleanTeks += ')';
                
                const namaTindakan = cleanTeks.split(' (Garansi:')[0];
                const garansiItem = cleanTeks.match(/Garansi:\s?([^-)]+)/)?.[1]?.trim() || '1 Bulan';
                const biayaItem = cleanTeks.match(/Jual:\s?Rp\s?([\d.,]+)/)?.[1]?.replace(/[.,]/g, '') || 0;
                
                pContainer.innerHTML += `
                    <tr>
                        <td class="border border-black p-1 font-medium text-left">${namaTindakan}</td>
                        <td class="border border-black p-1 text-center font-medium">${garansiItem}</td>
                        <td class="border border-black p-1 text-right font-bold font-mono">Rp ${parseInt(biayaItem).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        } else if (penangananRaw.includes('(Garansi:')) {
            const listTindakan = penangananRaw.split('), ');
            
            listTindakan.forEach(teks => {
                let cleanTeks = teks.trim();
                if (!cleanTeks.endsWith(')')) cleanTeks += ')';
                
                const namaTindakan = cleanTeks.split(' (Garansi:')[0];
                const garansiItem = cleanTeks.match(/Garansi:\s?([^-)]+)/)?.[1]?.trim() || '1 Bulan';
                const biayaItem = cleanTeks.match(/Rp\s?([\d.,]+)/)?.[1]?.replace(/[.,]/g, '') || 0;
                
                pContainer.innerHTML += `
                    <tr>
                        <td class="border border-black p-1 font-medium text-left">${namaTindakan}</td>
                        <td class="border border-black p-1 text-center font-medium">${garansiItem}</td>
                        <td class="border border-black p-1 text-right font-bold font-mono">Rp ${parseInt(biayaItem).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        } else {
            pContainer.innerHTML = `
                <tr>
                    <td class="border border-black p-1 font-medium text-left">${penangananRaw || '-'}</td>
                    <td class="border border-black p-1 text-center font-medium">1 Bulan</td>
                    <td class="border border-black p-1 text-right font-bold font-mono">Rp ${(parseInt(item.biaya) || 0).toLocaleString('id-ID')}</td>
                </tr>
            `;
        }
    }
    window.print();
};