<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';

// base dashboard path dinamis
$dash = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/dashboard.php';

// Validasi ID
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID tidak valid!</div>";
    echo "<a href='".htmlspecialchars($dash)."?page=layanan' class='btn btn-secondary'>Kembali</a>";
    return;
}

$id = (int)$_GET['id'];

// Query aman pakai prepared statement
$stmt = $conn->prepare("
    SELECT name, category, price, duration_minutes, status,
           short_description, full_description, features, not_included
    FROM services
    WHERE id = ?
    LIMIT 1
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='alert alert-danger'>Data layanan tidak ditemukan!</div>";
    echo "<a href='".htmlspecialchars($dash)."?page=layanan' class='btn btn-secondary'>Kembali</a>";
    return;
}

$data = $result->fetch_assoc();

if (!function_exists('esc')) {
    function esc($val) {
        return htmlspecialchars((string)$val, ENT_QUOTES, 'UTF-8');
    }
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

    .btn-warning {
        background: #f0ad4e;
        color: white;
    }
</style>

<h2>Detail Layanan</h2>

<table class="detail-table">
    <tr><th>Nama</th><td><?= esc($data['name'] ?? '') ?></td></tr>
    <tr><th>Kategori</th><td><?= esc($data['category'] ?? '') ?></td></tr>
    <tr><th>Harga</th><td>Rp <?= number_format((float)($data['price'] ?? 0), 0, ",", ".") ?></td></tr>
    <tr><th>Durasi</th><td><?= esc($data['duration_minutes'] ?? '') ?> menit</td></tr>
    <tr><th>Status</th><td><?= esc(ucfirst($data['status'] ?? '')) ?></td></tr>
    <tr><th>Deskripsi Singkat</th><td><?= esc($data['short_description'] ?? '') ?></td></tr>
    <tr><th>Deskripsi Lengkap</th><td><?= nl2br(esc($data['full_description'] ?? '')) ?></td></tr>
    <tr><th>Fitur</th><td><?= nl2br(esc($data['features'] ?? '')) ?></td></tr>
    <tr><th>Tidak Termasuk</th><td><?= nl2br(esc($data['not_included'] ?? '')) ?></td></tr>
</table>

<br>

<a href="<?= htmlspecialchars($dash) ?>?page=layanan" class="btn btn-secondary">Kembali</a>
<a href="<?= htmlspecialchars($dash) ?>?page=layanan_update&id=<?= $id ?>" class="btn btn-warning">Edit</a>
