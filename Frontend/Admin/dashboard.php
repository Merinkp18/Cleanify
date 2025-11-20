<?php
session_start();
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
            padding-top: 80px;
            color: #fff;
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
            background: #41aba0;
            color: #fff;
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

        /* CARD OVERVIEW */
        .card-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .card-box {
            background: #9292ff;
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }

        .card-box h3 {
            font-size: 28px;
            margin-top: 5px;
            font-weight: bold;
        }

        /* CHART SECTION */
        .chart-row {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .chart-card {
            flex: 1;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
        }

        .chart-card h5 {
            font-weight: bold;
            margin-bottom: 15px;
            color: #41aba0;
        }

        .chart-card canvas {
            height: 300px !important;
        }

        /* TABLE */
        .table-container {
            width: 100%;
            margin-top: 20px;
        }

        .table-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.1);
            margin-top: 25px;
            width: 45%;
            overflow-x: auto;
        }

        .table-card h4 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .table {
            border: 1px solid #ddd;
        }

        .table th,
        .table td {
            border: 1px solid #ddd !important;
            padding: 10px 12px;
            vertical-align: middle;
        }

        .table thead th {
            background: #41aba0 !important;
            color: #fff;
            border: 1px solid #3f928d !important;
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
        <a href="dashboard.php?page=home" class="active"><i class="fa fa-home me-2"></i> Dashboard</a>
        <a href="layanan.php?page=layanan"><i class="fa fa-broom me-2"></i> Layanan</a>
        <a href="order.php?page=order"><i class="fa fa-shopping-cart me-2"></i> Order</a>
        <a href="jadwal.php?page=jadwal"><i class="fa fa-calendar me-2"></i> Jadwal</a>
        <a href="customer.php?page=customer"><i class="fa fa-users me-2"></i> Customer</a>
        <a href="pekerja.php?page=pekerja"><i class="fa fa-user-tie me-2"></i> Pekerja</a>
    </div>

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <img src="assets/logo3.png">
            <h4 style="margin:0; color:#333;">Dashboard Admin</h4>
        </div>

        <div class="topbar-right">
            <div class="icon-wrapper">
                <i class="fa fa-bell"></i>
                <span class="badge-notif">3</span>
            </div>

            <div class="icon-wrapper">
                <i class="fa fa-envelope"></i>
                <span class="badge-message">4</span>
            </div>

            <img src="Admin/assets/profile.jpg" class="profile-img">
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">

        <!-- ================== ROUTER MULAI DI SINI ================== -->
        <?php
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        if ($page !== 'home') {
            if (file_exists("pages/" . $page . ".php")) {
                include "pages/" . $page . ".php";
            } else {
                echo "<h3>Halaman tidak ditemukan.</h3>";
            }
            exit;
        }
        ?>
        <!-- ================== ROUTER SELESAI ================== -->

        <!-- HALAMAN DASHBOARD UTAMA -->
        <h3 class="mb-4">Overview</h3>

        <div class="card-row">
            <div class="card-box"><p>Order Hari Ini</p><h3>23</h3></div>
            <div class="card-box"><p>Total Customer</p><h3>112</h3></div>
            <div class="card-box"><p>Cleaner Aktif</p><h3>18</h3></div>
            <div class="card-box"><p>Layanan Tersedia</p><h3>6</h3></div>
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
                    <tr>
                        <td>#00123</td>
                        <td>Merin</td>
                        <td>Deep Cleaning</td>
                        <td>18 Nov 2025</td>
                        <td><span class="status pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td>#00122</td>
                        <td>Kharis</td>
                        <td>AC Cleaning</td>
                        <td>17 Nov 2025</td>
                        <td><span class="status process">On Process</span></td>
                    </tr>
                    <tr>
                        <td>#00121</td>
                        <td>Ravi</td>
                        <td>Regular Cleaning</td>
                        <td>17 Nov 2025</td>
                        <td><span class="status done">Done</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        new Chart(document.getElementById('orderChart'), {
            type: 'bar',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
                datasets: [{
                    label: 'Jumlah Order',
                    data: [30,45,62,48,56,70,85,90,76,80,95,110],
                    backgroundColor: '#41aba0',
                    borderRadius: 6
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });

        new Chart(document.getElementById('serviceChart'), {
            type: 'pie',
            data: {
                labels: ['Regular', 'Deep Cleaning', 'AC Cleaning', 'Office Cleaning'],
                datasets: [{
                    data: [45, 30, 15, 10],
                    backgroundColor: ['#41aba0','#8ec64e','#009ee5','#f39c12']
                }]
            },
            options: { responsive: true }
        });
    </script>

</body>
</html>
