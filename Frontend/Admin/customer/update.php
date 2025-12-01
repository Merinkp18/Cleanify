<?php
include '../../../Backend/db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM customers WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $property_type = $_POST['property_type'];
    

    mysqli_query($conn, 
        "UPDATE services SET 
            name='$name',
            email='$email',
            address='$address',
            phone='$phone',
            property_type='$property_type',
            
        WHERE id=$id"
    );

    header("Location: customer.php");
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
        <th>Nama Customer</th>
        <td><input type="text" name="name" value="<?= $data['name'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Email</th>
        <td><input type="text" name="email" value="<?= $data['email'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Alamat</th>
        <td><input type="text" name="address" value="<?= $data['address'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Nomor Telepon</thl>
        <td><input type="number" name="phone" value="<?= $data['phone'] ?>" class="form-control"></td>
	</tr>	

    <tr>
        <th>Tipe Tempat</th>
        <td><input type="text" name="property_type" value="<?= $data['property_type'] ?>" class="form-control"></td>
    </tr>

    
	</table>

    <button class="btn btn-cleanify" name="submit">Update</button>
    <a href="customer.php" class="btn btn-secondary">Kembali</a>

</div>
</form>
