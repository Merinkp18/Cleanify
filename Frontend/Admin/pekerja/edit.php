<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/employee_logic.php';
require __DIR__ . '/../../../Backend/Logic/upload_logic.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = ($id > 0) ? employee_get($conn, $id) : null;

if (!$row) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    return;
}

// Biar submit tetap kena page=pekerja_edit&id=...
$action = "dashboard.php?page=pekerja_edit&id=" . $id;

if (isset($_POST['submit'])) {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'position' => trim($_POST['position'] ?? ''),
        'status' => $_POST['status'] ?? 'active',
        'rating' => (float)($_POST['rating'] ?? 0),
        'phone' => trim($_POST['phone'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'emergency_contact_name' => trim($_POST['emergency_contact_name'] ?? ''),
        'emergency_contact_phone' => trim($_POST['emergency_contact_phone'] ?? ''),
        'skills' => trim($_POST['skills'] ?? ''),
        'certifications' => trim($_POST['certifications'] ?? ''),
        'total_jobs_completed' => (int)($_POST['total_jobs_completed'] ?? 0),
        'shift_date' => ($_POST['shift_date'] ?? null) ?: null,
    ];

    // upload foto optional
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

        // ✅ PATH BENAR SESUAI STRUKTUR: pekerja/assets/uploads
        $abs = __DIR__ . '/assets/uploads';

        if (!is_dir($abs)) {
            mkdir($abs, 0755, true);
        }

        // upload_image return path/nama file -> kita simpan nama file saja ke DB
        $uploaded = upload_image($_FILES['photo'], $abs, '');
        if ($uploaded) {
            $data['photo'] = basename($uploaded);
        }
    }

    employee_update($conn, $id, $data);

    // Karena halaman ini di-include dashboard.php, JANGAN pakai header()
    echo "<script>window.location='dashboard.php?page=pekerja';</script>";
    exit;
}
?>

<h2 style="color:#0072CF; margin-bottom:20px;">Edit Data Pekerja</h2>

<style>
/* (STYLE TIDAK DIUBAH SAMA SEKALI) */
    .form-table {
        width: 70%;
        background: #ffffff;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .form-table th {
        width: 25%;
        background: #0072CF;
        color: white;
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .form-table td {
        padding: 12px;
        border: 1px solid #ddd;
        background: #f9fbff;
    }

    .form-control {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .btn-cleanify {
        background: #0072CF;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    .btn-cleanify:hover {
        background: #005fa8;
    }

    .btn-secondary {
        padding: 8px 16px;
        border-radius: 6px;
        background: #aaa;
        color: white;
        text-decoration: none;
        margin-left: 8px;
    }

    .btn-secondary:hover {
        background: #888;
    }
</style>

<form method="POST" enctype="multipart/form-data" action="<?= htmlspecialchars($action) ?>">
<table class="form-table">

    <tr>
        <th>Foto</th>
        <td>
            <?php if (!empty($row['photo'])): ?>
                <?php $photo = basename($row['photo']); ?>
                <img src="pekerja/assets/uploads/<?= htmlspecialchars($photo) ?>" width="80" style="border-radius:6px; margin-bottom:8px;">
            <?php endif; ?>
            <br>
            <input type="file" name="photo" class="form-control">
        </td>
    </tr>

    <tr>
        <th>Name</th>
        <td><input type="text" name="name" value="<?= htmlspecialchars($row['name'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Position</th>
        <td><input type="text" name="position" value="<?= htmlspecialchars($row['position'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Status</th>
        <td>
            <select name="status" class="form-control">
                <option value="active" <?= ($row['status'] ?? '')=='active'?'selected':'' ?>>Active</option>
                <option value="inactive" <?= ($row['status'] ?? '')=='inactive'?'selected':'' ?>>Inactive</option>
                <option value="on_leave" <?= ($row['status'] ?? '')=='on_leave'?'selected':'' ?>>On Leave</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Rating</th>
        <td><input type="number" step="0.01" name="rating" value="<?= htmlspecialchars($row['rating'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Phone</th>
        <td><input type="text" name="phone" value="<?= htmlspecialchars($row['phone'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><input type="email" name="email" value="<?= htmlspecialchars($row['email'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Address</th>
        <td><textarea name="address" class="form-control"><?= htmlspecialchars($row['address'] ?? '') ?></textarea></td>
    </tr>

    <tr>
        <th>Emergency Contact Name</th>
        <td><input type="text" name="emergency_contact_name" value="<?= htmlspecialchars($row['emergency_contact_name'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Emergency Contact Phone</th>
        <td><input type="text" name="emergency_contact_phone" value="<?= htmlspecialchars($row['emergency_contact_phone'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Skills</th>
        <td><textarea name="skills" class="form-control"><?= htmlspecialchars($row['skills'] ?? '') ?></textarea></td>
    </tr>

    <tr>
        <th>Certifications</th>
        <td><textarea name="certifications" class="form-control"><?= htmlspecialchars($row['certifications'] ?? '') ?></textarea></td>
    </tr>

    <tr>
        <th>Total Jobs Completed</th>
        <td><input type="number" name="total_jobs_completed" value="<?= htmlspecialchars($row['total_jobs_completed'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Shift Date</th>
        <td><input type="date" name="shift_date" value="<?= htmlspecialchars($row['shift_date'] ?? '') ?>" class="form-control"></td>
    </tr>

</table>

<button class="btn-cleanify" name="submit" type="submit">Update</button>
<a href="dashboard.php?page=pekerja" class="btn-secondary">Kembali</a>

</form>
