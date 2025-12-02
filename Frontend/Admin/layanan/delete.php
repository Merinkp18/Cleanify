<?php
// Proteksi admin
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';

// Koneksi database
require __DIR__ . '/../../../Backend/db.php';

// Validasi id
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "<script>alert('ID tidak valid!'); window.location='layanan.php';</script>";
    exit;
}

$id = (int) $_GET['id'];

// Hapus data dengan prepared statement
$stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "<script>alert('Layanan berhasil dihapus!'); window.location='layanan.php';</script>";
    exit;
} else {
    echo "<script>alert('Gagal menghapus layanan!'); window.location='layanan.php';</script>";
    exit;
}
?>
