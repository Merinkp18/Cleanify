<?php
include '../../../Backend/db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM services WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $status = $_POST['status'];
    $short = $_POST['short'];
    $full = $_POST['full'];
    $features = $_POST['features'];
    $not_included = $_POST['not_included'];

    mysqli_query($conn, 
        "UPDATE services SET 
            name='$name',
            category='$category',
            price='$price',
            duration_minutes='$duration',
            status='$status',
            short_description='$short',
            full_description='$full',
            features='$features',
            not_included='$not_included'
        WHERE id=$id"
    );

    header("Location: layanan.php");
    exit;
}
?>

<h2 style="color:#0072CF; margin-bottom:20px;">Edit Layanan</h2>

<style>
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

<form method="POST">
	<table class="form-table">

    <tr>
        <th>Nama Layanan</th>
        <td><input type="text" name="name" value="<?= $data['name'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Kategori</th>
		<td>
        <select name="category" class="form-control">
            <option value="regular" <?= $data['category']=='regular'?'selected':'' ?>>Regular</option>
            <option value="deep_clean" <?= $data['category']=='deep_clean'?'selected':'' ?>>Deep Clean</option>
            <option value="premium" <?= $data['category']=='premium'?'selected':'' ?>>Premium</option>
        </select>
		</td>
    </tr>

    <tr>
        <th>Harga</th>
        <td><input type="number" name="price" value="<?= $data['price'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Durasi</thl>
        <td><input type="number" name="duration" value="<?= $data['duration_minutes'] ?>" class="form-control"></td>
	</tr>	

    <tr>
        <th>Deskripsi Singkat</th>
        <td><input type="text" name="short" value="<?= $data['short_description'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Deskripsi Lengkap</th>
        <td><textarea name="full" class="form-control"><?= $data['full_description'] ?></textarea></td>
    </tr>

    <tr>
        <th>Fitur</th>
        <td><textarea name="features" class="form-control"><?= $data['features'] ?></textarea></td>
    </tr>

    <tr>
        <th>Tidak Termasuk</th>
        <td><textarea name="not_included" class="form-control"><?= $data['not_included'] ?></textarea></td>
    </tr>

    <tr>
        <th>Status</th>
        <td>
		<select name="status" class="form-control">
            <option value="active" <?= $data['status']=='active'?'selected':'' ?>>Aktif</option>
            <option value="inactive" <?= $data['status']=='inactive'?'selected':'' ?>>Tidak Aktif</option>
        </select>
		</td>
    </tr>
	</table>

    <button class="btn btn-cleanify" name="submit">Update</button>
    <a href="layanan.php" class="btn btn-secondary">Kembali</a>

</div>
</form>
