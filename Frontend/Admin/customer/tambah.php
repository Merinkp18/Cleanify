<?php
include '../../../Backend/db.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email= $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $property_type = $_POST['property_type'];
    
    

    $sql = "INSERT INTO customers 
           (name, email, address, phone, property_type, )
           VALUES ('$name','$email','$address','phone','$property_type')";

    mysqli_query($conn, $sql);
    header("Location: customer.php");
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
            <th>Nama Customer</th>
            <td><input type="text" name="name" class="form-control" required></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><input type="text" name="email" class="form-control" required></td>
        </tr>

        <tr>
            <th>Alamat</th>
            <td><input type="text" name="address" class="form-control" required></td>
        </tr>

        <tr>
            <th>Nomor Telepon</th>
            <td><input type="number" name="phone" class="form-control" required></td>
        </tr>

        <tr>
            <th>Tipe Tempat</th>
            <td><input type="text" name="property_type" class="form-control" required></td>
        </tr>

        

    </table>

    <button type="submit" name="submit" class="btn-cleanify">Simpan</button>
    <a href="customer.php" class="btn-secondary">Kembali</a>

</form>
