+------------------------------------+
|             SIMPUS-Mini            |
|------------------------------------|
|                                    |
|          [ Login Petugas ]         |
|                                    |
|   Username : [______________]      |
|   Password : [______________]      |
|                                    |
|           [   Masuk   ]            |
|                                    |
|  Belum punya akun? Daftar di sini  |
+------------------------------------+

+----------------------------------------------------+
|                    SIMPUS-Mini                     |
|----------------------------------------------------|
|                                                    |
|            [ Registrasi Anggota Baru ]             |
|                                                    |
|   Nama Lengkap   : [______________________]        |
|   NIK / No. ID   : [______________________]        |
|   Alamat         : [______________________]        |
|   No. Telepon    : [______________________]        |
|   Email          : [______________________]        |
|                                                    |
|      [ Daftar ]              [ Batal ]             |
|                                                    |
|      Sudah jadi anggota? Kembali ke Beranda        |
+----------------------------------------------------+

[Mulai]
   |
   v
Petugas login --> gagal? --(ya)--> tampilkan pesan error --> kembali ke login
   |(berhasil)
   v
Buka menu "Cari Anggota"
   |
   v
Pilih filter "Tunggakan lewat jatuh tempo"
   |
   v
Sistem membandingkan tanggal jatuh tempo dengan tanggal hari ini
   |
   v
Ada hasil? --(tidak)--> tampilkan "Tidak ada anggota menunggak" --> [Selesai]
   |(ya)
   v
Tampilkan daftar: nama, no. anggota, judul buku, hari terlambat, denda
   |
   v
Petugas memilih anggota --> lihat detail peminjaman
   |
   v
Petugas mengambil tindakan? --(tidak)--> [Selesai]
   |(ya)
   v
Catat pengembalian / catat denda / kirim pengingat
   |
   v
[Selesai]