<?php 
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/service_logic.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div class='alert alert-danger'>ID layanan tidak valid.</div>";
    return;
}

$data = service_get($conn, $id);
if (!$data) {
    echo "<div class='alert alert-danger'>Data layanan tidak ditemukan.</div>";
    return;
}

// submit harus balik ke route dashboard
$action = "dashboard.php?page=layanan_update&id=" . $id;

if (isset($_POST['submit'])) {
    $payload = [
        'name' => trim($_POST['name'] ?? ''),
        'category' => trim($_POST['category'] ?? ''),
        'price' => (float)($_POST['price'] ?? 0),
        'duration_minutes' => (int)($_POST['duration'] ?? 0),
        'short_description' => trim($_POST['short'] ?? ''),
        'full_description' => trim($_POST['full'] ?? ''),
        'features' => trim($_POST['features'] ?? ''),
        'not_included' => trim($_POST['not_included'] ?? ''),
        // default aman: kalau gak ada POST, pakai nilai lama
        'status' => trim($_POST['status'] ?? ($data['status'] ?? 'inactive')),
    ];

    // validasi minimal
    if ($payload['name'] === '') {
        echo "<div class='alert alert-danger'>Nama layanan tidak boleh kosong.</div>";
    } else {
        service_update($conn, $id, $payload);

        // karena file ini di-include dashboard.php -> jangan pakai header()
        echo "<script>window.location='dashboard.php?page=layanan';</script>";
        exit;
    }
}
?>

<h2 style="color:#0072CF; margin-bottom:20px;">Edit Layanan</h2>

<style>
    .form-table {
        width: 70%;
        background: #ffffff;
        border-collapse: collapse;
        margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .form-table th {
        width: 25%;
        background: #0072CF;
        color: white;
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .form-table td {
        padding: 12px;
        border: 1px solid #ddd;
        background: #f9fbff;
    }
    .form-control {
        width: 100%;
        padding: 8px;
        border-radius: 6px;
        border: 1px solid #ccc;
    }
    .btn-cleanify {
        background: #0072CF;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }
    .btn-cleanify:hover { background: #005fa8; }
    .btn-secondary {
        padding: 8px 16px;
        border-radius: 6px;
        background: #aaa;
        color: white;
        text-decoration: none;
        margin-left: 8px;
    }
    .btn-secondary:hover { background: #888; }
</style>

<form method="POST" action="<?= htmlspecialchars($action) ?>">
  <table class="form-table">

    <tr>
      <th>Nama Layanan</th>
      <td><input type="text" name="name" value="<?= htmlspecialchars($data['name'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
      <th>Kategori</th>
      <td>
        <select name="category" class="form-control">
          <option value="regular" <?= (($data['category'] ?? '')=='regular')?'selected':'' ?>>Regular</option>
          <option value="deep_clean" <?= (($data['category'] ?? '')=='deep_clean')?'selected':'' ?>>Deep Clean</option>
          <option value="premium" <?= (($data['category'] ?? '')=='premium')?'selected':'' ?>>Premium</option>
        </select>
      </td>
    </tr>

    <tr>
      <th>Harga</th>
      <td><input type="number" name="price" value="<?= htmlspecialchars($data['price'] ?? 0) ?>" class="form-control"></td>
    </tr>

    <tr>
      <th>Durasi</th>
      <td><input type="number" name="duration" value="<?= htmlspecialchars($data['duration_minutes'] ?? 0) ?>" class="form-control"></td>
    </tr>

    <tr>
      <th>Deskripsi Singkat</th>
      <td><input type="text" name="short" value="<?= htmlspecialchars($data['short_description'] ?? '') ?>" class="form-control"></td>
    </tr>

    <tr>
      <th>Deskripsi Lengkap</th>
      <td><textarea name="full" class="form-control"><?= htmlspecialchars($data['full_description'] ?? '') ?></textarea></td>
    </tr>

    <tr>
      <th>Fitur</th>
      <td><textarea name="features" class="form-control"><?= htmlspecialchars($data['features'] ?? '') ?></textarea></td>
    </tr>

    <tr>
      <th>Tidak Termasuk</th>
      <td><textarea name="not_included" class="form-control"><?= htmlspecialchars($data['not_included'] ?? '') ?></textarea></td>
    </tr>

    <tr>
      <th>Status</th>
      <td>
        <select name="status" class="form-control">
          <option value="active" <?= (($data['status'] ?? '')=='active')?'selected':'' ?>>Aktif</option>
          <option value="inactive" <?= (($data['status'] ?? '')=='inactive')?'selected':'' ?>>Tidak Aktif</option>
        </select>
      </td>
    </tr>

  </table>

  <button class="btn btn-cleanify" name="submit" type="submit">Update</button>
  <a href="dashboard.php?page=layanan" class="btn btn-secondary">Kembali</a>
</form>
