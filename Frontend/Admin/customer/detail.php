<?php
// Proteksi admin
require '../../../Backend/Admin/auth_admin.php';
require '../../../Backend/db.php';

// Validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id < 1) {
    die("ID tidak valid!");
}

// Prepared statement (ANTI SQL INJECTION)
$stmt = $conn->prepare("SELECT * FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    die("Data tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Customer</title>

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
        }

        .btn-secondary {
            background: gray;
            color: white;
        }
    </style>
</head>

<body>

<h2>Detail Customer</h2>

<table class="detail-table">
    <tr>
        <th>Nama</th>
        <td><?= htmlspecialchars($data['name']) ?></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($data['email']) ?></td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td><?= htmlspecialchars($data['address']) ?></td>
    </tr>

    <tr>
        <th>Nomor Telepon</th>
        <td><?= htmlspecialchars($data['phone']) ?></td>
    </tr>

    <tr>
        <th>Kontak</th>
        <td><?= htmlspecialchars($data['contact_preference'] ?? '-') ?></td>
    </tr>

    <tr>
        <th>Tipe Tempat</th>
        <td><?= htmlspecialchars($data['property_type']) ?></td>
    </tr>

    <tr>
        <th>Ukuran Tempat</th>
        <td><?= htmlspecialchars($data['property_size_sqm'] ?? '-') ?></td>
    </tr>

    <tr>
        <th>Alamat Lengkap</th>
        <td><?= nl2br(htmlspecialchars($data['full_address'] ?? '-')) ?></td>
    </tr>

</table>

<br>

<a href="customer.php" class="btn btn-secondary">Kembali</a>

</body>
</html>
