/**
 * Warrior Computer - Dashboard Core System
 * File: assets/js/dashboard.js
 * Deskripsi: Mengatur state data global, navigasi halaman hibrida, 
 * kontrol hak akses UI, dan pengiriman form berbasis AJAX dengan proteksi penanganan JSON.
 * Fitur Tambahan: Automasi kalkulasi nominal biaya jual & modal sparepart per item tindakan,
 * serta pemisahan data otomatis (Unit Lunas langsung bersih dari tabel antrean kerja aktif).
 */

// --- 1. GLOBAL STATE MANAGEMENT ---
let db = [];
let tagihanMandiriKasir = 0;

// Mengambil data session langsung dari variabel global yang di-inject di layout/footer
let roleSistem = window.PHP_SESSION_ROLE || 'front_admin';
let statusLogin = window.PHP_IS_LOGGED_IN || false;

// --- 2. INITIALIZATION ON DOM READY ---
document.addEventListener("DOMContentLoaded", () => {
    // Terapkan UI berdasarkan Hak Akses Role
    applyRoleUI(roleSistem);
    
    // Set Tanggal Hari Ini di Header Dashboard
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const dateHeader = document.getElementById('current-date');
    if (dateHeader) {
        dateHeader.innerText = new Date().toLocaleDateString('id-ID', options);
    }
    
    // Set Default Tanggal di Form Input Data Servis
    const inputTanggal = document.getElementById('tanggal');
    if (inputTanggal) {
        inputTanggal.valueAsDate = new Date();
    }

    // Pengalihan Halaman Awal Otomatis Berdasarkan Role
    if (roleSistem === 'Nico' || roleSistem === 'Bahri' || roleSistem === 'Ono') {
        showPage('page-monitor');
    } else {
        showPage('page-input');
    }
    
    // Muat Pertama Kali Data dari Basis Data
    loadDatabase();

    // Inisialisasi Event Handlers untuk Form Elektronik
    initFormEventListeners();
});

// --- 3. DATABASE SYNC CONTROLLER (AJAX FETCH) ---
function loadDatabase() {
    fetch('api/proses.php?action=get_all&t=' + new Date().getTime())
        .then(response => {
            if (!response.ok) throw new Error('Koneksi API bermasalah.');
            return response.json();
        })
        .then(data => {
            db = data;
            // Panggil fungsi render dari modul modular lain jika fungsi tersebut eksis
            if (typeof renderMonitor === 'function') renderMonitor();
            if (typeof renderVerifikasiKasir === 'function') renderVerifikasiKasir(); 
            if (typeof renderKeuangan === 'function') renderKeuangan(); 
            if (typeof renderLaporan === 'function') renderLaporan(); // Real-time render arsip laporan jika terbuka
        })
        .catch(error => console.error('Gagal mengambil data dari API Server:', error));
}

// --- 4. ENGINE ROUTING & NAVIGASI INTERFACES ---
function showPage(pageId) {
    // Tutup sidebar jika dalam mode mobile view
    if (typeof closeSidebar === 'function') closeSidebar();

    // Proteksi Navigasi Client-Side: Front Admin dilarang bypass halaman keuangan
    if (roleSistem === 'front_admin' && pageId === 'page-keuangan') {
        showPage('page-input');
        return;
    }

    // Sembunyikan semua kontainer halaman
    document.querySelectorAll('.page-content').forEach(p => p.classList.add('hidden'));
    
    // Reset state aktif pada link navigasi sidebar
    document.querySelectorAll('.nav-link-custom, .btn-main-nav').forEach(l => {
        l.classList.remove('active', 'bg-white/10', 'text-white', 'border-l-4', 'border-[#10b981]');
    });
    
    // Tampilkan halaman target
    const targetPage = document.getElementById(pageId);
    if (targetPage) targetPage.classList.remove('hidden');

    // Transliterasi Judul Header Berdasarkan Halaman Aktif
    const pageTitles = {
        'page-input': 'Input Data Servis Baru',
        'page-monitor': 'Monitoring Proses & Pekerjaan',
        'page-verifikasi': 'Verifikasi Selesai & Kasir Pembayaran',
        'page-keuangan': 'Laporan Keuangan & Komisi',
        'page-laporan': 'Arsip Laporan Transaksi Selesai', 
        'page-pengguna': 'Manajemen Pengguna Sistem'
    };
    
    const txtPageTitle = document.getElementById('page-title');
    if (txtPageTitle) {
        txtPageTitle.innerText = pageTitles[pageId] || 'Dashboard';
    }

    // Set State Hack Aktif pada Tombol Menu Navigator yang Sesuai
    if (pageId === 'page-input') {
        const btnInput = document.getElementById('btn-input-nav');
        if (btnInput) btnInput.classList.add('active');
    } else {
        const btnId = 'btn-' + pageId.split('-')[1];
        const targetBtn = document.getElementById(btnId);
        if (targetBtn) targetBtn.classList.add('active');
    }

    // Trigger Re-render Data saat halaman berpindah
    if (pageId === 'page-monitor' && typeof renderMonitor === 'function') renderMonitor();
    if (pageId === 'page-verifikasi' && typeof renderVerifikasiKasir === 'function') renderVerifikasiKasir();
    if (pageId === 'page-keuangan' && typeof handleKeuanganAccess === 'function') handleKeuanganAccess();
    if (pageId === 'page-laporan' && typeof renderLaporan === 'function') renderLaporan(); 
    if (pageId === 'page-pengguna' && typeof loadUsers === 'function') loadUsers();
}

