<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/service_logic.php';

$search = trim($_GET['search'] ?? '');
$services = services_list($conn, $search); // NOTE: logic lu harus support search
?>

<div class="cleanify-card">
  <h2>Kelola Layanan</h2>

  <div class="top-bar">
    <div class="search">
      <form method="GET" action="dashboard.php" style="display:inline;">
        <input type="hidden" name="page" value="layanan">
        <input type="text" name="search" placeholder="Cari nama layanan..."
               class="search-input"
               value="<?= htmlspecialchars($search) ?>">
      </form>
    </div>

    <div class="add-btn">
      <a href="dashboard.php?page=layanan_create" class="btn btn-success">Tambah Data</a>
    </div>
  </div>

  <table class="cleanify-table">
    <thead>
      <tr>
        <th>Nama Layanan</th>
        <th>Deskripsi</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Durasi</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>

    <tbody>
      <?php if ($services && count($services) > 0): ?>
        <?php foreach ($services as $row): ?>
          <tr>
            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['short_description'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['category'] ?? '') ?></td>
            <td>Rp <?= number_format((float)($row['price'] ?? 0), 0, ",", ".") ?></td>
            <td><?= (int)($row['duration_minutes'] ?? 0) ?> menit</td>
            <td><?= htmlspecialchars(ucfirst($row['status'] ?? '')) ?></td>

            <td class="td-aksi">
              <a href="dashboard.php?page=layanan_detail&id=<?= (int)$row['id'] ?>" class="btn btn-info">Detail</a>
              <a href="dashboard.php?page=layanan_update&id=<?= (int)$row['id'] ?>" class="btn btn-warning">Edit</a>
              <a href="layanan/delete.php?id=<?= (int)$row['id'] ?>&return=layanan"
                 onclick="return confirm('Hapus layanan ini?');"
                 class="btn btn-danger">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">Data layanan tidak tersedia.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
