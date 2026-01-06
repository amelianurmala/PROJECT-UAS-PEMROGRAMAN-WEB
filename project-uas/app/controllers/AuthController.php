<?php
require_once 'app/models/User.php';

class AuthController {

    public function __construct() {
        // Memastikan session dimulai sebelum ada output
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        // Jika sudah login, langsung lempar ke dashboard/buku
        if (isset($_SESSION['user'])) {
            header("Location: /project-uas/buku");
            exit;
        }
        require_once 'app/views/auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /project-uas/auth");
            exit;
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validasi input kosong
        if (empty($username) || empty($password)) {
            $_SESSION['error'] = "Username dan Password wajib diisi!";
            header("Location: /project-uas/auth");
            exit;
        }

        $userModel = new User();
        $user = $userModel->login($username);

        // Verifikasi User dan Password
        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID untuk keamanan (mencegah Session Fixation)
            session_regenerate_id(true);
            
            // Simpan data yang diperlukan saja (jangan simpan password di session)
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role'] ?? 'user'
            ];

            header("Location: /project-uas/buku");
            exit;
        } else {
            // Kirim pesan error via session agar bisa ditampilkan di view
            $_SESSION['error'] = "Username atau Password salah!";
            header("Location: /project-uas/auth");
            exit;
        }
    }

    public function logout() {
        $_SESSION = []; // Kosongkan semua data session
        session_destroy();
        
        // Redirect ke halaman login setelah logout
        header("Location: /project-uas/auth");
        exit;
    }
}