<?php
// Backend/Admin/auth_admin.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login
if (!isset($_SESSION['user_id'], $_SESSION['role'])) {
    header('Location: ' . __DIR__ . '/../../../Frontend/Akun/login.php');
    exit;
}

// Hanya admin yg boleh akses
if ($_SESSION['role'] !== 'admin') {
    header('Location: ' . __DIR__ . '/../../../Frontend/Akun/login.php');
    exit;
}
