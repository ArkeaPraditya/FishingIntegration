CARA MENJALANKAN

1. Letakkan folder pangkalan_fhising di htdocs (XAMPP), misalnya:
   C:\xampp\htdocs\pangkalan_fhising

2. Pastikan Apache dan MySQL menyala.

3. Buka phpMyAdmin, lalu import file database.sql.

4. Periksa database.php:
   host = 127.0.0.1
   db   = pangkalan_fhising
   user = root
   pass = '' (default XAMPP; ubah jika MySQL kamu memakai password)

5. Buka:
   http://localhost/pangkalan_fhising/

ALUR:
- Belum login -> tombol Reservasi menuju login.php.
- Login akun biasa -> kembali ke reservasi.php.
- Buat akun -> password disimpan dengan password_hash().
- Akun biasa -> avatar otomatis memakai huruf pertama nama.
- Login Google -> nama dan foto Google disimpan ke session/database.
- Sudah login -> navbar menampilkan nama + foto/avatar.
- Logout -> session dihapus.

GOOGLE LOGIN:
- Buat OAuth Client ID tipe Web application di Google Cloud Console.
- Daftarkan origin, misalnya http://localhost.
- Ganti GANTI_DENGAN_GOOGLE_CLIENT_ID di login.php dan google-login.php.
- Untuk produksi, gunakan verifikasi ID token dengan library Google API resmi; contoh ini memakai endpoint tokeninfo agar mudah dipelajari.

CATATAN:
- Reservasi pada contoh ini baru berupa halaman yang hanya bisa dibuka setelah login.
- Penyimpanan detail reservasi ke database adalah tahap berikutnya.
