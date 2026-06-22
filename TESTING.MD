# 🟡 Laporan Pengujian - Edge Case Test Case
**Aplikasi:** Monitoring Service Computer (Warrior Computer)  
**Penguji:** Alba

---

| ID Test Case | Kategori | Skenario Pengujian | Test Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TCE-01** | EDGE CASE | Input nama dengan spasi berlebih | Nama: `   Jamaludin   ` | Sistem menerima proses simpan dan menormalisasi spasi | Nama tersimpan rapi sebagai "Jamaludin" di tabel | **PASS** |
| **TCE-02** | EDGE CASE | Mencetak nota unit masuk tanpa rincian tindakan | Cetak unit yang status penangannya masih NULL | Nota tetap berhasil dicetak tanpa memicu error PHP | Area tindakan pada nota tampil kosong atau bergaris strip | **PASS** |
| **TCE-03** | EDGE CASE | Menguji Logika Kekurangan Pembayaran di Kasir | Total: Rp 100.000<br>Input Tunai: Rp 10.000 | Label Kekurangan muncul merah "Kurang Rp 90.000". Tombol "Sahkan" dinonaktifkan. | Label kurang muncul merah, tombol Sahkan terkunci. | **PASS** |
| **TCE-04** | EDGE CASE | Mengembalikan status progres laptop mundur | Mengubah status dari Pengerjaan kembali ke Cek / Antrean | Sistem berhasil mengubah kembali status tersebut di database tanpa mengunci alur kerja. | Status berhasil diturunkan kembali dan warna badge menyesuaikan secara otomatis. | **PASS** |
| **TCE-05** | EDGE CASE | Menguji respon auto-filter saat pelunasan | Ubah status menjadi Lunas / Selesai | Data langsung difilter keluar dari view antrean aktif | Baris data hilang secara otomatis tanpa memuat ulang halaman | **PASS** |
# 🔴 Laporan Pengujian - Negative Test Case
**Aplikasi:** Monitoring Service Computer (Warrior Computer)  
**Penguji:** Suci Jusna Dahlia  

---

| ID Test Case | Kategori | Skenario Pengujian | Te`st Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TCN-01** | NEGATIVE | Login dengan password salah | USR: `@teknisiPW`<br>PW: `salahpass` | Login gagal, pesan error kombinasi salah muncul | Login gagal dan menampilkan notifikasi error | **PASS** |
| **TCN-02** | NEGATIVE | Memasukkan teks ke input Biaya Tindakan | Teks: `user` diinput ke field biaya | Sistem menolak submit data, memicu validasi browser `type="number"` | Browser menampilkan popup validasi asli "Masukkan angka." | **PASS** |
| **TCN-03** | NEGATIVE | Memasukkan teks ke input Jumlah Uang Tunai | Teks: `coba` diinput ke field "Jumlah Uang Tunai Diterima" | Sistem menolak submit, memicu validasi sisi klien browser | Browser menampilkan popup validasi asli "Masukkan angka." dan tombol submit diblokir. | **PASS** |
| **TCN-04** | NEGATIVE | Input Modal Sparepart menggunakan huruf | Harga: `"Seratus Ribu"` | Sistem/form menolak pengisian karakter selain angka | Nilai ditolak dan kembali/ditahan di angka minimum 0 | **PASS** |
| **TCN-05** | NEGATIVE | Mendaftarkan username yang sudah terpakai | USR: `@front_admin` | Pendaftaran gagal, pesan error duplikat muncul | Notifikasi peringatan username sudah digunakan | **PASS** |