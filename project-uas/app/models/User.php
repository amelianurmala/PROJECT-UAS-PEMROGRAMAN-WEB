<?php
require_once 'config/database.php';

class User extends Database {

    public function login($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Tambahan: Fitur Registrasi (Sangat penting agar ada user yang bisa login)
    public function register($nama, $username, $password, $role = 'user') {
        // Hash password sebelum disimpan ke database (Keamanan Mutlak)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $this->conn->prepare(
            "INSERT INTO users (nama, username, password, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $nama, $username, $hashed_password, $role);
        
        return $stmt->execute();
    }

    // Tambahan: Cek apakah username sudah dipakai (untuk validasi register)
    public function isUsernameTaken($username) {
        $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Tambahan: Ambil data user berdasarkan ID (untuk session/profil)
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, nama, username, role FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}