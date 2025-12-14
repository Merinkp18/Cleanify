<?php
// Backend/login_proccess.php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    // Ambil mentah dulu (tanpa trim)
    $rawInput = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input dasar
    if ($rawInput === '' || $password === '') {
        $_SESSION['login_error'] = 'Email/username dan password wajib diisi.';
        header('Location: ../Frontend/Akun/login.php');
        exit;
    }

    // Tolak kalau email/username mengandung spasi (di mana pun)
    if (preg_match('/\s/', $rawInput)) {
        $_SESSION['login_error'] = 'Email/username tidak boleh mengandung spasi.';
        header('Location: ../Frontend/Akun/login.php');
        exit;
    }

    // Setelah dipastikan bebas spasi, boleh di-trim (gak ngubah perilaku lain)
    $input = trim($rawInput);

    // Query cek user via email ATAU username
    $sql = "SELECT id, username, email, password, role
            FROM users
            WHERE email = ? OR username = ?
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $_SESSION['login_error'] = 'Terjadi kesalahan server. Coba lagi nanti.';
        header('Location: ../Frontend/Akun/login.php');
        exit;
    }

    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result ? $result->fetch_assoc() : null;

    // Validasi user + password
    if ($user && password_verify($password, $user['password'])) {

        // Hardening session
        session_regenerate_id(true);

        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'admin':
            case 'cleaner':
                header('Location: ../Frontend/Admin/dashboard.php');
                break;

            case 'user':
                header('Location: ../Frontend/User/dashboard.php');
                break;

            default:
                // fallback kalau ada role aneh
                $_SESSION['login_error'] = 'Akun tidak valid.';
                header('Location: ../Frontend/Akun/login.php');
                break;
        }

        exit;
    }

    // Jika gagal login
    $_SESSION['login_error'] = 'Email/username atau password salah.';
    header('Location: ../Frontend/Akun/login.php');
    exit;
}

// Kalau akses langsung tanpa POST
header('Location: ../Frontend/Akun/login.php');
exit;
