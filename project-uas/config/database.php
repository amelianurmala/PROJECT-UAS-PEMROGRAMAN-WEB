<?php
class Database {
    protected $conn;

    public function __construct() {
        // Menggunakan blok try-catch agar error lebih rapi
        try {
            $this->conn = new mysqli("localhost", "root", "", "db_peminjaman_buku");
            
            if ($this->conn->connect_error) {
                throw new Exception("Gagal terhubung ke MySQL: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            // Menampilkan pesan yang lebih mudah dipahami
            die("<div style='color:red; font-family:sans-serif; padding:20px; border:1px solid red;'>
                    <strong>Waduh! Koneksi Database Gagal.</strong><br>
                    Pastikan MySQL di XAMPP sudah di-START (Aktif).<br>
                    <small>Pesan Error: " . $e->getMessage() . "</small>
                 </div>");
        }
    }
}