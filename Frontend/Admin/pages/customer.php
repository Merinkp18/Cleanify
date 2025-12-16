<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
if (!defined('IN_DASHBOARD')) { header('Location: ../dashboard.php?page=customer'); exit; }

require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/customer_logic.php';

// lu sesuaikan nama function di customer_logic.php
// misal: customer_get_all($conn)
$customers = customer_get_all($conn);
?>

<!-- Paste isi BODY dari customer/customer.php lu mulai dari konten utama (tanpa <html><head><body>) -->
<!-- Contoh render minimal kalau lu belum paste desain lama -->
<div class="page-customer">
  <h1>Customer</h1>

  <a href="dashboard.php?page=customer_tambah">Tambah</a>

  <table border="1" cellpadding="8">
    <tr>
      <th>Nama</th><th>Email</th><th>Phone</th><th>Aksi</th>
    </tr>
    <?php foreach ($customers as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['name'] ?? '') ?></td>
        <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
        <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
        <td>
          <a href="dashboard.php?page=customer_detail&id=<?= (int)$c['id'] ?>">Detail</a> |
          <a href="dashboard.php?page=customer_update&id=<?= (int)$c['id'] ?>">Edit</a> |
          <a href="customer/delete.php?id=<?= (int)$c['id'] ?>" onclick="return confirm('Hapus?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
