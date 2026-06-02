# BurgerGOOD

Website pemesanan makanan berbasis web untuk restoran burger, dibuat sebagai proyek UAS mata kuliah Pemrograman Berbasis Web (PBW).

---

## Daftar Anggota Kelompok

Muhamad Rafy Ramadhan | [@RafyR27](https://github.com/RafyR27) |
Viergie SP | [@ViergieSP](https://github.com/ViergieSP) |
Therta Rizky Syaputra | [@Therta-Rizky-Syaputra](https://github.com/Therta-Rizky-Syaputra) |

---

## Deskripsi dan Tujuan Website

**BurgerGOOD** adalah aplikasi web pemesanan makanan untuk restoran burger yang memungkinkan pelanggan memesan langsung dari meja mereka menggunakan kode outlet dan nomor meja. Website ini mendukung multi-outlet, artinya satu sistem dapat digunakan oleh beberapa cabang restoran secara bersamaan.

**Tujuan website ini adalah:**

- Mempermudah pelanggan dalam memesan menu tanpa perlu memanggil pelayan
- Mempercepat proses transaksi di kasir dengan tampilan pesanan yang masuk secara real-time
- Memberikan kemudahan bagi admin dalam mengelola produk, stok, dan data outlet
- Menyediakan informasi omset penjualan per outlet bagi admin

---

## Fitur-Fitur Utama Website

### Untuk Pelanggan

- **Landing Page** — Halaman utama berisi informasi restoran, keunggulan produk, dan preview menu
- **Akses Menu via Kode Outlet** — Pelanggan memasukkan kode outlet dan nomor meja untuk mulai memesan
- **Halaman Menu** — Menampilkan daftar produk yang tersedia dengan filter kategori dan fitur pencarian
- **Keranjang Belanja** — Pelanggan dapat memilih ukuran produk (small/large), mengatur jumlah, dan melihat total harga
- **Checkout & Pembayaran** — Mendukung beberapa metode pembayaran (BCA, BNI, GoPay, QRIS)
- **Halaman Sukses** — Konfirmasi pesanan setelah pembayaran berhasil

### Untuk Kasir

- **Dashboard Kasir** — Melihat daftar pesanan yang masuk dengan status _pending_
- **Selesaikan Pesanan** — Kasir dapat menandai pesanan sebagai selesai (_finished_)

### Untuk Admin

- **Dashboard Admin** — Melihat total omset, daftar produk, dan informasi outlet
- **Manajemen Produk** — Tambah, edit, dan hapus produk beserta stok (ukuran small & large)
- **Manajemen Outlet** — Edit informasi outlet seperti nama, total meja, dll
- **Filter & Pencarian Produk** — Memudahkan pengelolaan produk berdasarkan kategori

### Sistem Autentikasi

- Login berbasis session dengan role: **admin** dan **kasir**
- Redirect otomatis sesuai role setelah login

---

## Struktur Project

```
Project-UAS-PBW-BurgerGOOD/
│
├── index.html              # Landing page utama (tampilan publik)
├── access-menu.php         # Halaman input kode outlet & nomor meja
├── menu.php                # Halaman daftar menu produk
├── checkout.php            # Halaman checkout & pilih metode pembayaran
├── success.php             # Halaman konfirmasi pesanan berhasil
├── auth.php                # Halaman login (admin & kasir)
│
├── admin/                  # Panel admin (hanya bisa diakses role admin)
│   ├── dashboard.php       # Dashboard utama admin (omset, daftar produk)
│   ├── add-product.php     # Form tambah produk baru
│   ├── edit-product.php    # Form edit produk
│   ├── delete-product.php  # Hapus produk
│   └── edit-outlet.php     # Edit informasi outlet
│
├── kasir/                  # Panel kasir
│   ├── dashboard.php       # Dashboard kasir (daftar pesanan pending)
│   └── finish.php          # Proses selesaikan pesanan
│
├── controller/             # Logic backend (controller)
│   ├── payment.php         # Proses pembayaran & simpan ke database
│   ├── checkStock.php      # Cek stok produk sebelum checkout
│   └── logout.php          # Proses logout session
│
├── config/
│   └── db.php              # Konfigurasi koneksi database MySQL
│
├── assets/                 # File statis frontend
│   ├── styles.css          # Custom stylesheet utama
│   ├── script.js           # JavaScript untuk landing page
│   ├── menu.js             # JavaScript untuk halaman menu & keranjang
│   ├── checkout.js         # JavaScript untuk proses checkout
│   ├── css/                # Bootstrap CSS
│   ├── js/                 # Bootstrap JS
│   └── bootstrap-icons/    # Library ikon Bootstrap Icons
│
└── public/                 # Gambar & aset publik
    ├── logo.png
    ├── hero-pic.webp
    ├── *.png / *.jpg        # Gambar produk dan banner
    └── payment/            # Logo metode pembayaran (BCA, BNI, GoPay, QRIS)
```

### File & Folder Penting

| File/Folder              | Keterangan                                                                                                      |
| ------------------------ | --------------------------------------------------------------------------------------------------------------- |
| `config/db.php`          | Konfigurasi koneksi ke database MySQL. Ubah kredensial di sini sesuai environment lokal                         |
| `auth.php`               | Sistem login menggunakan session PHP dan password MD5                                                           |
| `access-menu.php`        | Entry point bagi pelanggan — validasi kode outlet dan nomor meja                                                |
| `controller/payment.php` | Memproses pembayaran, menyimpan order ke tabel `checkout`, dan mengurangi stok produk dengan transaksi database |
| `admin/dashboard.php`    | Halaman utama admin dengan data omset dan manajemen produk                                                      |
| `kasir/dashboard.php`    | Tampilan pesanan masuk untuk kasir                                                                              |

---

## Cara Menjalankan Aplikasi

### Prasyarat

- **PHP**
- **MySQL** / MariaDB
- **Web Server** (XAMPP / Laragon)

### Langkah Instalasi

1. **Clone repository ini**

   ```bash
   git clone https://github.com/RafyR27/Project-UAS-PBW-BurgerGOOD.git
   ```

2. **Pindahkan folder ke direktori web server**

   ```
   # Untuk XAMPP:
   Salin folder ke C:/xampp/htdocs/burgergood/

   # Untuk Laragon:
   Salin folder ke C:/laragon/www/burgergood/
   ```

3. **Buat database**
   - Buka **phpMyAdmin** (`http://localhost/phpmyadmin`)
   - Buat database baru dengan nama `burgergood`
   - Import file SQL jika tersedia (tabel: `user`, `outlet`, `product`, `checkout`)

4. **Sesuaikan konfigurasi database**

   Buka file `config/db.php` dan sesuaikan:

   ```php
   $host = "localhost";
   $user = "root";       // sesuaikan username MySQL
   $pass = "";           // sesuaikan password MySQL
   $db   = "burgergood";
   $BASE_URL = "http://localhost/burgergood/";
   ```

5. **Jalankan web server** (start Apache & MySQL di XAMPP/Laragon)

6. **Akses aplikasi**
   - **Landing Page:** `http://localhost/burgergood/`
   - **Akses Menu (Pelanggan):** `http://localhost/burgergood/access-menu.php`
   - **Login Admin/Kasir:** `http://localhost/burgergood/auth.php`

---

## Link Video Presentasi Project

> _Link video presentasi belum tersedia_
