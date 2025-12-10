<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/order_logic.php';

$limit = 5;
$p = max(1, (int)($_GET['p'] ?? 1));
$start = ($p - 1) * $limit;

$orders = orders_list($conn, $limit, $start);
$total = orders_count($conn);
$totalPages = max(1, (int)ceil($total / $limit));
?>

<style>
  /* SCOPED: biar gak nabrak bootstrap & halaman lain */
  .cleanify-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    margin: 20px auto;
    width: 90%;
  }

  .order-box{
    background: #0072CF;
    border-radius: 14px;
    padding: 18px;
    margin-top: 18px;
  }

  .order-topbar{
    display:flex;
    justify-content: space-between;
    align-items:center;
    gap: 12px;
    margin-bottom: 15px;
    flex-wrap: wrap;
  }

  .order-topbar .form-control{
    border-radius: 10px;
  }

  .order-table{
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 10px;
    overflow: hidden;
  }

  .order-table th{
    background-color: #0072CF;
    color: #fff;
    padding: 10px;
    font-weight: bold;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.25);
    vertical-align: middle;
  }

  .order-table td{
    padding: 10px;
    background: #e3f2ff;
    border: 1px solid #cfd8e3;
    color: #111;
    vertical-align: middle;
    text-align: center;
  }

  .order-aksi{
    white-space: nowrap;
  }
  .order-aksi a{
    display:inline-block;
    margin: 3px;
  }

  /* tombol gaya lama */
  .btn-order{
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    font-weight: 600;
  }
  .btn-warning-order{ background:#ffc107; color:#111; }
  .btn-danger-order{ background:#dc3545; color:#fff; }
</style>

<div class="cleanify-card">
  <h2>Kelola Order</h2>

  <div class="order-box">
    <div class="order-topbar">
      <input type="text" id="searchOrder" placeholder="Cari nama customer..." class="form-control" style="max-width:250px;">
      <select id="filterStatus" class="form-control" style="max-width:180px;">
        <option value="">Status: Semua</option>
        <option value="baru">Baru</option>
        <option value="dikonfirmasi">Dikonfirmasi</option>
        <option value="dalam_proses">Dalam_proses</option>
        <option value="selesai">Selesai</option>
        <option value="dibatalkan">Dibatalkan</option>
      </select>
      <input type="date" id="filterTanggal" class="form-control" style="max-width:180px;">
    </div>

    <table class="order-table">
      <thead>
        <tr>
          <th>ID Order</th>
          <th>Nomer Order</th>
          <th>Tanggal Order</th>
          <th>Harga</th>
          <th>Status Pembayaran</th>
          <th>Status Order</th>
          <th>Tanggal Pembayaran</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody id="orderData">
        <?php if (!empty($orders)): ?>
          <?php foreach ($orders as $row): ?>
            <?php
              // IMPORTANT: orders_list sudah join customers & services
              $cust = $row['customer_name'] ?? '';
              $svc  = $row['service_name'] ?? '';
              $status = $row['status'] ?? '';
              $dateRaw = $row['order_date'] ?? '';
              $dateKey = substr((string)$dateRaw, 0, 10); // YYYY-MM-DD
            ?>
            <tr
              data-customer="<?= htmlspecialchars(mb_strtolower($cust)) ?>"
              data-service="<?= htmlspecialchars(mb_strtolower($svc)) ?>"
              data-status="<?= htmlspecialchars(mb_strtolower($status)) ?>"
              data-date="<?= htmlspecialchars($dateKey) ?>"
            >
              <td><?= (int)($row['id'] ?? 0); ?></td>
              <td><?= htmlspecialchars($row['order_code'] ?? ''); ?></td>
              <td><?= htmlspecialchars($dateRaw); ?></td>
              <td>Rp <?= number_format((float)($row['total_cost'] ?? 0), 0, ",", "."); ?></td>
              <td><?= htmlspecialchars($row['payment_proof'] ?? ''); ?></td>
              <td><?= htmlspecialchars($status); ?></td>
              <td><?= htmlspecialchars($row['payment_confirmed_at'] ?? ''); ?></td>

              <td class="order-aksi">
                <a href="dashboard.php?page=order_edit&id=<?= (int)($row['id'] ?? 0); ?>" class="btn-order btn-warning-order">Edit</a>
                <a href="order/delete.php?id=<?= (int)($row['id'] ?? 0); ?>&return=order"
                   onclick="return confirm('Hapus order ini?');"
                   class="btn-order btn-danger-order">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">Data order tidak tersedia.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

  <?php if ($totalPages > 1): ?>
  <style>
    /* Samain kayak pagination pekerja (rapi & bukan bullet), posisi kiri */
    .pagination {
      list-style: none;
      padding-left: 0;
      margin: 16px 0 0;
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: flex-start;
    }
    .pagination .page-item { list-style: none; }
    .pagination .page-link {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 8px;
      text-decoration: none;
      border: 1px solid #cfe6ff;
      background: #fff;
      color: #0072CF;
      font-weight: 600;
    }
    .pagination .page-item.active .page-link {
      background: #0072CF;
      border-color: #0072CF;
      color: #fff;
    }
    .pagination .page-link:hover { background: #e3f2ff; }
    .pagination .page-item.disabled .page-link {
      opacity: .5;
      pointer-events: none;
    }
  </style>

  <nav aria-label="Pagination order">
    <ul class="pagination">
      <?php
        $base = "dashboard.php?page=order";
        $prev = max(1, $p - 1);
        $next = min($totalPages, $p + 1);
      ?>

      <li class="page-item <?= ($p <= 1) ? 'disabled' : '' ?>">
        <a class="page-link" href="<?= $base ?>&p=<?= $prev ?>">&laquo;</a>
      </li>

      <?php for ($i=1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i === $p ? 'active' : '' ?>">
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

<script>
  const tbody = document.getElementById("orderData");
  const qInput = document.getElementById("searchOrder");
  const sSelect = document.getElementById("filterStatus");
  const dInput = document.getElementById("filterTanggal");

  function applyFilters() {
    const q = (qInput.value || "").trim().toLowerCase();
    const st = (sSelect.value || "").trim().toLowerCase();
    const dt = (dInput.value || "").trim(); // YYYY-MM-DD

    tbody.querySelectorAll("tr").forEach(tr => {
      // skip row "data kosong"
      if (!tr.hasAttribute("data-customer")) return;

      const customer = tr.dataset.customer || "";
      const service  = tr.dataset.service || "";
      const status   = tr.dataset.status || "";
      const dateKey  = tr.dataset.date || ""; // YYYY-MM-DD

      // search: customer / service / isi row (biar fleksibel)
      const matchQuery  = !q || customer.includes(q) || service.includes(q) || (tr.innerText || "").toLowerCase().includes(q);
      const matchStatus = !st || status === st;
      const matchDate   = !dt || dateKey === dt;

      tr.style.display = (matchQuery && matchStatus && matchDate) ? "" : "none";
    });
  }

  qInput.addEventListener("keyup", applyFilters);
  sSelect.addEventListener("change", applyFilters);
  dInput.addEventListener("change", applyFilters);
</script>
