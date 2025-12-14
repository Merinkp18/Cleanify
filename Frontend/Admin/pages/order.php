<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
if (!defined('IN_DASHBOARD')) { header('Location: ../dashboard.php?page=order'); exit; }

require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/order_logic.php';

$orders = order_get_all($conn); // sesuaikan
?>

<div class="page-order">
  <h1>Order</h1>

  <table border="1" cellpadding="8">
    <tr>
      <th>ID</th><th>Customer</th><th>Service</th><th>Status</th><th>Total</th><th>Aksi</th>
    </tr>
    <?php foreach ($orders as $o): ?>
      <tr>
        <td><?= (int)($o['id'] ?? 0) ?></td>
        <td><?= htmlspecialchars($o['customer_name'] ?? '') ?></td>
        <td><?= htmlspecialchars($o['service_name'] ?? '') ?></td>
        <td><?= htmlspecialchars($o['status'] ?? '') ?></td>
        <td><?= htmlspecialchars($o['total_cost'] ?? '') ?></td>
        <td>
          <a href="dashboard.php?page=order_detail&id=<?= (int)$o['id'] ?>">Detail</a> |
          <a href="dashboard.php?page=order_update&id=<?= (int)$o['id'] ?>">Edit</a> |
          <a href="order/delete.php?id=<?= (int)$o['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
