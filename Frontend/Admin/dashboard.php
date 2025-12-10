<?php
// ====== PROTEKSI ADMIN SAJA ======
require '../../Backend/Admin/auth_admin.php';

// TAMBAH: DB (biar dashboard sinkron)
require '../../Backend/db.php';

$page = $_GET['page'] ?? 'home';

date_default_timezone_set('Asia/Jakarta');

// helper aman ambil count
function db_count(mysqli $conn, string $sql, string $types = '', array $params = []): int {
    $stmt = $conn->prepare($sql);
    if ($types !== '') $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return (int)($row['c'] ?? 0);
}

/* =========================
   DATA HOME (DB) - SINKRON
   ========================= */

// Cards
$ordersToday     = db_count($conn, "SELECT COUNT(*) AS c FROM orders WHERE DATE(order_date)=CURDATE()");
$totalCustomers  = db_count($conn, "SELECT COUNT(*) AS c FROM customers");
$activeCleaners  = db_count($conn, "SELECT COUNT(*) AS c FROM employees WHERE status='active'");
$activeServices  = db_count($conn, "SELECT COUNT(*) AS c FROM services WHERE status='active'");

// Chart bar: orders per bulan (tahun ini)
$monthCounts = array_fill(1, 12, 0);
$stmt = $conn->prepare("
    SELECT MONTH(order_date) AS m, COUNT(*) AS c
    FROM orders
    WHERE YEAR(order_date)=YEAR(CURDATE())
    GROUP BY MONTH(order_date)
");
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) {
    $m = (int)($r['m'] ?? 0);
    if ($m >= 1 && $m <= 12) $monthCounts[$m] = (int)($r['c'] ?? 0);
}
$orderPerMonthData = array_values($monthCounts); // 0..11

