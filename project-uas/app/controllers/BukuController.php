<?php

require_once 'app/models/Buku.php';

class BukuController {

    public function __construct() {
        // Memastikan session aktif untuk fitur flash message dan proteksi login
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Proteksi: Hanya user login yang bisa mengakses menu buku
        if (!isset($_SESSION['user'])) {
            header("Location: /project-uas/auth");
            exit;
        }
    }

    /**
     * Menampilkan daftar buku (Katalog)
     */
    public function index() {
        // Menangkap parameter dari URL
        $keyword = $_GET['cari'] ?? '';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $bukuModel = new Buku();
        
        // Ambil data buku (pastikan sudah dalam bentuk array)
        $buku = $bukuModel->getAll($keyword, $limit, $offset);
        
        // Pastikan $buku adalah array
        if (!is_array($buku)) {
            $buku = [];
        }
        
        // Hitung total data untuk pagination
        $total = $bukuModel->countData($keyword);
        $pages = ($total > 0) ? ceil($total / $limit) : 1;

        // Memanggil file View
        require_once 'app/views/buku/index.php';
    }

    /**
     * Menampilkan form tambah buku
     */
    public function tambah() {
        // Ambil data kategori untuk dropdown
        $bukuModel = new Buku();
        $kategori = $bukuModel->getKategori();
        
        require_once 'app/views/buku/tambah.php';
    }

    /**
     * Memproses penyimpanan data buku baru
     */
    public function simpan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namaFile = 'default_book.jpg'; // Gambar default

            // Logika upload gambar
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $fileName = $_FILES['gambar']['name'];
                $fileTmp = $_FILES['gambar']['tmp_name'];
                $fileSize = $_FILES['gambar']['size'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                // Validasi ekstensi dan ukuran (max 2MB)
                if (in_array($ext, $allowed) && $fileSize <= 2097152) {
                    $namaFile = time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = 'public/gambar_buku/' . $namaFile;
                    
                    // Pastikan folder exists
                    if (!file_exists('public/gambar_buku')) {
                        mkdir('public/gambar_buku', 0777, true);
                    }
                    
                    if (!move_uploaded_file($fileTmp, $targetPath)) {
                        $namaFile = 'default_book.jpg';
                        $_SESSION['error'] = "Gagal upload gambar, menggunakan gambar default.";
                    }
                } else {
                    $_SESSION['error'] = "Format gambar tidak valid atau ukuran terlalu besar (max 2MB).";
                }
            }

            // Validasi input
            $judul = trim($_POST['judul'] ?? '');
            $penulis = trim($_POST['penulis'] ?? '');
            $kategori_id = (int)($_POST['kategori_id'] ?? 0);
            $stok = (int)($_POST['stok'] ?? 0);

            if (empty($judul) || empty($penulis) || $kategori_id <= 0) {
                $_SESSION['error'] = "Semua field harus diisi dengan benar.";
                header("Location: /project-uas/buku/tambah");
                exit;
            }

            $bukuModel = new Buku();
            $result = $bukuModel->insert($judul, $penulis, $kategori_id, $stok, $namaFile);

            if ($result) {
                $_SESSION['success'] = "Buku berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambah buku. Silakan coba lagi.";
            }

            header("Location: /project-uas/buku");
            exit;
        }
    }

    /**
     * Menampilkan form edit buku berdasarkan ID
     */
    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = "ID buku tidak valid.";
            header("Location: /project-uas/buku");
            exit;
        }

        $bukuModel = new Buku();
        $buku = $bukuModel->getById($id);

        if (!$buku) {
            $_SESSION['error'] = "Data buku tidak ditemukan.";
            header("Location: /project-uas/buku");
            exit;
        }

        // Ambil data kategori untuk dropdown
        $kategori = $bukuModel->getKategori();

        require_once 'app/views/buku/edit.php';
    }

    /**
     * Memproses update data buku
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $namaFile = $_POST['gambar_lama'] ?? 'default_book.jpg'; 

            if ($id <= 0) {
                $_SESSION['error'] = "ID buku tidak valid.";
                header("Location: /project-uas/buku");
                exit;
            }

            // Cek jika ada upload gambar baru
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                $fileName = $_FILES['gambar']['name'];
                $fileTmp = $_FILES['gambar']['tmp_name'];
                $fileSize = $_FILES['gambar']['size'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($ext, $allowed) && $fileSize <= 2097152) {
                    $namaFileBaru = time() . '_' . uniqid() . '.' . $ext;
                    $targetPath = 'public/gambar_buku/' . $namaFileBaru;
                    
                    if (move_uploaded_file($fileTmp, $targetPath)) {
                        // Hapus gambar lama jika ada dan bukan default
                        if ($namaFile && $namaFile != 'default_book.jpg') {
                            $oldPath = 'public/gambar_buku/' . $namaFile;
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }
                        $namaFile = $namaFileBaru;
                    }
                }
            }

            // Validasi input
            $judul = trim($_POST['judul'] ?? '');
            $penulis = trim($_POST['penulis'] ?? '');
            $kategori_id = (int)($_POST['kategori_id'] ?? 0);
            $stok = (int)($_POST['stok'] ?? 0);

            if (empty($judul) || empty($penulis) || $kategori_id <= 0) {
                $_SESSION['error'] = "Semua field harus diisi dengan benar.";
                header("Location: /project-uas/buku/edit?id=" . $id);
                exit;
            }

            $bukuModel = new Buku();
            $result = $bukuModel->update($id, $judul, $penulis, $kategori_id, $stok, $namaFile);

            if ($result) {
                $_SESSION['success'] = "Data buku berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data buku.";
            }

            header("Location: /project-uas/buku");
            exit;
        }
    }

    /**
     * Menghapus data buku
     */
    public function hapus() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = "ID buku tidak valid.";
            header("Location: /project-uas/buku");
            exit;
        }

        $bukuModel = new Buku();
        
        // Mengambil info buku untuk menghapus gambar fisiknya
        $data = $bukuModel->getById($id);
        
        if ($data) {
            // Hapus gambar jika bukan default
            if (isset($data['gambar']) && $data['gambar'] != 'default_book.jpg') {
                $path = 'public/gambar_buku/' . $data['gambar'];
                if (file_exists($path)) {
                    @unlink($path);
                }
            }

            $result = $bukuModel->delete($id);
            
            if ($result) {
                $_SESSION['success'] = "Buku berhasil dihapus.";
            } else {
                $_SESSION['error'] = "Gagal menghapus buku.";
            }
        } else {
            $_SESSION['error'] = "Data buku tidak ditemukan.";
        }

        header("Location: /project-uas/buku");
        exit;
    }
}