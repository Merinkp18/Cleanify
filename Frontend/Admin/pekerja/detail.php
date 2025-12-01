<?php
include '../../../Backend/db.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = $_GET['id'];

// Ambil data employee berdasarkan ID
$sql = "SELECT * FROM employees WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Data tidak ditemukan.";
    exit;
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pekerja</title>
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

<h2>Detail Pekerja</h2>
	<table class="detail-table">

    <tr>
        <th>Photo</th>
        <td>
            <?php if (!empty($row['photo'])): ?>
                <img src="../asset/<?= $row['photo']; ?>" width="120" height="120" style="border-radius:10px; object-fit:cover;">
            <?php else: ?>
                <i>Tidak ada foto</i>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <th>Name</th>
        <td><?= $row['name']; ?></td>
    </tr>

    <tr>
        <th>Position</th>
        <td><?= $row['position']; ?></td>
    </tr>

    <tr>
        <th>Status</th>
        <td><?= $row['status']; ?></td>
    </tr>

    <tr>
        <th>Rating</th>
        <td><?= $row['rating']; ?></td>
    </tr>

    <tr>
        <th>Phone</th>
        <td><?= $row['phone']; ?></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><?= $row['email']; ?></td>
    </tr>

    <tr>
        <th>Address</th>
        <td><?= $row['address']; ?></td>
    </tr>

    <tr>
        <th>Emergency Contact Name</th>
        <td><?= $row['emergency_contact_name']; ?></td>
    </tr>

    <tr>
        <th>Emergency Contact Phone</th>
        <td><?= $row['emergency_contact_phone']; ?></td>
    </tr>

    <tr>
        <th>Skills</th>
        <td><?= $row['skills']; ?></td>
    </tr>

    <tr>
        <th>Certifications</th>
        <td><?= $row['certifications']; ?></td>
    </tr>

    <tr>
        <th>Total Jobs Completed</th>
        <td><?= $row['total_jobs_completed']; ?></td>
    </tr>

    <tr>
        <th>Shift Date</th>
        <td><?= $row['shift_date']; ?></td>
    </tr>

</table>


    <br>
   <a href="pekerja.php" class="btn btn-secondary">Kembali</a>


</body>
</html>
