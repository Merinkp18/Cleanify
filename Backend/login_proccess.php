<?php
// Backend/login_proccess.php
session_start();
require __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $input    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($input === '' || $password === '') {
        $_SESSION['login_error'] = 'Email/username dan password wajib diisi.';
        header('Location: ../Frontend/Akun/login.php');
        exit;
    }

    // Prepared statement: cari by email ATAU username
    $sql  = 'SELECT id, username, email, password, role 
             FROM users 
             WHERE email = ? OR username = ? 
             LIMIT 1';

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        // Jangan bocorin detail error ke user
        $_SESSION['login_error'] = 'Terjadi kesalahan server. Coba lagi nanti.';
        header('Location: ../Frontend/Akun/login.php');
        exit;
    }

    $stmt->bind_param('ss', $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result ? $result->fetch_assoc() : null;

    if ($user && password_verify($password, $user['password'])) {
        // Hardening session
        if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role']     = $user['role'];

    // Redirect by role
    if ($user['role'] === 'admin' || $user['role'] === 'cleaner') {
        header('Location: ../Frontend/Admin/dashboard.php');
        exit;
    } 
    elseif ($user['role'] === 'user') {
        header('Location: ../Frontend/User/dashboard.php');
        exit;
    } 

}
    } else {
        $_SESSION['login_error'] = 'Email/username atau password salah.';
        header('Location: ../Frontend/Akun/login.php');
        exit;
    }
}

header('Location: ../Frontend/Akun/login.php');
exit;
