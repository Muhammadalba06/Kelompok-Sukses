/**
 * Warrior Computer - Service Monitoring System
 * File: assets/js/monitor.js
 * Deskripsi: Mengelola visualisasi antrean pengerjaan laptop,
 * alokasi unit ke teknisi, kontrol mutasi status, dan hapus data.
 */

// --- 1. RENDER TABEL MONITORING UTAMA ---
window.renderMonitor = () => {
    const tbody = document.getElementById('tabelMonitor');
    if (!tbody) return;
    tbody.innerHTML = '';

    // Auto-Archiving: Hanya tampilkan status yang belum selesai/lunas
    const dataAktif = db.filter(item => {
        if (!item.status) return true;
        const s = item.status.trim().toLowerCase();
        return s !== 'selesai' && s !== 'lunas';
    });

    if (dataAktif.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-slate-400 italic text-xs">Tidak ada unit dalam antrean atau tugas pengerjaan.</td></tr>`;
        return;
    }

    // IZINKAN SEMUA TEKNISI UNTUK EDIT: 
    // Mengubah canEdit menjadi true agar teknisi bisa mengubah status, teknisi, dan biaya
    const canEdit = true; 

    dataAktif.forEach((item) => {
        const currentStatus = item.status ? item.status.trim() : 'Cek';

        tbody.innerHTML += `
            <tr class="border-b border-[#e2e8f0] hover:bg-slate-50/70 transition">
                <td class="px-4 py-3.5 text-xs">
                    <div class="font-bold text-slate-900 uppercase">${item.merk}</div>
                    <div class="text-slate-500 mt-0.5" style="font-size: 11px;">${item.nama} • <span class="font-medium text-slate-700 font-mono">${item.id}</span></div>
                </td>
                
                <td class="px-4 py-3.5 text-xs">
                    <select onchange="updateData('${item.id}', 'status', this.value)" 
                            class="bg-[#f8fafc] border border-[#e2e8f0] px-2 py-1.5 rounded-md text-[11.5px] font-medium outline-none transition w-auto focus:border-[#10b981]" 
                            ${canEdit ? '' : 'disabled'}>
                        <option value="Cek" ${currentStatus === 'Cek' ? 'selected' : ''}>Cek / Antrean</option>
                        <option value="Diagnosa Kerusakan" ${currentStatus === 'Diagnosa Kerusakan' ? 'selected' : ''}>Diagnosa Kerusakan</option>
                        <option value="Menunggu Sparepart" ${currentStatus === 'Menunggu Sparepart' ? 'selected' : ''}>Menunggu Sparepart</option>
                        <option value="Pengerjaan" ${currentStatus === 'Pengerjaan' ? 'selected' : ''}>Pengerjaan</option>
                        <option value="Selesai / Siap Ambil" ${currentStatus === 'Selesai / Siap Ambil' || currentStatus === 'Siap Ambil' ? 'selected' : ''}>Selesai / Siap Ambil</option>
                    </select>
                </td>
                
                <td class="px-4 py-3.5 text-xs">
                    <select onchange="updateData('${item.id}', 'teknisi', this.value)" 
                            class="bg-[#f8fafc] border border-[#e2e8f0] px-2 py-1.5 rounded-md text-[11.5px] font-medium outline-none transition w-auto focus:border-[#10b981]" 
                            ${canEdit ? '' : 'disabled'}>
                        <option value="" ${item.teknisi === '' ? 'selected' : ''}>Pilih Teknisi</option>
                        <option value="Nico" ${item.teknisi === 'Nico' ? 'selected' : ''}>Nico</option>
                        <option value="Bahri" ${item.teknisi === 'Bahri' ? 'selected' : ''}>Bahri</option>
                        <option value="Ono" ${item.teknisi === 'Ono' ? 'selected' : ''}>Ono</option>
                        <option value="Mulyanto" ${item.teknisi === 'Mulyanto' ? 'selected' : ''}>Mulyanto</option>
                    </select>
                </td>
                
                <td class="px-4 py-3.5 text-xs">
                    <div class="flex max-w-[140px]">
                        <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 px-2 py-1 text-slate-500 rounded-l-md flex items-center">Rp</span>
                        <input type="number" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-2 py-1 rounded-r-md outline-none text-xs font-semibold text-red-600 focus:bg-white focus:border-[#10b981]" 
                        value="${item.modal || 0}" onchange="updateData('${item.id}', 'modal', this.value)">
                    </div>
                </td>
                
                <td class="px-4 py-3.5 text-xs">
                    <div class="flex max-w-[140px]">
                        <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 px-2 py-1 text-slate-500 rounded-l-md flex items-center">Rp</span>
                        <input type="number" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-2 py-1 rounded-r-md outline-none font-bold text-emerald-600 focus:bg-white focus:border-[#10b981]" 
                        value="${item.biaya || 0}" onchange="updateData('${item.id}', 'biaya', this.value)">
                    </div>
                </td>
                
                <td class="px-4 py-3.5 text-center text-xs space-x-1 whitespace-nowrap">
                    <button onclick='bukaDetailTransaksi(${JSON.stringify(item)})' class="p-1.5 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-md transition cursor-pointer" title="Lihat Detail Transaksi">
                        <i class="bi bi-eye-fill"></i>
                    </button>
                    
                    <button onclick="hapus('${item.id}')" class="p-1.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-md transition cursor-pointer" title="Hapus Data">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </td>
            </tr>
        `;
    });
};

