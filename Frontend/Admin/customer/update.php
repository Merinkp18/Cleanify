<?php
// Proteksi admin
require '../../../Backend/Admin/auth_admin.php';
require '../../../Backend/db.php';

// Validasi ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<h3>ID tidak valid.</h3>";
    exit;
}

// Ambil data customer
$stmt = $conn->prepare("SELECT name, email, address, phone, property_type FROM customers WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<h3>Data customer tidak ditemukan.</h3>";
    exit;
}

// Jika form disubmit
if (isset($_POST['submit'])) {

    $name          = trim($_POST['name']);
    $email         = trim($_POST['email']);
    $address       = trim($_POST['address']);
    $phone         = trim($_POST['phone']);
    $property_type = trim($_POST['property_type']);

    // Prepared statement update
    $stmt = $conn->prepare(
        "UPDATE customers SET 
            name = ?, 
            email = ?, 
            address = ?, 
            phone = ?, 
            property_type = ?
        WHERE id = ?"
    );

    $stmt->bind_param("sssssi", $name, $email, $address, $phone, $property_type, $id);
    $stmt->execute();

    header("Location: customer.php");
    exit;
}
?>

<h2 style="color:#0072CF; margin-bottom:20px;">Edit Customer</h2>

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
            <td><input type="text" name="name" 
                value="<?= htmlspecialchars($data['name']); ?>" 
                class="form-control" required></td>
        </tr>

        <tr>
            <th>Email</th>
            <td><input type="email" name="email" 
                value="<?= htmlspecialchars($data['email']); ?>" 
                class="form-control" required></td>
        </tr>

        <tr>
            <th>Alamat</th>
            <td><input type="text" name="address" 
                value="<?= htmlspecialchars($data['address']); ?>" 
                class="form-control" required></td>
        </tr>

        <tr>
            <th>Nomor Telepon</th>
            <td><input type="text" name="phone" 
                value="<?= htmlspecialchars($data['phone']); ?>" 
                class="form-control" required></td>
        </tr>

        <tr>
            <th>Tipe Tempat</th>
            <td><input type="text" name="property_type" 
                value="<?= htmlspecialchars($data['property_type']); ?>" 
                class="form-control" required></td>
        </tr>

    </table>

    <button class="btn-cleanify" name="submit">Update</button>
    <a href="customer.php" class="btn-secondary">Kembali</a>
</form>
