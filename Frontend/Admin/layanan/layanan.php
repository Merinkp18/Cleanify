<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/service_logic.php';

// search
$search = trim($_GET['search'] ?? '');

// query
$sql = "SELECT id, name, short_description, category, price, duration_minutes, status FROM services";
if ($search !== '') {
    $sql .= " WHERE name LIKE ? ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $param = "%{$search}%";
    $stmt->bind_param("s", $param);
} else {
    $sql .= " ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- CSS DI-SCOPE KHUSUS HALAMAN LAYANAN, BIAR GAK NABRAK PAGE LAIN -->
<style>
  .layanan-page .cleanify-card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    margin: 20px auto;
    width: 90%;
  }

  .layanan-page .cleanify-card h2 {
    margin: 0 0 18px 0;
    font-weight: 800;
  }

  .layanan-page .cleanify-card .top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    gap: 12px;
    flex-wrap: wrap;
  }

  .layanan-page .cleanify-card .search-input {
    padding: 6px 10px;
    border-radius: 50px;
    border: 1px solid #ccc;
    width: 250px;
    max-width: 100%;
  }

  .layanan-page .cleanify-card .table {
    width: 100%;
    border-collapse: collapse;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }

  .layanan-page .cleanify-card .table th {
    background-color: #0072CF !important;
    color: white !important;
    padding: 10px !important;
    font-weight: bold;
    text-align: center;
    vertical-align: middle;
    border: 1px solid #ddd !important;
    white-space: nowrap;
  }

  .layanan-page .cleanify-card .table td {
    padding: 10px !important;
    background: #e3f2ff !important;
    border: 1px solid #ddd !important;
    vertical-align: middle;
  }

  .layanan-page .cleanify-card .td-aksi {
    text-align: center;
    white-space: nowrap;
  }

  .layanan-page .cleanify-card .td-aksi a {
    display: inline-block;
    margin: 3px;
  }

  /* tombol gaya cleanify */
  .layanan-page .cleanify-card .btn {
    padding: 6px 12px !important;
    border-radius: 6px !important;
    font-size: 14px !important;
    text-decoration: none !important;
    border: none !important;
  }
  .layanan-page .cleanify-card .btn-success { background: #0072CF !important; color: white !important; }
  .layanan-page .cleanify-card .btn-info    { background: #17a2b8 !important; color: white !important; }
  .layanan-page .cleanify-card .btn-warning { background: #ffc107 !important; color: black !important; }
  .layanan-page .cleanify-card .btn-danger  { background: #dc3545 !important; color: white !important; }

  /* biar table gak meledak kalau teks panjang */
  .layanan-page .cleanify-card .table-responsive {
    width: 100%;
    overflow-x: auto;
  }
  .layanan-page .cleanify-card td:nth-child(2) { /* deskripsi */
    min-width: 260px;
  }
</style>

<div class="layanan-page">
  <div class="cleanify-card">
    <h2>Kelola Layanan</h2>

    <div class="top-bar">
      <div class="search">
        <!-- action harus ke dashboard + bawa page=layanan -->
        <form method="GET" action="dashboard.php" style="display:inline;">
          <input type="hidden" name="page" value="layanan">
          <input
            type="text"
            name="search"
            placeholder="Cari nama layanan..."
            class="search-input"
            value="<?= htmlspecialchars($search); ?>"
          >
        </form>
      </div>

      <div class="add-btn">
        <a href="dashboard.php?page=layanan_create" class="btn btn-success">Tambah Data</a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped">
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
          <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><?= htmlspecialchars($row['short_description']); ?></td>
                <td><?= htmlspecialchars($row['category']); ?></td>
                <td>Rp <?= number_format((float)$row['price'], 0, ",", "."); ?></td>
                <td><?= (int)$row['duration_minutes']; ?> menit</td>
                <td><?= htmlspecialchars(ucfirst($row['status'])); ?></td>

                <td class="td-aksi">
                  <a href="dashboard.php?page=layanan_detail&id=<?= (int)$row['id']; ?>" class="btn btn-info">Detail</a>
                  <a href="dashboard.php?page=layanan_update&id=<?= (int)$row['id']; ?>" class="btn btn-warning">Edit</a>
                  <a href="layanan/delete.php?id=<?= (int)$row['id']; ?>&return=layanan"
                     onclick="return confirm('Hapus layanan ini?');"
                     class="btn btn-danger">Hapus</a>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">Data layanan tidak tersedia.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>
