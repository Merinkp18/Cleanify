<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/employee_logic.php';

$limit = 5;
$p = max(1, (int)($_GET['p'] ?? 1));
$start = ($p - 1) * $limit;

$employees = employees_list($conn, $limit, $start);
$total = employees_count($conn);
$totalPages = max(1, (int)ceil($total / $limit));
?>

<div class="cleanify-card">
  <h2>Kelola Pekerja</h2>

  <div class="top-bar">
    <div class="search">
      <input type="text" id="searchEmployee" placeholder="Cari nama pekerja..." class="search-input">
    </div>

    <div class="add-btn">
      <a href="dashboard.php?page=pekerja_tambah" class="btn btn-success">Tambah Data</a>
    </div>
  </div>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Foto</th>
        <th>Nama Pekerja</th>
        <th>Posisi</th>
        <th>Status</th>
        <th>Nomor Telepon</th>
        <th>Rating</th>
        <th>Skill</th>
        <th>Aksi</th>
      </tr>
    </thead>

    <tbody>
      <?php if ($employees): ?>
        <?php foreach ($employees as $row): ?>
          <tr>
            <td>
              <?php if (!empty($row['photo'])): ?>
                <img
                  src="assets/uploads/employees/<?= htmlspecialchars($row['photo']) ?>"
                  width="60" height="60" style="border-radius:8px;object-fit:cover;"
                  alt="Foto pekerja"
                >
              <?php else: ?>-
              <?php endif; ?>
            </td>

            <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['position'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
            <td>⭐ <?= htmlspecialchars($row['rating'] ?? '') ?></td>
            <td><?= htmlspecialchars($row['skills'] ?? '') ?></td>

            <td class="td-aksi">
              <a href="dashboard.php?page=pekerja_detail&id=<?= (int)$row['id'] ?>" class="btn btn-info btn-sm">Detail</a>
              <a href="dashboard.php?page=pekerja_edit&id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="pekerja/delete.php?id=<?= (int)$row['id'] ?>&return=pekerja"
                 onclick="return confirm('Hapus pekerja ini?');"
                 class="btn btn-danger btn-sm">Hapus</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="8" class="text-center">Data pekerja tidak tersedia.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <?php if ($totalPages > 1): ?>
    <div style="margin-top:12px;display:flex;gap:8px;align-items:center;">
      <?php for ($i=1; $i<=$totalPages; $i++): ?>
        <a class="btn <?= $i===$p ? 'btn-info' : 'btn-success' ?>"
           href="dashboard.php?page=pekerja&p=<?= $i ?>">
          <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
</div>
