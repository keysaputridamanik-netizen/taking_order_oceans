# Taking Order Cafe

Sistem pemesanan cafe berbasis web — PHP Native + Tailwind CSS + Iconify.

## Fitur
- Pelanggan: input meja → pilih menu (dengan gambar) → detail produk → keranjang → checkout → struk (print)
- Admin: dashboard (+grafik 7 hari), kelola menu (CRUD + upload gambar), data order, laporan + cetak
- Upload gambar JPG/PNG/WEBP maks 3MB
- Design system navy/ocean/sand + responsif

## Persyaratan
- PHP 7.4+ (disarankan 8.x) dengan ekstensi mysqli, fileinfo
- MySQL / MariaDB

## Instalasi
1. Import `db_cafe.sql` ke MySQL/MariaDB.
2. Edit `config/koneksi.php` sesuai kredensial DB.
3. Buat folder `assets/uploads/menu/` dan pastikan writable (`chmod 755`).
4. Jalankan `php -S localhost:8000` dari root project, atau deploy ke Apache/Nginx.
5. Buka `http://localhost:8000/`.

## Login Admin
- URL: `/admin/login.php`
- Username: `admin`
- Password: `admin123` — WAJIB regenerate via `admin/generate_hash.php` (lalu hapus file).

## Struktur
- `/admin` — panel admin
- `/pelanggan` — halaman pelanggan
- `/proses` — handler backend
- `/config` — koneksi & helper
- `/assets` — css, uploads