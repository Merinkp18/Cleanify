<?php
// Proteksi admin saja
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';

// Validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {

    // Prepared statement anti SQL injection
    $stmt = $conn->prepare("DELETE FROM customers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirect kembali
header("Location: ../dashboard.php?page=" . urlencode($_GET['return'] ?? 'customer'));
exit;