// --- 5. HAK AKSES DAN ROLE UI CONTROLLER ---
function applyRoleUI(role) {
    const btnInput = document.getElementById('btn-input-nav');
    const btnKeuangan = document.getElementById('btn-keuangan');
    const btnPengguna = document.getElementById('btn-pengguna'); 
    const btnVerifikasi = document.getElementById('btn-verifikasi');
    const btnLaporan = document.getElementById('btn-laporan'); 
    
    const displayNames = {
        direktur: 'Direktur (Mulyanto)',
        front_admin: 'Administrator',
        Nico: 'Teknisi: Nico',
        Bahri: 'Teknisi: Bahri',
        Ono: 'Teknisi: Ono'
    };
    
    const userDisplay = document.getElementById('current-user-display');
    if (userDisplay) userDisplay.innerText = displayNames[role] || role;
    
    // Manajemen Visibilitas Sidebar Menu Berdasarkan Role Pegawai
    if (role === 'Nico' || role === 'Bahri' || role === 'Ono') {
        if (btnInput) btnInput.classList.add('hidden'); 
        if (btnKeuangan) btnKeuangan.classList.remove('hidden');
        if (btnPengguna) btnPengguna.classList.add('hidden');
        if (btnVerifikasi) btnVerifikasi.classList.add('hidden'); 
        if (btnLaporan) btnLaporan.classList.add('hidden'); 
    } else if (role === 'front_admin') {
        if (btnInput) btnInput.classList.remove('hidden'); 
        if (btnKeuangan) btnKeuangan.classList.add('hidden'); 
        if (btnPengguna) btnPengguna.classList.remove('hidden');
        if (btnVerifikasi) btnVerifikasi.classList.remove('hidden');
        if (btnLaporan) btnLaporan.classList.remove('hidden'); 
    } else if (role === 'direktur') {
        if (btnInput) btnInput.classList.remove('hidden');
        if (btnKeuangan) btnKeuangan.classList.remove('hidden');
        if (btnPengguna) btnPengguna.classList.add('hidden');
        if (btnVerifikasi) btnVerifikasi.classList.remove('hidden');
        if (btnLaporan) btnLaporan.classList.remove('hidden'); 
    }
}

// --- 6. SIDEBAR RESPONSIVE UTILITIES ---
window.toggleSidebar = () => {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar) sidebar.classList.toggle('-translate-x-full');
    if (overlay) overlay.classList.toggle('hidden');
};

window.closeSidebar = () => {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
    }
};

// --- 7. CORE REALTIME UPDATE ENGINE DENGAN PROTEKSI PARSING DIAGNOSTIK ---
window.updateData = (id, key, val) => {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('id', id);
    formData.append('key', key);
    formData.append('val', val);

    fetch('api/proses.php', { method: 'POST', body: formData })
        .then(res => {
            if (!res.ok) throw new Error('Respon server gagal.');
            return res.text(); 
        })
        .then(text => {
            try {
                const res = JSON.parse(text);
                if (res.success) { 
                    loadDatabase(); 
                } else { 
                    alert('Gagal memperbarui data: ' + res.error); 
                }
            } catch (err) {
                console.error('=== IDENTIFIKASI RESPOND BACKEND BUKAN JSON ===');
                console.error('Teks kotor yang diterima:', text);
                alert('Sistem mendeteksi text hantu pembocor JSON dari file PHP. Silakan tekan F12 -> cek tab Console untuk melihat teks pengganggu tersebut.');
            }
        })
        .catch(err => console.error('Error saat melakukan pembaruan data:', err));
};

