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

## 6. Database Sistem Perpustakaan Digital

Aplikasi ini menggunakan database **MySQL** dengan nama:

```sql
db_peminjaman_buku
```

Database berfungsi untuk menyimpan data pengguna, buku, kategori, dan transaksi peminjaman.

---

### - Tabel `users`

Digunakan untuk menyimpan data akun yang bisa login ke sistem.

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    username VARCHAR(50),
    password VARCHAR(255),
    role VARCHAR(20)
);
```

Password disimpan dalam bentuk **hash** untuk keamanan.

---

### - Tabel `kategori`

Digunakan untuk mengelompokkan buku berdasarkan jenisnya.

```sql
CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50)
);
```

---

### - Tabel `buku`

Menyimpan data koleksi buku perpustakaan.

```sql
CREATE TABLE buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150),
    penulis VARCHAR(100),
    kategori_id INT,
    stok INT,
    gambar VARCHAR(100)
);
```

Kolom `stok` akan berkurang saat buku dipinjam dan bertambah saat dikembalikan.

---

### - Tabel `peminjaman`

Digunakan untuk mencatat transaksi peminjaman buku.

```sql
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    buku_id INT,
    tanggal_pinjam DATE,
    tanggal_kembali DATE,
    status VARCHAR(20),
    denda INT
);
```

Status peminjaman:

* `Dipinjam`
* `Terlambat`
* `Selesai`

---

### - Relasi Database (Singkat)

* **users → peminjaman** : satu user bisa banyak peminjaman
* **buku → peminjaman** : satu buku bisa dipinjam berkali-kali
* **kategori → buku** : satu kategori punya banyak buku

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

- **Halaman Login**
  <img width="1364" height="682" alt="Screenshot 2026-01-06 181936" src="https://github.com/user-attachments/assets/27054fcf-c229-45e8-bb8f-9bbbd6dc8b85" />

- **Katalog Buku**
  <img width="1365" height="681" alt="Screenshot 2026-01-06 210031" src="https://github.com/user-attachments/assets/831304cf-0f4c-49ab-8fad-79c4c7f1953c" />

- **Tambah Buku**
  <img width="1358" height="671" alt="Screenshot 2026-01-06 193044" src="https://github.com/user-attachments/assets/b73cf703-c83a-4473-833b-d478d09ea81c" />
  <img width="1360" height="675" alt="Screenshot 2026-01-06 193156" src="https://github.com/user-attachments/assets/a1ac8cdd-151d-4c47-887b-384083c94b7e" />

- **Edit Buku**
  <img width="1365" height="675" alt="Screenshot 2026-01-06 192701" src="https://github.com/user-attachments/assets/f999272d-a70b-486a-8566-f102f88c43be" />
  <img width="1365" height="674" alt="Screenshot 2026-01-06 210457" src="https://github.com/user-attachments/assets/0f442780-5d4b-4b8a-9a3d-8dc17367bd51" />

- **Menghapus Buku**
  <img width="1361" height="677" alt="Screenshot 2026-01-06 193113" src="https://github.com/user-attachments/assets/671e3a2d-c8e3-4be1-a346-60e73684e8d0" />
  <img width="1365" height="673" alt="Screenshot 2026-01-06 193215" src="https://github.com/user-attachments/assets/698e795b-b454-4504-9559-e0e3e73d4c92" />

- **Search Buku**
  <img width="1363" height="671" alt="Screenshot 2026-01-06 182058" src="https://github.com/user-attachments/assets/d4daa7e2-06f0-4074-80eb-9fa5bb9289c3" />

- **Form Peminjaman**
  <img width="1362" height="680" alt="Screenshot 2026-01-06 192743" src="https://github.com/user-attachments/assets/8ef99015-828f-466c-b87f-e7ac26e3832a" />
  <img width="1361" height="678" alt="Screenshot 2026-01-06 192801" src="https://github.com/user-attachments/assets/08516f47-d7e0-4ca8-9f74-e50dfa2e50c4" />
  
- **Form Pengembalian**
  <img width="1365" height="683" alt="Screenshot 2026-01-06 192816" src="https://github.com/user-attachments/assets/cf93db4b-75a6-42d6-80bf-375985295aff" />

- **Riwayat Peminjaman**
  <img width="1363" height="676" alt="Screenshot 2026-01-06 193249" src="https://github.com/user-attachments/assets/02c1fdee-1185-450d-8cdc-16d733d82378" />
  <img width="1363" height="206" alt="Screenshot 2026-01-06 193316" src="https://github.com/user-attachments/assets/b089793b-8ae3-490c-b3f2-80060d28090f" />

- **Cetak Laporan**
  <img width="1352" height="682" alt="Screenshot 2026-01-06 182449" src="https://github.com/user-attachments/assets/9355872e-6d90-407d-82c7-2ecb2f23d5c5" />

- **Tombol Keluar**
  <img width="1363" height="675" alt="Screenshot 2026-01-06 211145" src="https://github.com/user-attachments/assets/eb197ea0-e18e-439d-82f7-d77573716993" />

---

## 14. Kesimpulan

Dengan dibuatnya aplikasi **SIPERPUS**, pengelolaan perpustakaan menjadi:

* Lebih rapi
* Terstruktur
* Efisien
* Aman dari kesalahan pencatatan manual

Proyek ini berhasil menerapkan konsep **MVC**, **CRUD**, dan **transaksi database** menggunakan PHP Native.



