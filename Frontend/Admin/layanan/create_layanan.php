<?php
include '../../../Backend/db.php';

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

    $sql = "INSERT INTO services 
           (name, category, price, duration_minutes, status, short_description, full_description, features, not_included)
           VALUES ('$name','$category','$price','$duration','$status','$short','$full','$features','$not_included')";

    mysqli_query($conn, $sql);
    header("Location: layanan.php");
    exit;
}
?>

<h2 style="color:#0072CF; margin-bottom:20px;">Tambah Layanan</h2>

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
            <td><input type="text" name="name" class="form-control" required></td>
        </tr>

        <tr>
            <th>Kategori</th>
            <td>
                <select name="category" class="form-control">
                    <option value="regular">Regular</option>
                    <option value="deep_clean">Deep Clean</option>
                    <option value="premium">Premium</option>
                </select>
            </td>
        </tr>

        <tr>
            <th>Harga</th>
            <td><input type="number" name="price" class="form-control" required></td>
        </tr>

        <tr>
            <th>Durasi (menit)</th>
            <td><input type="number" name="duration" class="form-control" required></td>
        </tr>

        <tr>
            <th>Deskripsi Singkat</th>
            <td><input type="text" name="short" class="form-control" required></td>
        </tr>

        <tr>
            <th>Deskripsi Lengkap</th>
            <td><textarea name="full" class="form-control" rows="3"></textarea></td>
        </tr>

        <tr>
            <th>Fitur</th>
            <td><textarea name="features" class="form-control" rows="3"></textarea></td>
        </tr>

        <tr>
            <th>Tidak Termasuk</th>
            <td><textarea name="not_included" class="form-control" rows="3"></textarea></td>
        </tr>

        <tr>
            <th>Status</th>
            <td>
                <select name="status" class="form-control">
                    <option value="active">Aktif</option>
                    <option value="inactive">Tidak Aktif</option>
                </select>
            </td>
        </tr>

    </table>

    <button type="submit" name="submit" class="btn-cleanify">Simpan</button>
    <a href="layanan.php" class="btn-secondary">Kembali</a>

</form>
