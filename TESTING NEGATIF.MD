# 🔴 Laporan Pengujian - Negative Test Case
**Aplikasi:** Monitoring Service Computer (Warrior Computer)  
**Penguji:** Suci Jusna Dahlia  

---

| ID Test Case | Kategori | Skenario Pengujian | Test Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TCN-01** | NEGATIVE | Login dengan password salah | USR: `@teknisiPW`<br>PW: `salahpass` | Login gagal, pesan error kombinasi salah muncul | Login gagal dan menampilkan notifikasi error | **PASS** || ![TCN-01](path/to/gambar1.png) |
| **TCN-02** | NEGATIVE | Memasukkan teks ke input Biaya Tindakan | Teks: `user` diinput ke field biaya | Sistem menolak submit data, memicu validasi browser `type="number"` | Browser menampilkan popup validasi asli "Masukkan angka." | **PASS** |
| **TCN-03** | NEGATIVE | Memasukkan teks ke input Jumlah Uang Tunai | Teks: `coba` diinput ke field "Jumlah Uang Tunai Diterima" | Sistem menolak submit, memicu validasi sisi klien browser | Browser menampilkan popup validasi asli "Masukkan angka." dan tombol submit diblokir. | **PASS** |
| **TCN-04** | NEGATIVE | Input Modal Sparepart menggunakan huruf | Harga: `"Seratus Ribu"` | Sistem/form menolak pengisian karakter selain angka | Nilai ditolak dan kembali/ditahan di angka minimum 0 | **PASS** |
| **TCN-05** | NEGATIVE | Mendaftarkan username yang sudah terpakai | USR: `@front_admin` | Pendaftaran gagal, pesan error duplikat muncul | Notifikasi peringatan username sudah digunakan | **PASS** |