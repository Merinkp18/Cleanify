<?php
include '../../../Backend/db.php';

if (!isset($_GET['id'])) {
    header("Location: pekerja.php");
    exit;
}

$id = $_GET['id'];

// 1. Hapus jadwal yang terkait dengan pekerja ini
mysqli_query($conn, "DELETE FROM schedules WHERE employee_id = $id");

// 2. Hapus datanya dari tabel employees
mysqli_query($conn, "DELETE FROM employees WHERE id = $id");

// 3. Redirect kembali
header("Location: pekerja.php");
exit;
?>
