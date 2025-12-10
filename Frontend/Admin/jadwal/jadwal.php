<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/schedule_logic.php';

$limit = 5;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$p = max(1, $p);
$start = ($p - 1) * $limit;

$schedules = schedules_list($conn, $limit, $start);
$total = schedules_count($conn);
$totalPages = max(1, (int)ceil($total / $limit));
?>

<!-- CSS DI-SCOPE KHUSUS HALAMAN JADWAL, BIAR GAK NABRAK PAGE LAIN -->
<style>
  .jadwal-page .cleanify-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    margin: 20px auto;
    width: 90%;
  }

  .jadwal-page .cleanify-card h2{
    margin: 0 0 18px 0;
    font-weight: 800;
  }

  .jadwal-page .table-wrap{
    width: 100%;
    overflow-x: auto;
  }

  .jadwal-page .table{
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    margin: 0;
  }

  .jadwal-page .table thead th{
    background-color: #0072CF !important;
    color: #fff !important;
    padding: 10px !important;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
    border: 1px solid rgba(255,255,255,0.25) !important;
    white-space: nowrap;
  }

  .jadwal-page .table td{
    padding: 10px !important;
    background: #e3f2ff !important;
    border: 1px solid #cfd8e3 !important;
    color: #111;
    vertical-align: middle;
    text-align: center;
  }

  .jadwal-page .td-aksi{
    white-space: nowrap;
    text-align: center;
  }
  .jadwal-page .td-aksi a{
    display: inline-block;
    margin: 3px;
  }

  /* tombol kecil biar konsisten */
  .jadwal-page .btn-danger{
    background: #dc3545 !important;
    color: #fff !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    font-weight: 600;
    text-decoration: none !important;
  }
</style>

<div class="jadwal-page">
  <div class="cleanify-card">
    <h2>Kelola Jadwal</h2>

    <div class="table-wrap">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Jadwal</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
            <th>Status</th>
            <th>Catatan</th>
            <th>Aksi</th>
          </tr>
        </thead>

        <tbody>
          <?php if (!empty($schedules)): ?>
            <?php foreach ($schedules as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['schedule_date'] ?? ''); ?></td>
                <td><?= htmlspecialchars($row['time_slot_start'] ?? ''); ?></td>
                <td><?= htmlspecialchars($row['time_slot_end'] ?? ''); ?></td>
                <td><?= htmlspecialchars($row['status'] ?? ''); ?></td>
                <td><?= htmlspecialchars($row['notes'] ?? ''); ?></td>

                <td class="td-aksi">
                  <a
                    href="jadwal/delete.php?id=<?= (int)($row['id'] ?? 0); ?>&return=jadwal"
                    onclick="return confirm('Hapus jadwal ini?');"
                    class="btn-danger"
                  >Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">Data jadwal tidak tersedia.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if ($totalPages > 1): ?>
      <style>
        .jadwal-page .pagination{
          list-style:none;
          padding-left:0;
          margin:16px 0 0;
          display:flex;
          gap:8px;
          align-items:center;
          justify-content:flex-start;
        }
        .jadwal-page .pagination .page-link{
          display:inline-block;
          padding:6px 12px;
          border-radius:8px;
          text-decoration:none;
          border:1px solid #cfe6ff;
          background:#fff;
          color:#0072CF;
          font-weight:600;
        }
        .jadwal-page .pagination .active .page-link{
          background:#0072CF;
          border-color:#0072CF;
          color:#fff;
        }
        .jadwal-page .pagination .disabled .page-link{
          opacity:.5;
          pointer-events:none;
        }
        .jadwal-page .pagination .page-link:hover{ background:#e3f2ff; }
      </style>

      <?php
        $base = "dashboard.php?page=jadwal";
        $prev = max(1, $p - 1);
        $next = min($totalPages, $p + 1);
      ?>
      <nav aria-label="Pagination jadwal">
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
