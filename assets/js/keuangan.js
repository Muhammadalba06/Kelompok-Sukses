/**
 * Warrior Computer - Financial & Commission System
 * File: assets/js/keuangan.js
 * Deskripsi: Menghitung laba kotor, modal sparepart, laba bersih, 
 * komisi 35% untuk teknisi, serta akumulasi rekapitulasi bulanan berdasarkan status Lunas.
 */

// --- 1. CORE FINANCIAL RENDERER ---
function renderKeuangan() {
    const tbody = document.getElementById('tabelKeuangan');
    if (!tbody) return;
    tbody.innerHTML = '';
    
    let totalGross = 0, totalModal = 0, totalKomisi = 0;
    
    // PERBAIKAN LOGIKA: Keuangan kini menyaring unit yang transaksinya sudah berstatus 'Lunas'
    // (Telah melewati validasi pembayaran cash di kasir mandiri)
    const dataSelesai = db.filter(item => item.status && item.status.trim() === 'Lunas');
    
    // FILTER DATA BERDASARKAN ROLE LOGIN:
    // Direktur bisa melihat semua data selesai, sedangkan Teknisi hanya bisa melihat datanya sendiri.
    const visibleData = roleSistem === 'direktur' ? dataSelesai : dataSelesai.filter(item => item.teknisi === roleSistem);

    if (visibleData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-slate-400 italic text-xs">Belum ada unit dengan status 'Lunas' untuk hak akses role Anda.</td></tr>`;
        
        // Reset ringkasan kartu keuangan ke Rp 0
        if (document.getElementById('tot-kotor')) document.getElementById('tot-kotor').innerText = "Rp 0";
        if (document.getElementById('tot-modal')) document.getElementById('tot-modal').innerText = "Rp 0";
        if (document.getElementById('tot-komisi')) document.getElementById('tot-komisi').innerText = "Rp 0";
        
        renderRekapBulanan([]);
        return;
    }

    visibleData.forEach(item => {
        const biayaAkhir = parseInt(item.biaya) || 0;
        const modalPart = parseInt(item.modal) || 0;
        const labaBersih = biayaAkhir - modalPart;
        
        // Aturan Bisnis: Komisi 35% hanya berlaku untuk teknisi (Nico, Bahri, Ono) jika laba bersih bernilai positif (> 0)
        const isTeknisiMitra = (item.teknisi === 'Nico' || item.teknisi === 'Bahri' || item.teknisi === 'Ono');
        const komisi = isTeknisiMitra && labaBersih > 0 ? labaBersih * 0.35 : 0;

        totalGross += biayaAkhir; 
        totalModal += modalPart; 
        totalKomisi += komisi;

        tbody.innerHTML += `
            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50/70 transition text-xs font-medium text-slate-600">
                <td class="px-4 py-3.5">
                    <div class="font-bold text-slate-900">${item.merk}</div>
                    <div class="text-slate-500 mt-0.5" style="font-size: 11px;">ID: ${item.id} • Pemilik: ${item.nama}</div>
                </td>
                <td class="px-4 py-3.5 font-semibold text-slate-500">${item.teknisi || 'Belum dipilih'}</td>
                <td class="px-4 py-3.5 font-bold text-slate-800">Rp ${biayaAkhir.toLocaleString('id-ID')}</td>
                <td class="px-4 py-3.5 text-red-600">Rp ${modalPart.toLocaleString('id-ID')}</td>
                <td class="px-4 py-3.5 font-bold text-slate-900">Rp ${labaBersih.toLocaleString('id-ID')}</td>
                <td class="px-4 py-3.5 font-black text-emerald-700 bg-emerald-50/40">Rp ${Math.floor(komisi).toLocaleString('id-ID')}</td>
            </tr>
        `;
    });

    // Perbarui Tampilan Angka di Kartu Ringkasan (Top Cards Dashboard)
    if (document.getElementById('tot-kotor')) document.getElementById('tot-kotor').innerText = `Rp ${totalGross.toLocaleString('id-ID')}`;
    if (document.getElementById('tot-modal')) document.getElementById('tot-modal').innerText = `Rp ${totalModal.toLocaleString('id-ID')}`;
    if (document.getElementById('tot-komisi')) document.getElementById('tot-komisi').innerText = `Rp ${Math.floor(totalKomisi).toLocaleString('id-ID')}`;

    // Jalankan fungsi kompilasi rekap bulanan
    renderRekapBulanan(dataSelesai);
}