// Chart pie: distribusi kategori layanan (NGIKUTIN TABEL services)
$serviceLabels = [];
$serviceValues = [];
$stmt = $conn->prepare("
    SELECT COALESCE(category,'Unknown') AS cat, COUNT(*) AS c
    FROM services
    WHERE status='active'
    GROUP BY cat
    ORDER BY c DESC
");
$stmt->execute();
$res = $stmt->get_result();
while ($r = $res->fetch_assoc()) {
    $serviceLabels[] = (string)($r['cat'] ?? 'Unknown');
    $serviceValues[] = (int)($r['c'] ?? 0);
}
if (!$serviceLabels) {
    $serviceLabels = ['Belum ada layanan aktif'];
    $serviceValues = [1];
}

// Tabel: order terbaru
$latestOrders = [];
$stmt = $conn->prepare("
    SELECT
      o.id,
      COALESCE(c.name,'-') AS customer_name,
      COALESCE(s.name,'-') AS service_name,
      o.order_date,
      o.status
    FROM orders o
    LEFT JOIN customers c ON o.customer_id = c.id
    LEFT JOIN services s ON o.service_id = s.id
    ORDER BY o.id DESC
    LIMIT 5
");
$stmt->execute();
$res = $stmt->get_result();
$latestOrders = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin | Cleanify</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f5f6fa;
            font-family: 'Lato', sans-serif;
            margin: 0;
        }

        /* SIDEBAR */
        .sidebar {
			width: 230px;
			height: 100vh;
			background: #2c3e50;
			position: fixed;
			top: 0;
			left: 0;
			padding-top: 40px;     /* dikurangi, supaya foto naik */
			color: #fff;
		}

		/* CONTAINER UNTUK FOTO */
		.sidebar-header {
			display: flex;
			flex-direction: column;
			align-items: center;     /* center horizontal */
			justify-content: center; /* center vertical */
			margin-bottom: 20px;
		}

		/* FOTO DI SIDEBAR */
		.sidebar-photo {
			width: 90px;
			height: 90px;
			object-fit: cover;
			border-radius: 50%;
			border: 3px solid #0072CF;
			margin-bottom: 10px;
		}

        .sidebar a {
            display: block;
            padding: 14px 30px;
            color: #bdc3c7;
            font-size: 15px;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0072CF;
            color: #fff;
        }

        /* ===== FIX SIDEBAR PRESISI (TANPA UBAH DESAIN) ===== */
        .sidebar a{
            display:flex;
            align-items:center;
            gap:12px;
        }
        .sidebar a i{
            width:20px;
            text-align:center;
            flex:0 0 20px;
        }

        /* TOPBAR */
        .topbar {
            margin-left: 230px;
            height: 75px;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-left img {
            width: 65px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .topbar-right .icon-wrapper {
            position: relative;
            cursor: pointer;
        }

        .topbar-right .icon-wrapper i {
            font-size: 20px;
            color: #2c3e50;
        }

        .badge-notif,
        .badge-message {
            position: absolute;
            top: -6px;
            right: -8px;
            padding: 2px 6px;
            font-size: 10px;
            border-radius: 50%;
            color: #fff;
        }

        .badge-notif { background: #e74c3c; }
        .badge-message { background: #3498db; }

        .topbar-right .profile-img {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #41aba0;
            cursor: pointer;
        }

        /* CONTENT */
        .content {
            margin-left: 230px;
            padding: 30px;
        }

        /* ===== HOME DASHBOARD ONLY (SCOPED) ===== */
        .home-dashboard .card-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .home-dashboard .card-box {
            background: #0072CF;
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }

        .home-dashboard .card-box h3 {
            font-size: 28px;
            margin-top: 5px;
            font-weight: bold;
        }

        .home-dashboard .chart-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .home-dashboard .chart-card {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }

        .home-dashboard .chart-card h5 {
            font-weight: bold;
            margin-bottom: 15px;
            color: #0072CF;
        }

        .home-dashboard .chart-card canvas {
            height: 300px !important;
        }

        /* ============================
           FIX: TABLE CARD (HOME ONLY)
           ============================ */
        .home-dashboard .table-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
            margin-top: 25px;

            width: 90%;
            max-width: 1100px;
            overflow-x: auto;
        }

        .home-dashboard .table-card h4 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #0072CF;
        }

        .home-dashboard .table-card .table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        .home-dashboard .table-card .table thead th {
            background: #0072CF !important;
            color: #fff !important;
            border: 1px solid #3f928d !important;
            padding: 12px !important;
            text-align: center;
            vertical-align: middle;
        }

        .home-dashboard .table-card .table td {
            padding: 12px !important;
            border: 1px solid #ddd !important;
            vertical-align: middle;
        }

        .home-dashboard .table-card .table td,
        .home-dashboard .table-card .table th {
            white-space: normal;
            word-break: break-word;
        }

        .status {
            padding: 3px 12px;
            border-radius: 6px;
            color: white;
            font-size: 15px;
        }

        .pending { background: #f39c12; }
        .process { background: #3498db; }
        .done { background: #2ecc71; }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
		<div class="sidebar-header">
			<img src="assets/admin.jpeg" alt="Logo" class="sidebar-photo">
			<h5 style="text-align:center; margin-top:10px;color:white;">Admin Redup</h5>
		</div>
    
			<?php if ($_SESSION['role'] === 'admin'): ?>
				<a href="dashboard.php?page=home" class="<?= ($page==='home'?'active':'') ?>"><i class="fa fa-home"></i> Dashboard</a>
				<a href="dashboard.php?page=layanan" class="<?= ($page==='layanan'?'active':'') ?>"><i class="fa fa-broom"></i> Layanan</a>
				<a href="dashboard.php?page=order" class="<?= ($page==='order'?'active':'') ?>"><i class="fa fa-shopping-cart"></i> Order</a>
				<a href="dashboard.php?page=jadwal" class="<?= ($page==='jadwal'?'active':'') ?>"><i class="fa fa-calendar"></i> Jadwal</a>
				<a href="dashboard.php?page=customer" class="<?= ($page==='customer'?'active':'') ?>"><i class="fa fa-users"></i> Customer</a>
				<a href="dashboard.php?page=pekerja" class="<?= ($page==='pekerja'?'active':'') ?>"><i class="fa fa-user-tie"></i> Pekerja</a>
			<?php endif; ?>
		
    </div>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <img src="assets/logo3.png">
            <h4 style="margin:0; color:#333;">Dashboard Admin</h4>
        </div>

        
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">

       <?php
        $page = $_GET['page'] ?? 'home';

        if ($page !== 'home') {
            // ROUTE LENGKAP -> biar detail/edit/tambah gak "halaman tidak ditemukan"
            $routes = [
                // main
                'layanan'  => __DIR__ . '/layanan/layanan.php',
                'customer' => __DIR__ . '/customer/customer.php',
                'order'    => __DIR__ . '/order/order.php',
                'jadwal'   => __DIR__ . '/jadwal/jadwal.php',
                'pekerja'  => __DIR__ . '/pekerja/pekerja.php',

                // layanan
                'layanan_detail' => __DIR__ . '/layanan/detail.php',
                'layanan_create' => __DIR__ . '/layanan/create_layanan.php',
                'layanan_update' => __DIR__ . '/layanan/update.php',

                // customer
                'customer_detail' => __DIR__ . '/customer/detail.php',
                'customer_tambah' => __DIR__ . '/customer/tambah.php',
                'customer_update' => __DIR__ . '/customer/update.php',

                // pekerja
                'pekerja_detail' => __DIR__ . '/pekerja/detail.php',
                'pekerja_tambah' => __DIR__ . '/pekerja/tambah.php',
                'pekerja_edit'   => __DIR__ . '/pekerja/edit.php',

                // order
                'order_edit'     => __DIR__ . '/order/edit.php',
            ];

            if (isset($routes[$page]) && file_exists($routes[$page])) {
                include $routes[$page];
            } else {
                echo "<h3>Halaman tidak ditemukan.</h3>";
            }
            exit;
        }
        ?>

        <!-- WRAPPER HOME BIAR CSS GAK NABRAK -->
        <div class="home-dashboard">

            <h3 class="mb-4">Overview</h3>

            <div class="card-row">
                <div class="card-box"><p>Order Hari Ini</p><h3><?= (int)$ordersToday ?></h3></div>
                <div class="card-box"><p>Total Customer</p><h3><?= (int)$totalCustomers ?></h3></div>
                <div class="card-box"><p>Cleaner Aktif</p><h3><?= (int)$activeCleaners ?></h3></div>
                <div class="card-box"><p>Layanan Tersedia</p><h3><?= (int)$activeServices ?></h3></div>
            </div>

            <h4 class="mt-5 mb-3">Statistik Order</h4>

            <div class="chart-row">
                <div class="chart-card">
                    <h5>Jumlah Order per Bulan</h5>
                    <canvas id="orderChart"></canvas>
                </div>
                <div class="chart-card">
                    <h5>Distribusi Jenis Layanan</h5>
                    <canvas id="serviceChart"></canvas>
                </div>
            </div>

            <div class="table-card">
                <h4>Order Terbaru</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID Order</th>
                            <th>Customer</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($latestOrders)): ?>
                        <?php foreach ($latestOrders as $o): ?>
                            <tr>
                                <td><?= (int)($o['id'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($o['customer_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($o['service_name'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($o['order_date'] ?? '-') ?></td>
                                <td><?= htmlspecialchars($o['status'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Belum ada data order.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div><!-- /home-dashboard -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const orderPerMonth = <?= json_encode($orderPerMonthData, JSON_UNESCAPED_UNICODE) ?>;
        const serviceLabels = <?= json_encode($serviceLabels, JSON_UNESCAPED_UNICODE) ?>;
        const serviceValues = <?= json_encode($serviceValues, JSON_UNESCAPED_UNICODE) ?>;

        new Chart(document.getElementById('orderChart'), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [{
                    label: 'Jumlah Order',
                    data: orderPerMonth,
                    backgroundColor: '#41aba0',
                    borderRadius: 6
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        const baseColors = ['#41aba0','#8ec64e','#009ee5','#f39c12'];
        const pieColors = serviceLabels.map((_, i) => baseColors[i % baseColors.length]);

        new Chart(document.getElementById('serviceChart'), {
            type: 'pie',
            data: {
                labels: serviceLabels,
                datasets: [{
                    data: serviceValues,
                    backgroundColor: pieColors
                }]
            },
            options: { responsive: true }
        });
    </script>

</body>
</html>
