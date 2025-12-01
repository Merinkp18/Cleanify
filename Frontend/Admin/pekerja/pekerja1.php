<?php
include '../../../Backend/db.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil data
$sql = "SELECT * FROM employees LIMIT $start, $limit";
$result = $conn->query($sql);



?>


<!DOCTYPE html>
<html>
<head>
	<title>Pekerja</title>
	<style>
		.cleanify-card {
			background: white;
			padding: 30px;
			border-radius: 12px;
			box-shadow: 0 10px 20px rgba(0,0,0,0.1);
			margin: 20px auto;
			width: 90%;
		

		.top-bar {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 15px;
		}

		.top-bar .search {
			flex: 1;
		}

		.top-bar .search input {
			max-width: 250px;
		}

		.top-bar .add-btn {
			margin-left: 10px;
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
			text-align: center;        /* posisi horizontal ke tengah */
		}

			.td-aksi a {
				display: inline-block;     /* biar tetap rapi */
				margin: 3px;               /* jarak antar button */
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
<h2>Kelola Pekerja</h2>
<div class="top-bar">
    <div class="search">
        <input type="text" id="searchOrder" placeholder="Cari nama layanan..." class="search-input">
    </div>

    <div class="add-btn">
        <a href="tambah.php" class="btn btn-success">Tambah Data</a>
    </div>
</div>


<table class="table table-bordered table-striped">
	<thead class="table-success">
		<tr>
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
		<?php if ($result->num_rows > 0): ?>
			<?php while($row = $result->fetch_assoc()): ?>
				<tr>
					<td><?= htmlspecialchars($row['name']); ?></td>
					<td><?= htmlspecialchars($row['position']); ?></td>
					<td><?= htmlspecialchars($row['status']); ?></td>
					<td><?= number_format($row['phone']); ?></td>
					<td><?= htmlspecialchars($row['rating']); ?></td>
					<td>
						<span class="badge bg-<?= $row['status'] == 'active' ? 'success' : 'danger' ?>">
							<?= ucfirst($row['status']); ?>
						</span>
					</td>

					<td class="td-aksi">
						<a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-info btn-sm">Detail</a>
						<a href="update.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
						<a href="delete.php?id=<?= $row['id']; ?>"
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
