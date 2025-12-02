<?php
// Proteksi admin
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';

// Fitur pencarian
$search = trim($_GET['search'] ?? '');

// Query dasar
$sql = "SELECT id, name, short_description, category, price, duration_minutes, status 
        FROM services";

// Jika mencari layanan
if ($search !== '') {
    $sql .= " WHERE name LIKE ?";
    $stmt = $conn->prepare($sql);
    $param = "%" . $search . "%";
    $stmt->bind_param("s", $param);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Layanan</title>
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
		}

		.top-bar .search input {
			max-width: 250px;
		}

		.search-input {
			padding: 6px 10px;
			border-radius: 50px;
			border: 1px solid #ccc;
			width: 250px;
		}

		.table {
			width: 100%;
			border-collapse: collapse;
			box-shadow: 0 4px 10px rgba(0,0,0,0.1);
		}

		.table th {
			background-color: #0072CF;
			color: white;
			padding: 10px;
			font-weight: bold;
			text-align: center;
		}

		.table td {
			padding: 10px;
			background: #e3f2ff;
		}

		.h2 {
			color: #0072CF;
			font-weight: 600;
			margin-bottom: 20px;
		}

		.td-aksi {
			text-align: center;
		}

		.td-aksi a {
			display: inline-block;
			margin: 3px;
		}

		.btn {
			padding: 6px 12px;
			border-radius: 6px;
			font-size: 14px;
			text-decoration: none;
		}

		.btn-success { background: #0072CF; color: white; }
		.btn-info { background: #17a2b8; color: white; }
		.btn-warning { background: #ffc107; color: black; }
		.btn-danger { background: #dc3545; color: white; }
	</style>
</head>

<body>
<div class="cleanify-card">

<h2>Kelola Layanan</h2>

<div class="top-bar">
    <div class="search">
        <form method="GET" style="display: inline;">
            <input type="text" name="search" placeholder="Cari nama layanan..." 
                   class="search-input"
                   value="<?= htmlspecialchars($search); ?>">
        </form>
    </div>

    <div class="add-btn">
        <a href="create_layanan.php" class="btn btn-success">Tambah Data</a>
    </div>
</div>

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
		<?php if ($result->num_rows > 0): ?>
			<?php while($row = $result->fetch_assoc()): ?>
				<tr>
					<td><?= htmlspecialchars($row['name']); ?></td>
					<td><?= htmlspecialchars($row['short_description']); ?></td>
					<td><?= htmlspecialchars($row['category']); ?></td>
					<td>Rp <?= number_format($row['price'], 0, ",", "."); ?></td>
					<td><?= (int)$row['duration_minutes']; ?> menit</td>
					<td>
						<span class="badge bg-<?= $row['status'] === 'active' ? 'success' : 'danger'; ?>">
							<?= ucfirst($row['status']); ?>
						</span>
					</td>

					<td class="td-aksi">
						<a href="detail.php?id=<?= (int)$row['id']; ?>" class="btn btn-info btn-sm">Detail</a>
						<a href="update.php?id=<?= (int)$row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
						<a href="delete.php?id=<?= (int)$row['id']; ?>"
						   onclick="return confirm('Hapus layanan ini?');"
						   class="btn btn-danger btn-sm">Hapus</a>
					</td>
				</tr>
			<?php endwhile; ?>
		<?php else: ?>
			<tr><td colspan="7" class="text-center">Data layanan tidak tersedia.</td></tr>
		<?php endif; ?>
	</tbody>
</table>
</div>
</body>
</html>
