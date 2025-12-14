<?php
session_start();
require __DIR__ . '/db.php';

// Pastikan request valid
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['register'])) {
    header('Location: ../Frontend/Akun/register.php');
    exit;
}

// Ambil input
$username = trim($_POST['username'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ==== VALIDASI DASAR ====
if ($username === '' || $email === '' || $password === '') {
    $_SESSION['register_error'] = "Semua field wajib diisi.";
    header('Location: ../Frontend/Akun/register.php');
    exit;
}

// ==== VALIDASI EMAIL ====
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['register_error'] = "Format email tidak valid.";
    header('Location: ../Frontend/Akun/register.php');
    exit;
}

// ==== VALIDASI USERNAME ====
if (!preg_match('/^[a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*$/', $username)) {
    $_SESSION['register_error'] = "Username hanya boleh alfanumerik atau hyphen.";
    header('Location: ../Frontend/Akun/register.php');
    exit;
}

// ==== VALIDASI PASSWORD ====
if (strlen($password) < 8 || 
    !preg_match('/[a-z]/', $password) || 
    !preg_match('/[0-9]/', $password)) 
{
    $_SESSION['register_error'] = "Password minimal 8 karakter dan harus mengandung huruf kecil & angka.";
    header('Location: ../Frontend/Akun/register.php');
    exit;
}

// ==== CEK DUPLIKAT ====
$check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
$check->bind_param("ss", $email, $username);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    $_SESSION['register_error'] = "Email atau username sudah digunakan!";
    header('Location: ../Frontend/Akun/register.php');
    exit;
}

$check->close();

// ==== HASH PASSWORD ====
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ==== INSERT DATA ====
$stmt = $conn->prepare("
    INSERT INTO users (username, email, password, role)
    VALUES (?, ?, ?, 'user')
");

$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['register_success'] = "Akun berhasil dibuat! Silakan login.";
    header('Location: ../Frontend/Akun/login.php');
    exit;
} else {
    $_SESSION['register_error'] = "Terjadi kesalahan pada server.";
    header('Location: ../Frontend/Akun/register.php');
    exit;
}
