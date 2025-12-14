<?php
// Backend/Admin/auth_admin.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// URL path ke login (sesuaikan base folder project kamu: /cleanify)
$loginUrl = '/cleanify/Frontend/Akun/login.php';

// Jika belum login
if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header('Location: ' . $loginUrl);
    exit;
}

// Hanya admin yg boleh akses
if ($_SESSION['role'] !== 'admin') {
    header('Location: ' . $loginUrl);
    exit;
}