// --- 8. SYSTEM AUTOMATION: ENGINE KALKULASI TOTAL BIAYA JUAL & MODAL SEKETIKA ---
function hitungTotalBiaya() {
    const container = document.getElementById("container-penanganan");
    const inputTotalBiaya = document.getElementById("biaya");
    const inputTotalModal = document.getElementById("modal");
    if (!container) return;

    // 1. Akumulasi Biaya Jual (Rupiah Jual)
    const semuaInputBiayaItem = container.querySelectorAll(".input-biaya-item");
    let totalBiaya = 0;
    semuaInputBiayaItem.forEach(input => {
        totalBiaya += parseInt(input.value) || 0;
    });
    if (inputTotalBiaya) inputTotalBiaya.value = totalBiaya;

    // 2. Akumulasi Biaya Modal Dasar (Rupiah Suku Cadang)
    const semuaInputModalItem = container.querySelectorAll(".input-modal-item");
    let totalModal = 0;
    semuaInputModalItem.forEach(input => {
        totalModal += parseInt(input.value) || 0;
    });
    if (inputTotalModal) inputTotalModal.value = totalModal;
}

// --- 9. SUBMIT HANDLERS (AJAX FORM CONTROLLERS) ---
function initFormEventListeners() {
    const containerPenanganan = document.getElementById("container-penanganan");

    // Pasang listener input dinamis untuk deteksi ganda (Modal & Jual) secara instant
    if (containerPenanganan) {
        containerPenanganan.addEventListener("input", function (e) {
            if (e.target.classList.contains("input-biaya-item") || e.target.classList.contains("input-modal-item")) {
                hitungTotalBiaya();
            }
        });
        
        // Pasang listener klik dinamis untuk mengkalkulasi ulang biaya saat ada rincian tindakan dihapus
        containerPenanganan.addEventListener("click", function (e) {
            if (e.target.classList.contains("btn-hapus-penanganan") || e.target.closest(".btn-hapus-penanganan")) {
                setTimeout(hitungTotalBiaya, 50);
            }
        });
    }

    // A. Form Input Penerimaan Unit Servis Baru
    const formServis = document.getElementById('formServis');
    if (formServis) {
        formServis.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const tanggalInput = document.getElementById('tanggal').value; 
            const rowsPenanganan = document.querySelectorAll('.penanganan-row');
            let arrayPenanganan = [];
            
            rowsPenanganan.forEach(row => {
                const teksTindakan = row.querySelector('.input-penanganan').value.trim();
                const hargaTindakan = parseInt(row.querySelector('.input-biaya-item').value) || 0;
                const modalTindakan = parseInt(row.querySelector('.input-modal-item').value) || 0;
                const garansiTindakan = row.querySelector('.input-garansi-item').value.trim();
                
                if (teksTindakan !== '') {
                    arrayPenanganan.push(`${teksTindakan} (Garansi: ${garansiTindakan} - Modal: Rp ${modalTindakan.toLocaleString('id-ID')} - Jual: Rp ${hargaTindakan.toLocaleString('id-ID')})`);
                }
            });
            
            const penangananString = arrayPenanganan.join(', ');

            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('tgl', tanggalInput);
            formData.append('nama', document.getElementById('nama').value);
            formData.append('wa', document.getElementById('wa').value);
            formData.append('merk', document.getElementById('merk').value);
            formData.append('kelengkapan', document.getElementById('kelengkapan').value);
            formData.append('kerusakan', document.getElementById('kerusakan').value);
            formData.append('penanganan', penangananString);
            formData.append('garansi', 'Mengikuti Item');
            formData.append('biaya', document.getElementById('biaya').value);
            formData.append('modal', document.getElementById('modal').value);

            fetch('api/proses.php', { method: 'POST', body: formData })
                .then(res => {
                    if (!res.ok) throw new Error('Respon server gagal');
                    return res.json();
                })
                .then(res => {
                    if (res.success) {
                        alert('Data servis berhasil disimpan ke database!');
                        formServis.reset();
                        
                        if (containerPenanganan) {
                            containerPenanganan.innerHTML = `
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-2 penanganan-row items-center">
                                    <div class="md:col-span-4 w-full">
                                        <input type="text" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-2.5 py-2 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] outline-none transition input-penanganan" placeholder="Contoh: Ganti LCD / Keyboard Rusak" required>
                                    </div>
                                    <div class="md:col-span-2 w-full">
                                        <input type="text" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 rounded-lg text-xs font-medium text-slate-900 focus:bg-white focus:border-[#10b981] outline-none transition input-garansi-item" list="garansi-item-presets" placeholder="Garansi item" value="1 Bulan" required>
                                    </div>
                                    <div class="md:col-span-3 w-full">
                                        <div class="flex">
                                            <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 px-2 py-2 text-[10px] font-extrabold text-slate-400 rounded-l-lg flex items-center">M: Rp</span>
                                            <input type="number" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 text-xs font-bold text-red-600 rounded-r-lg outline-none focus:bg-white focus:border-red-500 transition input-modal-item" placeholder="Modal" value="0" min="0" required>
                                        </div>
                                    </div>
                                    <div class="md:col-span-2 w-full">
                                        <div class="flex">
                                            <span class="bg-slate-100 border border-[#e2e8f0] border-r-0 px-2.5 py-2 text-[10px] font-extrabold text-slate-400 rounded-l-lg flex items-center">J: Rp</span>
                                            <input type="number" class="w-full bg-[#f8fafc] border border-[#e2e8f0] px-3 py-2 text-xs font-bold text-emerald-600 rounded-r-lg outline-none focus:bg-white focus:border-[#10b981] transition input-biaya-item" placeholder="Jual" value="0" min="0" required>
                                        </div>
                                    </div>
                                    <div class="md:col-span-1 w-full text-right">
                                        <button type="button" class="w-full bg-red-50 text-red-500 rounded-lg py-2 font-bold hover:bg-red-500 hover:text-white transition disabled:opacity-40 disabled:cursor-not-allowed btn-hapus-penanganan" disabled>&times;</button>
                                    </div>
                                </div>
                            `;
                        }
                        if (typeof toggleHapusButtonState === 'function') toggleHapusButtonState();
                        document.getElementById('tanggal').valueAsDate = new Date();
                        loadDatabase(); 
                        showPage('page-monitor');
                    } else {
                        alert('Gagal menyimpan data ke MySQL: ' + res.error);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan sistem saat menghubungi file api/proses.php');
                });
        });
    }

    // B. Form Proses Pelunasan Kasir Pembayaran
    const formKasirMandiri = document.getElementById('formKasirMandiri');
    if (formKasirMandiri) {
        formKasirMandiri.addEventListener('submit', function(e) {
            e.preventDefault();
            const idNota = document.getElementById('km-id').value;
            const modalPart = document.getElementById('km-modal').value;
            const cash = parseInt(document.getElementById('km-bayar').value) || 0;

            if (cash < tagihanMandiriKasir) {
                alert('Peringatan! Uang tunai yang dibayarkan kurang dari nominal tagihan.');
                return;
            }

            const reqStatus = new FormData();
            reqStatus.append('action', 'update'); 
            reqStatus.append('id', idNota); 
            reqStatus.append('key', 'status'); 
            reqStatus.append('val', 'Lunas');

            const reqModal = new FormData();
            reqModal.append('action', 'update'); 
            reqModal.append('id', idNota); 
            reqModal.append('key', 'modal'); 
            reqModal.append('val', modalPart);

            Promise.all([
                fetch('api/proses.php', { method: 'POST', body: reqStatus }).then(res => {
                    return res.text().then(text => {
                        try { return JSON.parse(text); } 
                        catch(e) { console.error('Error reqStatus text:', text); return {success: false}; }
                    });
                }),
                fetch('api/proses.php', { method: 'POST', body: reqModal }).then(res => {
                    return res.text().then(text => {
                        try { return JSON.parse(text); } 
                        catch(e) { console.error('Error reqModal text:', text); return {success: false}; }
                    });
                })
            ])
            .then(results => {
                if (results.every(r => r.success)) {
                    alert('Verifikasi Pelunasan Sukses! Unit laptop lunas dan siap dilepas ke pelanggan.');
                    if (typeof closeKasirMandiriModal === 'function') {
                        closeKasirMandiriModal();
                    } else {
                        const modal = document.getElementById('modalKasirMandiri');
                        if (modal) modal.classList.add('hidden');
                    }
                    loadDatabase(); // Memperbarui global state DB, memicu penghapusan otomatis dari antrean aktif
                    showPage('page-laporan'); // Alihkan view secara instan ke lembar arsip laporan
                } else {
                    alert('Terjadi kesalahan format/penulisan data kasir ke database server.');
                }
            })
            .catch(err => console.error('Gagal memproses transaksi kasir:', err));
        });
    }

    // C. Form Manajemen Registrasi / Edit Data Pengguna Sistem
    const formPengguna = document.getElementById('formPengguna');
    if (formPengguna) {
        formPengguna.addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('user-id-input').value;
            
            const formData = new FormData();
            formData.append('action', id ? 'update_user' : 'add_user');
            if (id) formData.append('id', id);
            formData.append('nama', document.getElementById('user-nama').value);
            formData.append('username', document.getElementById('user-username').value);
            formData.append('role', document.getElementById('user-role').value);
            formData.append('password', document.getElementById('user-pass').value);

            fetch('api/proses.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        alert('Data Pengguna Berhasil Disimpan!');
                        if (typeof resetFormUser === 'function') resetFormUser();
                        if (typeof loadUsers === 'function') loadUsers();
                    } else {
                        alert('Gagal menyimpan pengguna: ' + res.error);
                    }
                })
                .catch(err => console.error('Gagal memproses data pengguna:', err));
        });
    }
}

