<?php
include '../../../Backend/db.php';

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan!");
}

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM customers WHERE id=$id");
$data = mysqli_fetch_assoc($query);

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

        .btn-warning {
            background: #f0ad4e;
            color: white;
        }
    </style>
</head>

<body>

<h2>Detail Customer</h2>

<table class="detail-table">
    <tr>
        <th>Nama</th>
        <td><?= $data['name'] ?></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><?= $data['email'] ?></td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td><?= $data['address'] ?></td>
    </tr>

    <tr>
        <th>Nomor Telepom</th>
        <td><?= $data['phone'] ?></td>
    </tr>

    <tr>
        <th>Kontak</th>
        <td><?= ucfirst($data['contact_preference']) ?></td>
    </tr>

    <tr>
        <th>Tipe Tempat</th>
        <td><?= $data['property_type'] ?></td>
    </tr>

    <tr>
        <th>Ukuran Tempat</th>
        <td><?= $data['property_size_sqm'] ?></td>
    </tr>

    <tr>
        <th>Alamat Lengkap</th>
        <td><?= nl2br($data['full_address']) ?></td>
    </tr>

</table>

<br>

<a href="customer.php" class="btn btn-secondary">Kembali</a>


</body>
</html>
