<?php
include '../../../Backend/db.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM services WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Layanan</title>

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

        .btn-warning {
            background: #f0ad4e;
            color: white;
        }
    </style>
</head>

<body>

<h2>Detail Layanan</h2>

<table class="detail-table">
    <tr>
        <th>Nama</th>
        <td><?= $data['name'] ?></td>
    </tr>

    <tr>
        <th>Kategori</th>
        <td><?= $data['category'] ?></td>
    </tr>

    <tr>
        <th>Harga</th>
        <td>Rp <?= number_format($data['price'], 0, ",", ".") ?></td>
    </tr>

    <tr>
        <th>Durasi</th>
        <td><?= $data['duration_minutes'] ?> menit</td>
    </tr>

    <tr>
        <th>Status</th>
        <td><?= ucfirst($data['status']) ?></td>
    </tr>

    <tr>
        <th>Deskripsi Singkat</th>
        <td><?= $data['short_description'] ?></td>
    </tr>

    <tr>
        <th>Deskripsi Lengkap</th>
        <td><?= nl2br($data['full_description']) ?></td>
    </tr>

    <tr>
        <th>Fitur</th>
        <td><?= nl2br($data['features']) ?></td>
    </tr>

    <tr>
        <th>Tidak Termasuk</th>
        <td><?= nl2br($data['not_included']) ?></td>
    </tr>
</table>

<br>

<a href="layanan.php" class="btn btn-secondary">Kembali</a>


</body>
</html>