// --- 10. MANAJEMEN DATA PENGGUNA (AJAX RENDER & CRUD) ---
function loadUsers() {
    const tbody = document.getElementById('tabelUser');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400 italic text-xs">Memuat data pengguna dari server...</td></tr>`;

    fetch('api/proses.php?action=get_users&t=' + new Date().getTime())
        .then(res => {
            if (!res.ok) throw new Error('Respon server gagal.');
            return res.json();
        })
        .then(data => {
            tbody.innerHTML = ''; 

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-slate-400 italic text-xs">Tidak ada data pengguna terdaftar.</td></tr>`;
                return;
            }

            data.forEach(u => {
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50 transition text-slate-600 border-b border-slate-100">
                        <td class="px-4 py-3">
                            <div class="text-xs font-bold text-slate-800">${u.nama}</div>
                            <div class="text-[10px] text-emerald-600 font-bold uppercase mt-0.5">${u.role}</div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-600 font-medium">@${u.username}</td>
                        <td class="px-4 py-3 text-xs font-mono text-slate-400">${u.password}</td>
                        <td class="px-4 py-3 text-center space-x-2 whitespace-nowrap">
                            <button type="button" onclick='editUser(${JSON.stringify(u)})' class="text-blue-500 hover:text-blue-700 p-1 transition cursor-pointer" title="Edit User">
                                <i class="bi bi-pencil-square text-sm"></i>
                            </button>
                            <button type="button" onclick="hapusUser(${u.id})" class="text-red-500 hover:text-red-700 p-1 transition cursor-pointer" title="Hapus User">
                                <i class="bi bi-trash text-sm"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(err => {
            console.error("Gagal memuat pengguna:", err);
            tbody.innerHTML = `<tr><td colspan="4" class="p-4 text-center text-red-500 text-xs font-medium">Gagal memuat data dari database.</td></tr>`;
        });
}

window.editUser = (u) => {
    const idInput = document.getElementById('user-id-input');
    if (idInput) {
        idInput.value = u.id;
        document.getElementById('user-nama').value = u.nama;
        document.getElementById('user-username').value = u.username;
        document.getElementById('user-role').value = u.role;
        document.getElementById('user-pass').value = u.password;
    }
};

window.resetFormUser = () => {
    const form = document.getElementById('formPengguna');
    if (form) form.reset();
    const idInput = document.getElementById('user-id-input');
    if (idInput) idInput.value = '';
};

window.hapusUser = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini secara permanen dari sistem?')) {
        const formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('id', id);
        
        fetch('api/proses.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if (res.success) { 
                    loadUsers(); 
                } else { 
                    alert('Gagal menghapus pengguna: ' + res.error); 
                }
            })
            .catch(err => console.error(err));
    }
};

