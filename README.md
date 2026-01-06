# PROJECT-UAS-PEMROGRAMAN-WEB

Nama          | **Amelia Nurmala Dewi**   
NIM           | **312410199**        
Kelas         | **TI.24.A2**      

---

# SISTEM PERPUSTAKAAN DIGITAL (SIPERPUS)

## 1. Pendahuluan

Sistem Perpustakaan Digital (SIPERPUS) adalah aplikasi berbasis web yang dibuat untuk membantu pengelolaan data buku dan proses peminjaman di perpustakaan secara terkomputerisasi.

---

## 2. Tujuan Pembuatan Aplikasi

Tujuan utama pembuatan aplikasi SIPERPUS adalah:

- Mempermudah pengelolaan data perpustakaan

- Menggantikan pencatatan manual menjadi sistem digital

- Menyediakan sistem peminjaman buku yang rapi dan terdokumentasi

- Meningkatkan keamanan dan konsistensi data

---

## 3. Teknologi yang Digunakan

- PHP Native
- MySQL
- Bootstrap 5
- HTML & CSS
- Apache (.htaccess)
- XAMPP

## 4. Fitur Aplikasi
### 🔐 Autentikasi
- Login pengguna
- Session management
- Logout

### 📚 Manajemen Buku
- Tambah buku
- Edit buku
- Hapus buku
- Upload cover buku
- Pencarian & pagination

### 🔄 Peminjaman Buku
- Proses pinjam buku
- Batas waktu pengembalian
- Pengembalian buku
- Perhitungan denda otomatis

### 🧾 Laporan
- Riwayat peminjaman
- Statistik peminjaman
- Cetak laporan (print)
  
---

## 5. Struktur Folder Proyek

```
PROJECT-UAS
├── .htaccess
├── index.php
│
├── app
│   ├── controllers
│   │   ├── AuthController.php
│   │   ├── BukuController.php
│   │   └── PeminjamanController.php
│   │
│   ├── models
│   │   ├── Buku.php
│   │   ├── Peminjaman.php
│   │   └── User.php
│   │
│   ├── views
│   │   ├── auth
│   │   │   └── login.php
│   │   │
│   │   ├── buku
│   │   │   ├── index.php
│   │   │   ├── tambah.php
│   │   │   └── edit.php
│   │   │
│   │   ├── peminjaman
│   │   │   ├── index.php
│   │   │   ├── form.php
│   │   │   └── cetak.php
│   │   │
│   │   └── layout
│   │       ├── header.php
│   │       └── footer.php
│
├── config
│   └── database.php
│
├── public
│   ├── css
│   │   └── bootstrap.min.css
│   │
│   ├── js
│   │   └── bootstrap.bundle.js
│   │
│   └── gambar_buku
```

---
## 6. Alur Pengerjaan Proyek 

1. Membuat database **db_peminjaman_buku**
2. Menentukan konsep **MVC**
3. Membuat `.htaccess` untuk routing
4. Membuat `index.php` sebagai Front Controller
5. Membuat koneksi database
6. Membuat Model (User, Buku, Peminjaman)
7. Membuat Controller (Auth, Buku, Peminjaman)
8. Membuat View dengan Bootstrap
9. Mengatur session login
10. Implementasi transaksi peminjaman
11. Menambahkan fitur denda & laporan
12. Testing dan debugging

---

## 7. Konfigurasi Database

### File: `config/database.php`

**Bagian penting:**

```php
$this->conn = new mysqli("localhost", "root", "", "db_peminjaman_buku");
```

Fungsi utama:

* Menghubungkan aplikasi ke MySQL
* Menangani error koneksi dengan try-catch

---

## 8. Routing Aplikasi

### File: `.htaccess`

**Fungsi utama:**

* Mengarahkan semua URL ke `index.php`
* Mengaktifkan clean URL

```apache
RewriteRule ^(.*)$ index.php?url=$1
```

---

### File: `index.php`

**Peran utama:**

* Front Controller
* Menentukan controller & method dari URL
* Menjalankan aplikasi MVC

**Bagian penting:**

```php
$controllerName = ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
```

---

## 9. Model (Logika Database)

### 📁 `User.php`

Fungsi penting:

* Login user
* Hash password
* Ambil data user

```php
password_hash($password, PASSWORD_DEFAULT);
```

---

### 📁 `Buku.php`

Fungsi penting:

* CRUD buku
* Upload gambar
* Manajemen stok
* Pagination & pencarian

```php
public function kurangiStok($id)
```

---

### 📁 `Peminjaman.php`

Fungsi penting:

* Proses pinjam buku
* Transaksi database
* Pengembalian & denda

```php
$this->conn->begin_transaction();
```

---

## 10. Controller (Penghubung)

### 📁 `AuthController`

* Login
* Logout
* Session user

### 📁 `BukuController`

* Menampilkan katalog
* Tambah, edit, hapus buku

### 📁 `PeminjamanController`

* Proses pinjam
* Proses kembali
* Cetak laporan

---

## 11. View (Tampilan)

### Halaman penting:

* Login
* Katalog Buku
* Tambah & Edit Buku
* Form Peminjaman
* Riwayat Peminjaman
* Cetak Laporan

**Framework UI:** Bootstrap 5
**Tujuan:** Tampilan responsif dan user-friendly

---

## 12. Cetak Laporan

* Format A4 Landscape
* Menampilkan statistik
* Bisa difilter berdasarkan status & bulan

---

## 13. Dokumentasi Tampilan Aplikasi

- Halaman Login
- Katalog Buku
- Tambah Buku
- Edit Buku
- Form Peminjaman
- Riwayat Peminjaman
- Cetak Laporan

---

## 14. Kesimpulan

Dengan dibuatnya aplikasi **SIPERPUS**, pengelolaan perpustakaan menjadi:

* Lebih rapi
* Terstruktur
* Efisien
* Aman dari kesalahan pencatatan manual

Proyek ini berhasil menerapkan konsep **MVC**, **CRUD**, dan **transaksi database** menggunakan PHP Native.



