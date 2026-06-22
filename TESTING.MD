# 🟡 Laporan Pengujian - Edge Case Test Case
**Aplikasi:** Monitoring Service Computer (Warrior Computer)  
**Penguji:** Rasyid  

---

| ID Test Case | Kategori | Skenario Pengujian | Test Data | Expected Result | Actual Result | Status |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **TCE-01** | EDGE CASE | Input nama dengan spasi berlebih | Nama: `   Jamaludin   ` | Sistem menerima proses simpan dan menormalisasi spasi | Nama tersimpan rapi sebagai "Jamaludin" di tabel | **PASS** |
| **TCE-02** | EDGE CASE | Mencetak nota unit masuk tanpa rincian tindakan | Cetak unit yang status penangannya masih NULL | Nota tetap berhasil dicetak tanpa memicu error PHP | Area tindakan pada nota tampil kosong atau bergaris strip | **PASS** |
| **TCE-03** | EDGE CASE | Menguji Logika Kekurangan Pembayaran di Kasir | Total: Rp 100.000<br>Input Tunai: Rp 10.000 | Label Kekurangan muncul merah "Kurang Rp 90.000". Tombol "Sahkan" dinonaktifkan. | Label kurang muncul merah, tombol Sahkan terkunci. | **PASS** |
| **TCE-04** | EDGE CASE | Mengembalikan status progres laptop mundur | Mengubah status dari Pengerjaan kembali ke Cek / Antrean | Sistem berhasil mengubah kembali status tersebut di database tanpa mengunci alur kerja. | Status berhasil diturunkan kembali dan warna badge menyesuaikan secara otomatis. | **PASS** |
| **TCE-05** | EDGE CASE | Menguji respon auto-filter saat pelunasan | Ubah status menjadi Lunas / Selesai | Data langsung difilter keluar dari view antrean aktif | Baris data hilang secara otomatis tanpa memuat ulang halaman | **PASS** |