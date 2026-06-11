<?php
// pages/pengguna.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Memastikan file ini hanya diakses melalui index.php yang sah
if (!isset($_SESSION['is_logged_in'])) {
    exit('Akses ditolak.');
}
?>
<div id="page-pengguna" class="page-content hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Form Pengguna</h3>
                <form id="formPengguna" class="space-y-4">
                    <input type="hidden" id="user-id-input">
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Nama Lengkap</label>
                        <input type="text" id="user-nama" class="w-full bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-xs outline-none focus:border-emerald-500 transition text-slate-900" placeholder="Contoh: Nico Ardiansyah" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Username</label>
                        <input type="text" id="user-username" class="w-full bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-xs outline-none focus:border-emerald-500 transition text-slate-900" placeholder="Username untuk login" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Role / Jabatan</label>
                        <select id="user-role" class="w-full bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-xs outline-none focus:border-emerald-500 transition text-slate-900 font-bold" required>
                            <option value="front_admin">front_admin (Admin Depan)</option>
                            <option value="Nico">Nico (Teknisi)</option>
                            <option value="Bahri">Bahri (Teknisi)</option>
                            <option value="Ono">Ono (Teknisi)</option>
                            <option value="direktur">direktur (Pemilik)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Password / PIN</label>
                        <input type="text" id="user-pass" class="w-full bg-slate-50 border border-slate-200 px-3 py-2 rounded-lg text-xs outline-none focus:border-emerald-500 transition text-slate-900" placeholder="Minimal 4 karakter" required>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-grow bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2 rounded-lg text-xs transition cursor-pointer uppercase tracking-wider">SIMPAN USER</button>
                        <button type="button" onclick="resetFormUser()" class="bg-slate-100 text-slate-600 px-3 py-2 rounded-lg text-xs font-bold cursor-pointer uppercase tracking-wider">RESET</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Nama & Role</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Username</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Password</th>
                                <th class="px-4 py-3 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tabelUser" class="divide-y divide-slate-100">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>