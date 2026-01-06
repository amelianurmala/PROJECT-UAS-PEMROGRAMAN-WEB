<?php
require_once 'config/database.php';

class Peminjaman extends Database {
    
    /**
     * Mengambil semua data peminjaman
     */
    public function getAll() {
        $sql = "SELECT 
                    p.*, 
                    u.nama AS nama_peminjam,
                    u.username,
                    b.judul, 
                    b.penulis,
                    b.gambar,
                    k.nama_kategori
                FROM peminjaman p
                INNER JOIN users u ON p.user_id = u.id
                INNER JOIN buku b ON p.buku_id = b.id
                LEFT JOIN kategori k ON b.kategori_id = k.id
                ORDER BY p.id DESC";
        
        $result = $this->conn->query($sql);
        
        // Return array untuk menghindari error count()
        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        
        return $data;
    }
    
    /**
     * Mengambil peminjaman berdasarkan user ID
     */
    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT 
                                        p.*, 
                                        b.judul,
                                        b.penulis,
                                        b.gambar,
                                        k.nama_kategori
                                      FROM peminjaman p 
                                      INNER JOIN buku b ON p.buku_id = b.id 
                                      LEFT JOIN kategori k ON b.kategori_id = k.id
                                      WHERE p.user_id = ? 
                                      ORDER BY p.id DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Memproses peminjaman buku
     */
    public function pinjam($user, $buku, $tgl_pinjam, $tgl_kembali) {
        $this->conn->begin_transaction();
        
        try {
            // 1. Cek Stok (dengan lock untuk race condition)
            $cek = $this->conn->prepare("SELECT stok FROM buku WHERE id = ? FOR UPDATE");
            $cek->bind_param("i", $buku);
            $cek->execute();
            $result = $cek->get_result()->fetch_assoc();
            $stok = $result['stok'] ?? 0;
            
            if ($stok <= 0) {
                throw new Exception("Stok buku habis!");
            }
            
            // 2. Simpan Data Peminjaman
            $stmt = $this->conn->prepare(
                "INSERT INTO peminjaman (user_id, buku_id, tanggal_pinjam, tanggal_kembali, status)
                 VALUES (?, ?, ?, ?, 'Dipinjam')"
            );
            $stmt->bind_param("iiss", $user, $buku, $tgl_pinjam, $tgl_kembali);
            $stmt->execute();
            
            // 3. Kurangi Stok Buku
            $update = $this->conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id = ?");
            $update->bind_param("i", $buku);
            $update->execute();
            
            // Commit jika semua berhasil
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            // Rollback jika ada error
            $this->conn->rollback();
            error_log("Error peminjaman: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Memproses pengembalian buku
     */
    public function kembalikan($id) {
        $this->conn->begin_transaction();
        
        try {
            // 1. Ambil data peminjaman (dengan lock)
            $stmt = $this->conn->prepare("SELECT * FROM peminjaman WHERE id = ? FOR UPDATE");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $data = $stmt->get_result()->fetch_assoc();
            
            if (!$data) {
                throw new Exception("Data peminjaman tidak ditemukan");
            }
            
            if ($data['status'] === 'Selesai') {
                throw new Exception("Buku sudah dikembalikan sebelumnya");
            }
            
            // 2. Hitung Denda (Rp 2.000/hari)
            $tgl_kembali_seharusnya = strtotime($data['tanggal_kembali']);
            $tgl_sekarang = strtotime(date('Y-m-d'));
            $selisih_hari = ($tgl_sekarang - $tgl_kembali_seharusnya) / 86400;
            $denda = ($selisih_hari > 0) ? floor($selisih_hari) * 2000 : 0;
            
            // 3. Update Status Peminjaman
            $updatePinjam = $this->conn->prepare(
                "UPDATE peminjaman 
                 SET status = 'Selesai', denda = ? 
                 WHERE id = ?"
            );
            $updatePinjam->bind_param("ii", $denda, $id);
            $updatePinjam->execute();
            
            // 4. Kembalikan Stok Buku
            $updateBuku = $this->conn->prepare("UPDATE buku SET stok = stok + 1 WHERE id = ?");
            $updateBuku->bind_param("i", $data['buku_id']);
            $updateBuku->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Error pengembalian: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mengambil peminjaman berdasarkan ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT 
                                        p.*,
                                        u.nama AS nama_peminjam,
                                        u.username,
                                        u.email,
                                        b.judul,
                                        b.penulis,
                                        b.gambar,
                                        k.nama_kategori
                                      FROM peminjaman p
                                      INNER JOIN users u ON p.user_id = u.id
                                      INNER JOIN buku b ON p.buku_id = b.id
                                      LEFT JOIN kategori k ON b.kategori_id = k.id
                                      WHERE p.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Mengambil riwayat peminjaman user tertentu
     */
    public function getRiwayat($user_id, $limit = 10) {
        $stmt = $this->conn->prepare("SELECT 
                                        p.*,
                                        b.judul,
                                        b.penulis,
                                        b.gambar
                                      FROM peminjaman p
                                      INNER JOIN buku b ON p.buku_id = b.id
                                      WHERE p.user_id = ?
                                      ORDER BY p.id DESC
                                      LIMIT ?");
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Cek apakah user sudah pinjam buku ini dan belum mengembalikan
     */
    public function cekSudahPinjam($user_id, $buku_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total 
                                      FROM peminjaman 
                                      WHERE user_id = ? 
                                      AND buku_id = ? 
                                      AND status = 'Dipinjam'");
        $stmt->bind_param("ii", $user_id, $buku_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['total'] > 0;
    }
    
    /**
     * Update status menjadi terlambat otomatis
     */
    public function updateStatusTerlambat() {
        $sql = "UPDATE peminjaman 
                SET status = 'Terlambat' 
                WHERE tanggal_kembali < CURDATE() 
                AND status = 'Dipinjam'";
        
        return $this->conn->query($sql);
    }
    
    /**
     * Statistik peminjaman
     */
    public function getStatistik() {
        $sql = "SELECT 
                    COUNT(*) as total_peminjaman,
                    SUM(CASE WHEN status = 'Dipinjam' THEN 1 ELSE 0 END) as sedang_dipinjam,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as sudah_kembali,
                    SUM(CASE WHEN status = 'Terlambat' THEN 1 ELSE 0 END) as terlambat,
                    SUM(denda) as total_denda
                FROM peminjaman";
        
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}