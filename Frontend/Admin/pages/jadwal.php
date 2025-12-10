<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
if (!defined('IN_DASHBOARD')) { header('Location: ../dashboard.php?page=jadwal'); exit; }

require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/schedule_logic.php';

$schedules = schedule_get_all($conn); // sesuaikan
?>

<div class="page-jadwal">
  <h1>Jadwal</h1>
  <a href="dashboard.php?page=jadwal_tambah">Tambah</a>

  <table border="1" cellpadding="8">
    <tr>
      <th>Tanggal</th><th>Mulai</th><th>Selesai</th><th>Pekerja</th><th>Order</th><th>Aksi</th>
    </tr>
    <?php foreach ($schedules as $s): ?>
      <tr>
        <td><?= htmlspecialchars($s['schedule_date'] ?? '') ?></td>
        <td><?= htmlspecialchars($s['time_slot_start'] ?? '') ?></td>
        <td><?= htmlspecialchars($s['time_slot_end'] ?? '') ?></td>
        <td><?= htmlspecialchars($s['employee_name'] ?? '') ?></td>
        <td><?= htmlspecialchars($s['order_id'] ?? '') ?></td>
        <td>
          <a href="dashboard.php?page=jadwal_update&id=<?= (int)$s['id'] ?>">Edit</a> |
          <a href="jadwal/delete.php?id=<?= (int)$s['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
