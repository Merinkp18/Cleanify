<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/employee_logic.php';

// dashboard path dinamis (biar aman project beda-beda)
$dash = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/dashboard.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = ($id > 0) ? employee_get($conn, $id) : null;

if (!$row) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    echo "<a href='".htmlspecialchars($dash, ENT_QUOTES, 'UTF-8')."?page=pekerja' class='btn btn-secondary'>Kembali</a>";
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

    .btn-warning {
        background: #f0ad4e;
        color: white;
    }
</style>

<h2>Detail Pekerja</h2>

<table class="detail-table">
    <tr>
        <th>Photo</th>
        <td>
            <?php if (!empty($row['photo'])): ?>
                <img src="pekerja/assets/uploads/<?= esc($row['photo']); ?>" width="120" height="120" style="border-radius:10px; object-fit:cover;">
            <?php else: ?>
                <i>Tidak ada foto</i>
            <?php endif; ?>
        </td>
    </tr>

    <tr><th>Name</th><td><?= esc($row['name'] ?? '-') ?></td></tr>
    <tr><th>Position</th><td><?= esc($row['position'] ?? '-') ?></td></tr>
    <tr><th>Status</th><td><?= esc($row['status'] ?? '-') ?></td></tr>
    <tr><th>Rating</th><td><?= esc($row['rating'] ?? '-') ?></td></tr>
    <tr><th>Phone</th><td><?= esc($row['phone'] ?? '-') ?></td></tr>
    <tr><th>Email</th><td><?= esc($row['email'] ?? '-') ?></td></tr>
    <tr><th>Address</th><td><?= esc($row['address'] ?? '-') ?></td></tr>
    <tr><th>Emergency Contact Name</th><td><?= esc($row['emergency_contact_name'] ?? '-') ?></td></tr>
    <tr><th>Emergency Contact Phone</th><td><?= esc($row['emergency_contact_phone'] ?? '-') ?></td></tr>
    <tr><th>Skills</th><td><?= esc($row['skills'] ?? '-') ?></td></tr>
    <tr><th>Certifications</th><td><?= esc($row['certifications'] ?? '-') ?></td></tr>
    <tr><th>Total Jobs Completed</th><td><?= esc($row['total_jobs_completed'] ?? '0') ?></td></tr>
    <tr><th>Shift Date</th><td><?= esc($row['shift_date'] ?? '-') ?></td></tr>
</table>

<br>

<a href="<?= esc($dash) ?>?page=pekerja" class="btn btn-secondary">Kembali</a>
<a href="<?= esc($dash) ?>?page=pekerja_edit&id=<?= (int)$id ?>" class="btn btn-warning">Edit</a>