// --- 11. MODAL TRANSACTION UTILITIES (WITH IN-LINE PART COST EDITING) ---
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
    const btnUpdateModal = document.getElementById('btn-update-modal-part');
    const btnPrintTrigger = document.getElementById('m-btn-print-trigger'); 
    
    if (!containerTindakan) return;
    
    containerTindakan.innerHTML = '';
    const penangananRaw = item.penanganan || '';

    if (penangananRaw.includes('Modal:') || penangananRaw.includes('Jual:')) {
        const listTindakan = penangananRaw.split('), ');

        listTindakan.forEach((teks, index) => {
            let cleanTeks = teks.trim();
            if (!cleanTeks.endsWith(')')) cleanTeks += ')';

            const namaTindakan = cleanTeks.split(' (Garansi:')[0];
            const garansiItem = cleanTeks.match(/Garansi:\s?([^-)]+)/)?.[1]?.trim() || '1 Bulan';
            
            const matchModal = cleanTeks.match(/Modal:\s?Rp\s?([\d.,]+)/);
            const matchJual = cleanTeks.match(/Jual:\s?Rp\s?([\d.,]+)/);

            const nilaiModal = matchModal ? parseInt(matchModal[1].replace(/[.,]/g, '')) : 0;
            const nilaiJual = matchJual ? parseInt(matchJual[1].replace(/[.,]/g, '')) : 0;

            containerTindakan.innerHTML += `
                <tr class="hover:bg-slate-50/50 transition row-edit-part" data-nama="${namaTindakan}" data-garansi="${garansiItem}" data-jual="${nilaiJual}">
                    <td class="p-2.5 text-center font-bold text-slate-400">${index + 1}</td>
                    <td class="p-2.5 font-bold text-slate-800">
                        <div>${namaTindakan}</div>
                        <div class="text-[10px] text-slate-400 font-medium mt-0.5">Garansi: ${garansiItem}</div>
                    </td>
                    <td class="p-2.5">
                        <div class="flex items-center">
                            <span class="bg-slate-100 border border-slate-200 border-r-0 px-2 py-1 text-[10px] font-bold text-slate-400 rounded-l-lg">Rp</span>
                            <input type="number" class="w-full bg-slate-50 border border-slate-200 px-2 py-1 text-xs font-bold text-red-600 rounded-r-lg outline-none focus:bg-white focus:border-red-500 transition input-edit-modal-item" value="${nilaiModal}" min="0">
                        </div>
                    </td>
                    <td class="p-2.5 text-right font-bold text-slate-900 bg-slate-50/40">Rp ${nilaiJual.toLocaleString('id-ID')}</td>
                </tr>
            `;
        });

        if (btnUpdateModal && (roleSistem === 'front_admin' || roleSistem === 'direktur' || roleSistem === item.teknisi)) {
            btnUpdateModal.classList.remove('hidden');
            btnUpdateModal.onclick = () => { simpanPerubahanModalPerItem(item.id); };
        }

    } else {
        containerTindakan.innerHTML = `
            <tr>
                <td class="p-2.5 text-center font-bold text-slate-400">1</td>
                <td class="p-2.5 font-bold text-slate-800">${penangananRaw || '-'}</td>
                <td class="p-2.5 text-slate-400 italic">Data format lama</td>
                <td class="p-2.5 text-right font-bold text-slate-900">Rp ${(parseInt(item.biaya) || 0).toLocaleString('id-ID')}</td>
            </tr>
        `;
        if (btnUpdateModal) btnUpdateModal.classList.add('hidden');
    }

    if (btnPrintTrigger) {
        btnPrintTrigger.onclick = () => { 
            window.printNota(item); 
        };
    }

    const modal = document.getElementById('modalDetailTransaksi');
    if (modal) modal.classList.remove('hidden');
};

