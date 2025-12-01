<?php
include '../../../Backend/db.php';

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Ambil data
$sql = "SELECT * FROM orders LIMIT $start, $limit";
$result = $conn->query($sql);

?>


<!DOCTYPE html>
<html 
<head>

<title>Order</title>

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

</style>

</head>
<body>

<div class="cleanify-card">

    <h2>Kelola Order</h2>

    <div class="card-box">

        <!-- FILTER BAR -->
        <div class="top-bar">

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

        <!-- TABLE ORDER -->
        <table class="table table-bordered table-striped">
            <thead class="table-success">
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

            <tbody>
				<?php if ($result->num_rows > 0): ?>
					<?php while($row = $result->fetch_assoc()): ?>
						<tr>
							<td><?= htmlspecialchars($row['customer_id']); ?></td>
							<td><?= htmlspecialchars($row['order_code']); ?></td>
							<td><?= htmlspecialchars($row['order_date']); ?></td>
							<td>Rp <?= number_format($row['total_cost'], 0, ",", "."); ?></td>
							<td><?= htmlspecialchars($row['payment_proof']); ?></td>
							<td>
								<span class="badge bg-<?= $row['status'] == 'baru' ? 'warning' : 'danger' ?>">
									<?= ucfirst($row['status']); ?>
								</span>
							</td>
							<td><?= htmlspecialchars($row['payment_confirmed_at']); ?></td>

							<td class="td-aksi">
								<a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
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

</div>





   
    <script>

// SEARCH CUSTOMER
document.getElementById("searchOrder").addEventListener("keyup", function () {
    let val = this.value.toLowerCase();
    document.querySelectorAll("#orderData tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val) ? "" : "none";
    });
});

// FILTER STATUS
document.getElementById("filterStatus").addEventListener("change", function () {
    let val = this.value.toLowerCase();
    document.querySelectorAll("#orderData tr").forEach(row => {
        let status = row.children[5].innerText.toLowerCase();
        row.style.display = (val === "" || status.includes(val)) ? "" : "none";
    });
});

// FILTER TANGGAL ORDER
document.getElementById("filterTanggal").addEventListener("change", function () {
    let val = this.value;
    document.querySelectorAll("#orderData tr").forEach(row => {
        let date = row.children[2].innerText.trim();
        row.style.display = (val === "" || date.startsWith(val)) ? "" : "none";
    });
});

</script>




</body>
</html>
