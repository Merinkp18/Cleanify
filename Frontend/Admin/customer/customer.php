<?php
// ====== ADMIN PROTECTION ======
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';

// ====== DATABASE ======
require __DIR__ . '/../../../Backend/db.php';

// base dashboard path dinamis
$dash = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/dashboard.php';

// ====== PAGINATION ======
$limit = 5;
$p = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($p < 1) $p = 1;
$start = ($p - 1) * $limit;

// ====== TOTAL COUNT (buat totalPages) ======
$total = 0;
$countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM customers");
$countStmt->execute();
$countRes = $countStmt->get_result();
if ($countRes) {
    $total = (int)($countRes->fetch_assoc()['total'] ?? 0);
}
$totalPages = max(1, (int)ceil($total / $limit));

// ====== QUERY (prepared statement) ======
$stmt = $conn->prepare("
  SELECT id, name, email, address, phone, property_type
  FROM customers
  ORDER BY id DESC
  LIMIT ?, ?
");
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Customer</title>
  <style>
    .cleanify-card {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      margin: 20px auto;
      width: 90%;
    }

    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      gap: 12px;
    }

    .top-bar .search { flex: 1; }

    .search-input {
      padding: 6px 10px;
      border-radius: 50px;
      border: 1px solid #ccc;
      width: 250px;
      outline: none;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      background: #fff;
    }

    .table th {
      background-color: #0072CF;
      color: white;
      padding: 10px;
      font-weight: bold;
      text-align: center;
      border: 1px solid rgba(255,255,255,0.25);
    }

    .table td {
      padding: 10px;
      background: #e3f2ff;
      text-align: center;
      border: 1px solid #cfd8e3;
      color: #111;
    }

    .td-aksi { text-align: center; white-space: nowrap; }
    .td-aksi a { display: inline-block; margin: 3px; }

    .btn {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
      text-decoration: none;
      font-weight: 600;
      display: inline-block;
    }

    .btn-success { background: #0072CF; color: white; }
    .btn-info { background: #17a2b8; color: white; }
    .btn-warning { background: #ffc107; color: black; }
    .btn-danger { background: #dc3545; color: white; }

    /* Pagination model pekerja (kiri) */
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
</head>

<body>
  <div class="cleanify-card">
    <h2>Kelola Customer</h2>

    <div class="top-bar">
      <div class="search">
        <input type="text" id="searchCustomer" placeholder="Cari nama customer..." class="search-input">
      </div>

      <div class="add-btn">
        <a href="<?= htmlspecialchars($dash) ?>?page=customer_tambah" class="btn btn-success">Tambah Data</a>
      </div>
    </div>

    <table class="table table-bordered table-striped">
      <thead class="table-success">
        <tr>
          <th>Nama Customer</th>
          <th>Email</th>
          <th>Alamat</th>
          <th>Nomor Telepon</th>
          <th>Tipe Tempat</th>
          <th>Aksi</th>
        </tr>
      </thead>

      <tbody id="customerData">
        <?php if ($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['name'] ?? ''); ?></td>
              <td><?= htmlspecialchars($row['email'] ?? ''); ?></td>
              <td><?= htmlspecialchars($row['address'] ?? ''); ?></td>
              <td><?= htmlspecialchars($row['phone'] ?? ''); ?></td>
              <td><?= htmlspecialchars($row['property_type'] ?? ''); ?></td>

              <td class="td-aksi">
                <a href="<?= htmlspecialchars($dash) ?>?page=customer_detail&id=<?= (int)$row['id']; ?>" class="btn btn-info btn-sm">Detail</a>
                <a href="<?= htmlspecialchars($dash) ?>?page=customer_update&id=<?= (int)$row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= htmlspecialchars($dash) ?>?page=customer_delete&id=<?= (int)$row['id']; ?>"
                   onclick="return confirm('Hapus customer ini?');"
                   class="btn btn-danger btn-sm">Hapus</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="6" class="text-center">Data customer tidak tersedia.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
      <?php
        $base = htmlspecialchars($dash) . '?page=customer';
        $prev = max(1, $p - 1);
        $next = min($totalPages, $p + 1);
      ?>
      <nav aria-label="Pagination customer">
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

  <script>
    document.getElementById("searchCustomer").addEventListener("keyup", function () {
      let val = this.value.toLowerCase();
      document.querySelectorAll("#customerData tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
      });
    });
  </script>
</body>
</html>
