<?php
include '../../../Backend/db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM orders WHERE id=$id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data tidak ditemukan!");
}

if (isset($_POST['submit'])) {

    $customer_id = $_POST['customer_id'];
    $order_code = $_POST['order_code'];
    $order_date = $_POST['order_date'];
    $total_cost = $_POST['total_cost'];
    $payment_proof = $_POST['payment_proof'];
    $status = $_POST['status'];
    $payment_confirmed_at = $_POST['payment_confirmed_at'];

    mysqli_query($conn, 
        "UPDATE orders SET
            customer_id='$customer_id',
            order_code='$order_code',
            order_date='$order_date',
            total_cost='$total_cost',
            payment_proof='$payment_proof',
            status='$status',
            payment_confirmed_at='$payment_confirmed_at'
        WHERE id=$id"
    );

    header("Location: order.php");
    exit;
}
?>

<h2 style="color:#0072CF; margin-bottom:20px;">Edit Order</h2>

<style>
    .form-table {
        width: 70%;
        background: #ffffff;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .form-table th {
        width: 30%;
        background: #0072CF;
        color: white;
        padding: 12px;
        border: 1px solid #ddd;
    }
    .form-table td {
        padding: 12px;
        border: 1px solid #ddd;
        background: #f4f8ff;
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
        margin-top: 10px;
    }
    .btn-secondary {
        padding: 8px 16px;
        border-radius: 6px;
        background: #aaa;
        color: white;
        text-decoration: none;
        margin-left: 8px;
    }
</style>

<form method="POST">
<table class="form-table">

    <tr>
        <th>Customer ID</th>
        <td><input type="number" name="customer_id" value="<?= $data['customer_id'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Kode Order</th>
        <td><input type="text" name="order_code" value="<?= $data['order_code'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Tanggal Order</th>
        <td><input type="datetime-local" name="order_date" value="<?= $data['order_date'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Harga</th>
        <td><input type="number" name="total_cost" value="<?= $data['total_cost'] ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Status Pembayaran</th>
        <td>
            <select name="payment_proof" class="form-control">
                <option value="transfer" <?= $data['payment_proof']=='transfer'?'selected':'' ?>>Transfer</option>
                <option value="dp" <?= $data['payment_proof']=='dp'?'selected':'' ?>>DP</option>
                <option value="selesai" <?= $data['payment_proof']=='selesai'?'selected':'' ?>>Selesai</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Status Order</th>
        <td>
            <select name="status" class="form-control">
                <option value="baru" <?= $data['status']=='baru'?'selected':'' ?>>Baru</option>
                <option value="dikonfirmasi" <?= $data['status']=='dikonfirmasi'?'selected':'' ?>>Dikonfirmasi</option>
                <option value="dalam_proses" <?= $data['status']=='dalam_proses'?'selected':'' ?>>Dalam Proses</option>
                <option value="selesai" <?= $data['status']=='selesai'?'selected':'' ?>>Selesai</option>
                <option value="dibatalkan" <?= $data['status']=='dibatalkan'?'selected':'' ?>>Dibatalkan</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Tanggal Pembayaran</th>
        <td>
            <input type="datetime-local" name="payment_confirmed_at" value="<?= $data['payment_confirmed_at'] ?>" class="form-control">
        </td>
    </tr>

</table>

<button class="btn-cleanify" name="submit">Update</button>
<a href="order.php" class="btn-secondary">Kembali</a>

</form>
