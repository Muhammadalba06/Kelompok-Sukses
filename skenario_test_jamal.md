## 1. Pengujian Fitur Input Data Service (Admin)
* *Deskripsi:* Memastikan admin dapat memasukkan data laptop pelanggan yang masuk untuk diservis.
* *Langkah-langkah:*
  1. Masuk ke halaman login aplikasi.
  2. Input username dan password admin, klik Login.
  3. Buka halaman "Tambah Data Service".
  4. Isi data pelanggan (Nama, No HP, Keluhan Laptop, Merek Laptop).
  5. Klik tombol "Simpan Data".
* *Hasil yang Diharapkan:* Data berhasil tersimpan ke database (warrior_computer.sql) dan status monitoring awal berubah menjadi "Antrean/Pending".

## 2. Pengujian Fitur Update Status Monitoring
* *Deskripsi:* Memastikan teknisi/admin bisa mengubah status pengerjaan laptop.
* *Langkah-langkah:*
  1. Buka halaman utama Dashboard / Monitor.
  2. Cari data pelanggan yang tadi dimasukkan.
  3. Klik "Aksi" -> "Update Status".
  4. Ubah status dari "Pending" menjadi "Sedang Diperbaiki".
  5. Klik "Simpan".
* *Hasil yang Diharapkan:* Status pada tabel monitor langsung terupdate secara real-time menjadi "Sedang Diperbaiki".