function simpanPerubahanModalPerItem(idServis) {
    const rows = document.querySelectorAll(".row-edit-part");
    if (rows.length === 0) return;

    let arrayPenangananBaru = [];
    let totalModalBaru = 0;

    rows.forEach(row => {
        const nama = row.getAttribute("data-nama");
        const garansi = row.getAttribute("data-garansi");
        const jual = parseInt(row.getAttribute("data-jual")) || 0;
        const modalBaruItem = parseInt(row.querySelector(".input-edit-modal-item").value) || 0;

        totalModalBaru += modalBaruItem;
        arrayPenangananBaru.push(`${nama} (Garansi: ${garansi} - Modal: Rp ${modalBaruItem.toLocaleString('id-ID')} - Jual: Rp ${jual.toLocaleString('id-ID')})`);
    });

    const penangananStringBaru = arrayPenangananBaru.join(", ");

    const formDataPenanganan = new FormData();
    formDataPenanganan.append("action", "update_penanganan_modal");
    formDataPenanganan.append("id", idServis);
    formDataPenanganan.append("penanganan", penangananStringBaru);
    formDataPenanganan.append("modal_total", totalModalBaru);

    fetch("api/proses.php", { method: "POST", body: formDataPenanganan })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                alert("Sukses! Perubahan nominal komponen modal sparepart berhasil disimpan.");
                const modal = document.getElementById('modalDetailTransaksi');
                if (modal) modal.classList.add('hidden');
                loadDatabase();
            } else {
                alert("Gagal memperbarui modal: " + res.error);
            }
        })
        .catch(err => console.error("Koneksi API bermasalah:", err));
}

