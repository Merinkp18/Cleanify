<?php
// Backend/Admin/auth_admin.php

// Pastikan session hanya dimulai sekali
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    // Redirect ke login (path selalu benar karena pakai __DIR__)
    header('Location: ' . __DIR__ . '/../../Frontend/Akun/login.php');
    exit;
}

// Hanya Admin yg boleh akses halaman admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: ' . __DIR__ . '/../../Frontend/Akun/login.php');
    exit;
}
