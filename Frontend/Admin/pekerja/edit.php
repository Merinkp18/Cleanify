<?php
include '../../../Backend/db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM employees WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $position = $_POST['position'];
    $status = $_POST['status'];
    $rating = $_POST['rating'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $emergency_name = $_POST['emergency_contact_name'];
    $emergency_phone = $_POST['emergency_contact_phone'];
    $skills = $_POST['skills'];
    $certifications = $_POST['certifications'];
    $jobs = $_POST['total_jobs_completed'];
    $shift = $_POST['shift_date'];

    // --- HANDLE FOTO ---
    $fotoBaru = $_FILES['photo']['name'];
    $fotoLama = $data['photo'];

    if ($fotoBaru != "") {
        $targetDir = "../asset/";
        $fileName = time() . "_" . basename($fotoBaru);
        $targetFile = $targetDir . $fileName;

        // Upload file
        move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile);
        $finalPhoto = $fileName;
    } else {
        // Jika tidak upload foto baru
        $finalPhoto = $fotoLama;
    }

    mysqli_query($conn,
        "UPDATE employees SET 
            name='$name',
            position='$position',
            status='$status',
            rating='$rating',
            phone='$phone',
            email='$email',
            address='$address',
            emergency_contact_name='$emergency_name',
            emergency_contact_phone='$emergency_phone',
            skills='$skills',
            certifications='$certifications',
            total_jobs_completed='$jobs',
            shift_date='$shift',
            photo='$finalPhoto'
        WHERE id=$id"
    );

    header("Location: pekerja.php");
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

<form method="POST" enctype="multipart/form-data">
<table class="form-table">

    <tr>
        <th>Foto</th>
        <td>
            <?php if (!empty($data['photo'])): ?>
                <img src="../asset/<?= $data['photo'] ?>" width="80" style="border-radius:6px; margin-bottom:8px;">
            <?php endif; ?>
            <br>
            <input type="file" name="photo" class="form-control">
        </td>
    </tr>

    <tr>
        <th>Name</th>
        <td><input type="text" name="name" value="<?= $data['name'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Position</th>
        <td><input type="text" name="position" value="<?= $data['position'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Status</th>
        <td>
            <select name="status" class="form-control">
                <option value="active" <?= $data['status']=='active'?'selected':'' ?>>Active</option>
                <option value="inactive" <?= $data['status']=='inactive'?'selected':'' ?>>Inactive</option>
                <option value="on_leave" <?= $data['status']=='on_leave'?'selected':'' ?>>On Leave</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Rating</th>
        <td><input type="number" step="0.01" name="rating" value="<?= $data['rating'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Phone</th>
        <td><input type="text" name="phone" value="<?= $data['phone'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><input type="email" name="email" value="<?= $data['email'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Address</th>
        <td><textarea name="address" class="form-control"><?= $data['address'] ?></textarea></td>
    </tr>

    <tr>
        <th>Emergency Contact Name</th>
        <td><input type="text" name="emergency_contact_name" value="<?= $data['emergency_contact_name'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Emergency Contact Phone</th>
        <td><input type="text" name="emergency_contact_phone" value="<?= $data['emergency_contact_phone'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Skills</th>
        <td><textarea name="skills" class="form-control"><?= $data['skills'] ?></textarea></td>
    </tr>

    <tr>
        <th>Certifications</th>
        <td><textarea name="certifications" class="form-control"><?= $data['certifications'] ?></textarea></td>
    </tr>

    <tr>
        <th>Total Jobs Completed</th>
        <td><input type="number" name="total_jobs_completed" value="<?= $data['total_jobs_completed'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Shift Date</th>
        <td><input type="date" name="shift_date" value="<?= $data['shift_date'] ?>" class="form-control"></td>
    </tr>

</table>

<button class="btn-cleanify" name="submit">Update</button>
<a href="pekerja.php" class="btn-secondary">Kembali</a>

</form>