// --- 12. ENGINE RENDER PRINT NOTA STRUK KASIR (FIX TERPISAH) ---
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
        
        if (penangananRaw.includes('Jual:')) {
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
                        <td class="border border-black p-1 text-right font-bold">Rp ${parseInt(biayaItem).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
        } else {
            pContainer.innerHTML = `
                <tr>
                    <td class="border border-black p-1 font-medium text-left">${penangananRaw || '-'}</td>
                    <td class="border border-black p-1 text-center font-medium">Mengikuti Item</td>
                    <td class="border border-black p-1 text-right font-bold">Rp ${(parseInt(item.biaya) || 0).toLocaleString('id-ID')}</td>
                </tr>
            `;
        }
    }
    window.print();
};

// --- 13. ENGINE REPORTING ARCHIVE SYSTEM (LAPORAN TRANSAKSI SELESAI) ---
function renderLaporan() {
    const tbody = document.getElementById("tabelArsipLaporan");
    if (!tbody) return;

    const dataLulusKasir = db.filter(item => item.status === 'Selesai' || item.status === 'Lunas');
    tbody.innerHTML = '';

    if (dataLulusKasir.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="p-5 text-center italic text-slate-400 text-xs">Belum ada riwayat arsip transaksi lunas di dalam database.</td></tr>`;
        return;
    }

    dataLulusKasir.forEach(item => {
        tbody.innerHTML += `
            <tr class="hover:bg-slate-50 transition border-b border-slate-100 row-data-laporan">
                <td class="px-4 py-3">
                    <div class="text-xs font-bold text-slate-800 uppercase target-search-merk">${item.merk}</div>
                    <div class="text-[10px] text-slate-500 mt-0.5 target-search-nama">${item.nama} • <span class="font-mono text-slate-400 font-bold">${item.id}</span></div>
                </td>
                <td class="px-4 py-3 text-center text-xs font-bold text-blue-600">${item.teknisi || '-'}</td>
                <td class="px-4 py-3 text-right font-semibold text-red-600 font-mono">Rp ${(parseInt(item.modal) || 0).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-right font-black text-emerald-600 font-mono">Rp ${(parseInt(item.biaya) || 0).toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-black uppercase tracking-wider">LUNAS / SELESAI</span>
                </td>
                <td class="px-4 py-3 text-center">
                    <button type="button" onclick='bukaDetailTransaksi(${JSON.stringify(item)})' class="text-slate-400 hover:text-slate-900 transition p-1 cursor-pointer" title="Lihat Rekam Medis / Cetak Nota">
                        <i class="bi bi-eye-fill text-sm"></i>
                    </button>
                </td>
            </tr>
        `;
    });
}

window.searchTabelLaporan = () => {
    const input = document.getElementById("search-laporan").value.toLowerCase();
    const rows = document.querySelectorAll(".row-data-laporan");

    rows.forEach(row => {
        const merk = row.querySelector(".target-search-merk").innerText.toLowerCase();
        const nama = row.querySelector(".target-search-nama").innerText.toLowerCase();
        
        if (merk.includes(input) || nama.includes(input)) {
            row.classList.remove("hidden");
        } else {
            row.classList.add("hidden");
        }
    });
};