<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/order_logic.php';

function to_datetime_local($v): string {
    if (!$v) return '';
    // kalau dari DB: "YYYY-MM-DD HH:MM:SS" -> "YYYY-MM-DDTHH:MM"
    // kalau udah ada "T", aman.
    $v = trim((string)$v);
    if (strpos($v, 'T') !== false) return substr($v, 0, 16);
    return str_replace(' ', 'T', substr($v, 0, 16));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$row = ($id > 0) ? order_get($conn, $id) : null;

if (!$row) {
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    return;
}

if (isset($_POST['submit'])) {
    $data = [
        'customer_id' => (int)($_POST['customer_id'] ?? 0),
        'order_code' => trim($_POST['order_code'] ?? ''),
        'order_date' => ($_POST['order_date'] ?? null) ?: null,
        'total_cost' => (float)($_POST['total_cost'] ?? 0),
        'payment_proof' => trim($_POST['payment_proof'] ?? ''),
        'status' => trim($_POST['status'] ?? ''),
        'payment_confirmed_at' => ($_POST['payment_confirmed_at'] ?? null) ?: null,
    ];

    order_update_full($conn, $id, $data);

    // FILE INI DI-INCLUDE DARI dashboard.php, JANGAN PAKE header()
    echo "<script>window.location='dashboard.php?page=order';</script>";
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

<form method="POST" action="dashboard.php?page=order_edit&id=<?= (int)$id ?>">
<table class="form-table">

    <tr>
        <th>Customer ID</th>
        <td><input type="number" name="customer_id" value="<?= (int)($row['customer_id'] ?? 0) ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Kode Order</th>
        <td><input type="text" name="order_code" value="<?= htmlspecialchars($row['order_code'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Tanggal Order</th>
        <td><input type="datetime-local" name="order_date" value="<?= htmlspecialchars(to_datetime_local($row['order_date'] ?? '')) ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Harga</th>
        <td><input type="number" name="total_cost" value="<?= htmlspecialchars((string)($row['total_cost'] ?? 0)) ?>" class="form-control"></td>
    </tr>

    <tr>
        <th>Status Pembayaran</th>
        <td>
            <select name="payment_proof" class="form-control">
                <option value="transfer" <?= (($row['payment_proof'] ?? '')==='transfer'?'selected':'') ?>>Transfer</option>
                <option value="dp" <?= (($row['payment_proof'] ?? '')==='dp'?'selected':'') ?>>DP</option>
                <option value="selesai" <?= (($row['payment_proof'] ?? '')==='selesai'?'selected':'') ?>>Selesai</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Status Order</th>
        <td>
            <select name="status" class="form-control">
                <option value="baru" <?= (($row['status'] ?? '')==='baru'?'selected':'') ?>>Baru</option>
                <option value="dikonfirmasi" <?= (($row['status'] ?? '')==='dikonfirmasi'?'selected':'') ?>>Dikonfirmasi</option>
                <option value="dalam_proses" <?= (($row['status'] ?? '')==='dalam_proses'?'selected':'') ?>>Dalam Proses</option>
                <option value="selesai" <?= (($row['status'] ?? '')==='selesai'?'selected':'') ?>>Selesai</option>
                <option value="dibatalkan" <?= (($row['status'] ?? '')==='dibatalkan'?'selected':'') ?>>Dibatalkan</option>
            </select>
        </td>
    </tr>

    <tr>
        <th>Tanggal Pembayaran</th>
        <td>
            <input type="datetime-local" name="payment_confirmed_at" value="<?= htmlspecialchars(to_datetime_local($row['payment_confirmed_at'] ?? '')) ?>" class="form-control">
        </td>
    </tr>

</table>

<button class="btn-cleanify" name="submit" type="submit">Update</button>
<a href="dashboard.php?page=order" class="btn-secondary">Kembali</a>

</form>
