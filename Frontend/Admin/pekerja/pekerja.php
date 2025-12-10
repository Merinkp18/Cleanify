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

<style>
  /* SCOPE KHUSUS PEKERJA: BIAR GAK NABRAK PAGE LAIN */
  .pekerja-page .cleanify-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    margin: 20px auto;
    width: 90%;
  }

  .pekerja-page .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    gap: 12px;
  }

  .pekerja-page .search-input {
    padding: 6px 10px;
    border-radius: 50px;
    border: 1px solid #ccc;
    width: 250px;
  }

  .pekerja-page .table-wrap { width: 100%; overflow-x: auto; }

  .pekerja-page .table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    margin: 0;
  }

  /* header tabel ikut gaya dashboard lu (biru) */
  .pekerja-page .table thead th {
    background-color: #0072CF !important;
    color: #fff !important;
    padding: 10px !important;
    font-weight: bold;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.25) !important;
    white-space: nowrap;
    vertical-align: middle;
  }

  .pekerja-page .table td {
    padding: 10px !important;
    background: #e3f2ff !important;
    border: 1px solid #cfd8e3 !important;
    vertical-align: middle;
    text-align: center;
  }

  .pekerja-page .td-aksi { text-align: center; white-space: nowrap; }
  .pekerja-page .td-aksi a { display: inline-block; margin: 3px; }

  .pekerja-page .btn {
    padding: 6px 12px !important;
    border-radius: 6px !important;
    font-size: 14px !important;
    text-decoration: none !important;
    border: none !important;
  }
  .pekerja-page .btn-success { background: #0072CF !important; color: white !important; }
  .pekerja-page .btn-info { background: #17a2b8 !important; color: white !important; }
  .pekerja-page .btn-warning { background: #ffc107 !important; color: black !important; }
  .pekerja-page .btn-danger { background: #dc3545 !important; color: white !important; }

  /* PAGINATION DI-SCOPE -> FIX UTAMA BIAR GAK NGEGANGGU PAGE LAIN */
  .pekerja-page .pagination {
    list-style: none;
    padding-left: 0;
    margin: 16px 0 0;
    display: flex;
    gap: 8px;
    align-items: center;
  }
  .pekerja-page .pagination .page-item { list-style: none; }
  .pekerja-page .pagination .page-link {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 8px;
    text-decoration: none;
    border: 1px solid #cfe6ff;
    background: #fff;
    color: #0072CF;
    font-weight: 600;
  }
  .pekerja-page .pagination .page-item.active .page-link {
    background: #0072CF;
    border-color: #0072CF;
    color: #fff;
  }
  .pekerja-page .pagination .page-link:hover { background: #e3f2ff; }
  .pekerja-page .pagination .page-item.disabled .page-link {
    opacity: .5;
    pointer-events: none;
  }
</style>

<div class="pekerja-page">
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

    <div class="table-wrap">
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
          <?php if (!empty($employees)): ?>
            <?php foreach ($employees as $row): ?>
              <tr>
                <td>
                  <?php if (!empty($row['photo'])): ?>
                    <?php $photo = basename((string)$row['photo']); ?>
                    <img
                      src="pekerja/assets/uploads/<?= rawurlencode($photo) ?>"
                      width="60" height="60" style="border-radius:8px;object-fit:cover;"
                      alt="Foto pekerja"
                    >
                  <?php else: ?>
                    -
                  <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($row['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['position'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['status'] ?? '') ?></td>
                <td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
                <td>⭐ <?= htmlspecialchars((string)($row['rating'] ?? '')) ?></td>
                <td><?= htmlspecialchars($row['skills'] ?? '') ?></td>

                <td class="td-aksi">
                  <a href="dashboard.php?page=pekerja_detail&id=<?= (int)$row['id'] ?>" class="btn btn-info btn-sm">Detail</a>
                  <a href="dashboard.php?page=pekerja_edit&id=<?= (int)$row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>

                  <!-- paling aman ikut pola layanan/jadwal: delete via file langsung -->
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
    </div>

    <?php if ($totalPages > 1): ?>
      <?php
        $base = "dashboard.php?page=pekerja";
        $prev = max(1, $p - 1);
        $next = min($totalPages, $p + 1);
      ?>
      <nav aria-label="Pagination pekerja">
        <ul class="pagination">
          <li class="page-item <?= ($p <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $base ?>&p=<?= $prev ?>">&laquo;</a>
          </li>

          <?php for ($i=1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i === $p) ? 'active' : '' ?>">
              <a class="page-link" href="<?= $base ?>&p=<?= $i ?>"><?= $i ?></a>
            </li>
          <?php endfor; ?>

          <li class="page-item <?= ($p >= $totalPages) ? 'disabled' : '' ?>">
            <a class="page-link" href="<?= $base ?>&p=<?= $next ?>">&raquo;</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>

  </div>
</div>