// --- 2. MODAL VIEW DETAIL TRANSAKSI SERVIS ---
window.bukaDetailTransaksi = (item) => {
    document.getElementById('m-id').innerText = item.id;
    document.getElementById('m-tgl').innerText = item.tgl;
    document.getElementById('m-nama').innerText = item.nama;
    document.getElementById('m-wa').innerText = item.wa;
    document.getElementById('m-merk').innerText = item.merk;
    document.getElementById('m-kelengkapan').innerText = item.kelengkapan || '-';
    document.getElementById('m-status').innerText = item.status;
    document.getElementById('m-teknisi').innerText = item.teknisi || 'Belum dipilih';
    document.getElementById('m-kerusakan').innerText = item.kerusakan;
    document.getElementById('m-total-biaya').innerText = "Rp " + (parseInt(item.biaya) || 0).toLocaleString('id-ID');

    const containerTindakan = document.getElementById('m-container-tindakan');
    if (containerTindakan) {
        containerTindakan.innerHTML = '';
        const penangananRaw = item.penanganan || '';
        const listTindakan = penangananRaw.split('), ');

        if (penangananRaw.trim() === '' || listTindakan.length === 0) {
            containerTindakan.innerHTML = `<tr><td colspan="4" class="px-4 py-3 text-center text-slate-400 italic">Tidak ada rincian tindakan awal tercatat.</td></tr>`;
        } else {
            listTindakan.forEach((teks, index) => {
                let cleanTeks = teks.trim();
                if (!cleanTeks.endsWith(')')) cleanTeks += ')';
                
                const namaTindakan = cleanTeks.split(' (Garansi:')[0];
                const garansiItem = cleanTeks.match(/Garansi:\s?([^-)]+)/)?.[1]?.trim() || '1 Bulan';
                const matchJual = cleanTeks.match(/Jual:\s?Rp\s?([\d.,]+)/);
                
                let biayaItem = 0;
                if (matchJual) {
                    biayaItem = parseInt(matchJual[1].replace(/[.,]/g, ''));
                } else {
                    const matchBiayaLama = cleanTeks.match(/Rp\s?([\d.,]+)/);
                    biayaItem = matchBiayaLama ? parseInt(matchBiayaLama[1].replace(/[.,]/g, '')) : 0;
                }

                containerTindakan.innerHTML += `
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-4 py-2.5 text-center font-bold text-slate-400">${index + 1}</td>
                        <td class="px-4 py-2.5 font-bold text-slate-800 text-left">${namaTindakan}</td>
                        <td class="px-4 py-2.5 text-center font-semibold text-slate-500">${garansiItem}</td>
                        <td class="px-4 py-2.5 text-right font-bold text-slate-900">Rp ${biayaItem.toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        }
    }

    const btnPrintTrigger = document.getElementById('m-btn-print-trigger');
    if (btnPrintTrigger) {
        btnPrintTrigger.onclick = () => { 
            if (typeof window.printNota === 'function') {
                window.printNota(item); 
            } else {
                alert('Modul print-nota.php tidak terdeteksi.');
            }
        };
    }

    const modal = document.getElementById('modalDetailTransaksi');
    if (modal) modal.classList.remove('hidden');
};

window.closeDetailTransaksiModal = () => {
    const modal = document.getElementById('modalDetailTransaksi');
    if (modal) modal.classList.add('hidden');
};

// --- 3. PROSES HAPUS TRANSAKSI ---
window.hapus = (id) => {
    // Validasi konfirmasi ganda demi keamanan integritas data toko
    if (confirm(`Apakah Anda yakin ingin menghapus data servis dengan ID: ${id}?\nData yang terhapus tidak dapat dikembalikan lagi.`)) {
        const formData = new FormData();
        formData.append('action', 'delete'); 
        formData.append('id', id);

        fetch('api/proses.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    alert('Data servis berhasil dihapus dari antrean.');
                    if (typeof loadDatabase === 'function') {
                        loadDatabase(); // Memuat ulang database lokal secara real-time
                    } else {
                        location.reload(); // Fallback jika tidak ada fungsi trigger refresh parsial
                    }
                } else {
                    alert('Gagal menghapus data: ' + res.error);
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan jaringan atau sistem database.');
            });
    }
};