// --- 2. MONTHLY RECAPITULATION BUNDLER ---
function renderRekapBulanan(dataSelesai) {
    const tbody = document.getElementById('tabelRekapBulanan');
    if (!tbody) return;
    tbody.innerHTML = '';

    const monthlyData = {};

    dataSelesai.forEach(item => {
        const tglMentah = item.tgl_selesai || item.tgl;
        if (!tglMentah) return;
        
        let sTgl = "";
        // Normalisasi format tanggal dd/mm/yyyy menjadi yyyy-mm-dd agar bisa dibaca valid oleh Date Object
        if (tglMentah.includes('/')) {
            const p = tglMentah.split('/');
            sTgl = `${p[2]}-${p[1]}-${p[0]}`; 
        } else {
            sTgl = tglMentah;
        }

        const dObj = new Date(sTgl);
        if (isNaN(dObj.getTime())) return; 

        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        const keyBulanTahun = `${monthNames[dObj.getMonth()]} ${dObj.getFullYear()}`;

        // Inisialisasi kerangka data group per bulan
        if (!monthlyData[keyBulanTahun]) { 
            monthlyData[keyBulanTahun] = { Nico: 0, Bahri: 0, Ono: 0, Total: 0 }; 
        }

        const biayaAkhir = parseInt(item.biaya) || 0;
        const modalPart = parseInt(item.modal) || 0;
        const labaBersih = biayaAkhir - modalPart;
        const komisi = labaBersih > 0 ? labaBersih * 0.35 : 0;

        // Akumulasikan komisi ke masing-masing teknisi yang bersangkutan
        if (item.teknisi === 'Nico') { monthlyData[keyBulanTahun].Nico += komisi; monthlyData[keyBulanTahun].Total += komisi; }
        if (item.teknisi === 'Bahri') { monthlyData[keyBulanTahun].Bahri += komisi; monthlyData[keyBulanTahun].Total += komisi; }
        if (item.teknisi === 'Ono') { monthlyData[keyBulanTahun].Ono += komisi; monthlyData[keyBulanTahun].Total += komisi; }
    });

    const keys = Object.keys(monthlyData);
    if (keys.length === 0) {
        tbody.innerHTML = `<tr><td colspan="5" class="p-4 text-center text-slate-400 italic text-xs">Tidak ada rekapan bulanan.</td></tr>`;
        return;
    }

    keys.forEach(bulan => {
        const data = monthlyData[bulan];
        
        // PENGUNCIAN DATA ANTAR PEGAWAI:
        // Teknisi dilarang mengintip atau melihat pendapatan komisi bulanan milik rekan teknisi lainnya.
        const valNico = (roleSistem === 'direktur' || roleSistem === 'Nico') ? `Rp ${Math.floor(data.Nico).toLocaleString('id-ID')}` : '🔐 Terkunci';
        const valBahri = (roleSistem === 'direktur' || roleSistem === 'Bahri') ? `Rp ${Math.floor(data.Bahri).toLocaleString('id-ID')}` : '🔐 Terkunci';
        const valOno = (roleSistem === 'direktur' || roleSistem === 'Ono') ? `Rp ${Math.floor(data.Ono).toLocaleString('id-ID')}` : '🔐 Terkunci';
        
        // Total komisi gabungan seluruh teknisi dalam satu bulan hanya boleh diakses oleh Direktur Utama
        const totalKomisiBulanan = (roleSistem === 'direktur') ? `Rp ${Math.floor(data.Total).toLocaleString('id-ID')}` : '🔐 Khusus Direktur';

        tbody.innerHTML += `
            <tr class="border-b border-[#e2e8f0] text-xs font-semibold text-slate-600">
                <td class="px-4 py-3.5 font-bold text-slate-800">${bulan}</td>
                <td class="px-4 py-3.5 text-blue-600 font-bold">${valNico}</td>
                <td class="px-4 py-3.5 text-blue-600 font-bold">${valBahri}</td>
                <td class="px-4 py-3.5 text-blue-600 font-bold">${valOno}</td>
                <td class="px-4 py-3.5 bg-emerald-50 text-emerald-700 font-black">${totalKomisiBulanan}</td>
            </tr>
        `;
    });
}

// --- 3. PRIVILEGES ACCESS BOUNDARY CONTROLLER ---
function handleKeuanganAccess() {
    const lockedState = document.getElementById('locked-state');
    const unlockedState = document.getElementById('unlocked-state');
    const accessBadge = document.getElementById('access-badge');

    // Pengaman Berlapis Front-Admin: Jika admin depan iseng memicu menu keuangan lewat console log browser
    if (!statusLogin || roleSistem === 'front_admin') {
        if (lockedState) lockedState.classList.remove('hidden');
        if (unlockedState) unlockedState.classList.add('hidden');
        return;
    }

    if (lockedState) lockedState.classList.add('hidden');
    if (unlockedState) unlockedState.classList.remove('hidden');

    // Mengubah penamaan label info box keuangan secara dinamis berdasarkan role yang masuk
    if (accessBadge) {
        if (roleSistem === 'direktur') {
            accessBadge.innerText = 'Direktur View';
            accessBadge.className = 'inline-block bg-emerald-100 text-emerald-700 uppercase tracking-wider font-extrabold text-[9px] px-2.5 py-1.5 rounded';
            if (document.getElementById('label-komisi-title')) {
                document.getElementById('label-komisi-title').innerText = 'KOMISI KESELURUHAN TEKNISI (35%)';
            }
        } else {
            accessBadge.innerText = `Akses Teknisi: ${roleSistem}`;
            accessBadge.className = 'inline-block bg-blue-100 text-blue-700 uppercase tracking-wider font-extrabold text-[9px] px-2.5 py-1.5 rounded';
            if (document.getElementById('label-komisi-title')) {
                document.getElementById('label-komisi-title').innerText = `Komisi Anda (${roleSistem}) - 35%`;
            }
        }
    }
    
    // Jalankan kalkulasi utama
    renderKeuangan();
}