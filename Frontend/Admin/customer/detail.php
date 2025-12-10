<?php
// Proteksi admin
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';

// base dashboard path dinamis
$dash = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/dashboard.php';

// Validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id < 1) {
    echo "<div class='alert alert-danger'>ID tidak valid!</div>";
    echo "<a href='".htmlspecialchars($dash, ENT_QUOTES, 'UTF-8')."?page=customer' class='btn btn-secondary'>Kembali</a>";
    return;
}

// Prepared statement (ANTI SQL INJECTION)
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan!</div>";
    echo "<a href='".htmlspecialchars($dash, ENT_QUOTES, 'UTF-8')."?page=customer' class='btn btn-secondary'>Kembali</a>";
    return;
}

function esc($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
?>

<style>
    .detail-table {
        width: 70%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #ffffff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    .detail-table th, .detail-table td {
        border: 1px solid #ccc;
        padding: 12px 15px;
        vertical-align: top;
    }

    .detail-table th {
        width: 220px;
        background: #0072CF;
        color: white;
        font-weight: bold;
    }

    .btn {
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        margin-right: 6px;
        display: inline-block;
    }

    .btn-secondary {
        background: gray;
        color: white;
    }
</style>

<h2>Detail Customer</h2>

<table class="detail-table">
    <tr><th>Nama</th><td><?= esc($data['name'] ?? '-') ?></td></tr>
    <tr><th>Email</th><td><?= esc($data['email'] ?? '-') ?></td></tr>
    <tr><th>Alamat</th><td><?= esc($data['address'] ?? '-') ?></td></tr>
    <tr><th>Nomor Telepon</th><td><?= esc($data['phone'] ?? '-') ?></td></tr>
    <tr><th>Kontak</th><td><?= esc($data['contact_preference'] ?? '-') ?></td></tr>
    <tr><th>Tipe Tempat</th><td><?= esc($data['property_type'] ?? '-') ?></td></tr>
    <tr><th>Ukuran Tempat</th><td><?= esc($data['property_size_sqm'] ?? '-') ?></td></tr>
    <tr><th>Alamat Lengkap</th><td><?= nl2br(esc($data['full_address'] ?? '-')) ?></td></tr>
</table>

<br>

<a href="<?= esc($dash) ?>?page=customer" class="btn btn-secondary">Kembali</a>
