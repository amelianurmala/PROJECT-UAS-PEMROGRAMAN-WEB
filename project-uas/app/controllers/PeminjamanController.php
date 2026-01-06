<?php
require_once 'app/models/Peminjaman.php';
require_once 'app/models/Buku.php';

class PeminjamanController {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Proteksi Halaman: Wajib Login
        if (!isset($_SESSION['user'])) {
            header("Location: /project-uas/auth");
            exit;
        }
    }
    
    /**
     * Menampilkan halaman daftar semua peminjaman
     */
    public function index() {
        $peminjamanModel = new Peminjaman();
        
        // Update status terlambat secara otomatis saat halaman dibuka
        $peminjamanModel->updateStatusTerlambat();
        
        // Ambil data peminjaman
        $data = $peminjamanModel->getAll();
        
        require_once 'app/views/peminjaman/index.php';
    }
    
    /**
     * Menampilkan form input peminjaman buku
     */
    public function form() {
        $buku_id = $_GET['id'] ?? null;
        
        if (!$buku_id) {
            $_SESSION['error'] = "Pilih buku yang ingin dipinjam terlebih dahulu.";
            header("Location: /project-uas/buku");
            exit;
        }
        
        $bukuModel = new Buku();
        $buku = $bukuModel->getById($buku_id);
        
        if (!$buku) {
            $_SESSION['error'] = "Data buku tidak ditemukan.";
            header("Location: /project-uas/buku");
            exit;
        }
        
        if ($buku['stok'] <= 0) {
            $_SESSION['error'] = "Maaf, stok buku ini sedang habis.";
            header("Location: /project-uas/buku");
            exit;
        }
        
        require_once 'app/views/peminjaman/form.php';
    }

    /**
     * Alias untuk fungsi proses agar tidak error "Method tidak ditemukan"
     */
    public function simpan() {
        $this->proses();
    }
    
    /**
     * Memproses penyimpanan data peminjaman ke database
     */
    public function proses() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user']['id'];
            $buku_id = (int)$_POST['buku_id'];
            $tanggal_pinjam = date('Y-m-d');
            $tanggal_kembali = $_POST['tanggal_kembali'];
            
            // 1. Validasi Tanggal
            if (empty($tanggal_kembali) || strtotime($tanggal_kembali) <= strtotime($tanggal_pinjam)) {
                $_SESSION['error'] = "Tanggal kembali tidak valid (minimal besok).";
                header("Location: /project-uas/peminjaman/form?id=" . $buku_id);
                exit;
            }
            
            $peminjamanModel = new Peminjaman();
            
            // 2. Cek apakah user sudah meminjam buku yang sama dan belum dikembalikan
            if ($peminjamanModel->cekSudahPinjam($user_id, $buku_id)) {
                $_SESSION['error'] = "Anda masih meminjam buku ini. Silakan kembalikan dulu buku sebelumnya.";
                header("Location: /project-uas/peminjaman");
                exit;
            }
            
            // 3. Eksekusi Simpan
            $result = $peminjamanModel->pinjam($user_id, $buku_id, $tanggal_pinjam, $tanggal_kembali);
            
            if ($result) {
                $_SESSION['success'] = "Berhasil meminjam buku!";
                header("Location: /project-uas/peminjaman");
            } else {
                $_SESSION['error'] = "Gagal meminjam buku. Periksa stok buku.";
                header("Location: /project-uas/buku");
            }
            exit;
        }
    }
    
    /**
     * Memproses pengembalian buku
     */
    public function kembalikan() {
        $id = (int)($_GET['id'] ?? 0);
        
        if ($id <= 0) {
            $_SESSION['error'] = "ID transaksi tidak valid.";
            header("Location: /project-uas/peminjaman");
            exit;
        }
        
        $peminjamanModel = new Peminjaman();
        $result = $peminjamanModel->kembalikan($id);
        
        if ($result) {
            $_SESSION['success'] = "Buku telah berhasil dikembalikan.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat memproses pengembalian.";
        }
        
        header("Location: /project-uas/peminjaman");
        exit;
    }
    
    /**
     * Menampilkan halaman laporan untuk dicetak
     */
    public function cetak() {
        $peminjamanModel = new Peminjaman();
        
        $status = $_GET['status'] ?? 'all';
        $bulan = $_GET['bulan'] ?? date('Y-m');
        
        $data = $peminjamanModel->getAll();
        
        // Filter Status
        if ($status !== 'all') {
            $data = array_filter($data, function($p) use ($status) {
                return $p['status'] == $status;
            });
        }
        
        // Filter Bulan
        if (!empty($bulan)) {
            $data = array_filter($data, function($p) use ($bulan) {
                return date('Y-m', strtotime($p['tanggal_pinjam'])) == $bulan;
            });
        }
        
        $statistik = $peminjamanModel->getStatistik();
        
        require_once 'app/views/peminjaman/cetak.php';
    }
}