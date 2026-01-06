<?php
require_once __DIR__ . '/../../config/database.php';

class Buku extends Database {
    
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Mengambil semua data buku dengan pencarian dan pagination
     */
    public function getAll($keyword, $limit, $offset) {
        $keyword = "%$keyword%";
        // Menggunakan Prepared Statement agar aman dari SQL Injection
        $stmt = $this->conn->prepare("SELECT buku.*, kategori.nama_kategori 
                                    FROM buku 
                                    JOIN kategori ON buku.kategori_id = kategori.id
                                    WHERE buku.judul LIKE ? OR buku.penulis LIKE ?
                                    ORDER BY buku.id DESC
                                    LIMIT ? OFFSET ?");
        $stmt->bind_param("ssii", $keyword, $keyword, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Kembalikan array, bukan mysqli_result
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
    
    /**
     * Menghitung total data buku untuk pagination
     */
    public function countData($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM buku WHERE judul LIKE ? OR penulis LIKE ?");
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)$row['total'];
    }
    
    /**
     * Mengambil semua kategori untuk dropdown
     * INI METHOD YANG HILANG!
     */
    public function getKategori() {
        $sql = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
        $result = $this->conn->query($sql);
        
        $kategori = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $kategori[] = $row;
            }
        }
        
        return $kategori;
    }
    
    /**
     * Menambah data buku baru
     */
    public function insert($judul, $penulis, $kategori, $stok, $gambar) {
        $stmt = $this->conn->prepare("INSERT INTO buku (judul, penulis, kategori_id, stok, gambar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiis", $judul, $penulis, $kategori, $stok, $gambar);
        return $stmt->execute();
    }
    
    /**
     * Menghapus data buku
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Mengambil data buku berdasarkan ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT buku.*, kategori.nama_kategori 
                                      FROM buku 
                                      LEFT JOIN kategori ON buku.kategori_id = kategori.id
                                      WHERE buku.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Mengupdate data buku
     */
    public function update($id, $judul, $penulis, $kategori, $stok, $gambar) {
        $stmt = $this->conn->prepare("UPDATE buku SET judul=?, penulis=?, kategori_id=?, stok=?, gambar=? WHERE id=?");
        $stmt->bind_param("ssiisi", $judul, $penulis, $kategori, $stok, $gambar, $id);
        return $stmt->execute();
    }
    
    // --- FUNGSI MANAJEMEN STOK ---
    
    /**
     * Mengurangi stok buku (saat dipinjam)
     */
    public function kurangiStok($id) {
        $stmt = $this->conn->prepare("UPDATE buku SET stok = stok - 1 WHERE id = ? AND stok > 0");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Menambah stok buku (saat dikembalikan)
     */
    public function tambahStok($id) {
        $stmt = $this->conn->prepare("UPDATE buku SET stok = stok + 1 WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    /**
     * Mengecek ketersediaan stok buku
     */
    public function cekStok($id) {
        $stmt = $this->conn->prepare("SELECT stok FROM buku WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row ? (int)$row['stok'] : 0;
    }
    
    /**
     * Mengambil buku berdasarkan kategori
     */
    public function getByKategori($kategori_id) {
        $stmt = $this->conn->prepare("SELECT buku.*, kategori.nama_kategori 
                                      FROM buku 
                                      JOIN kategori ON buku.kategori_id = kategori.id
                                      WHERE buku.kategori_id = ?
                                      ORDER BY buku.judul ASC");
        $stmt->bind_param("i", $kategori_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $data;
    }
}