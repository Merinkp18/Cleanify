<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order | Cleanify Admin</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background: #f5f6fa;
        font-family: 'Lato', sans-serif;
    }

    .content {
        margin-left: 30px;
		margin-right: 30px;
        padding: 30px;
    }

    .page-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .card-box {
        background: #fff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 3px 8px rgba(0,0,0,.1);
        margin-bottom: 30px;
    }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    table thead {
        background: #41aba0;
        color: white;
    }

    table thead th {
        padding: 12px;
        font-weight: bold;
    }

    table tbody td {
        padding: 12px;
        border-bottom: 1px solid #eee;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        color: #fff;
    }

    .pending { background: #f1c40f; }
    .process { background: #3498db; }
    .done { background: #2ecc71; }
    .cancel { background: #e74c3c; }

    .btn-cleanify {
        background: #41aba0;
        border: none;
        color: white;
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 8px;
        transition: .2s;
    }

    .btn-cleanify:hover {
        background: #379489;
    }

	.modal {
		display:none !important;
	}

    .modal-content {
        border-radius: 12px;
        padding: 10px;
    }

</style>

</head>
<body>

<div class="content">

    <div class="page-title">Kelola Order</div>

    <div class="card-box">

        <!-- FILTER BAR -->
        <div class="action-row">

            <input type="text" id="searchOrder" placeholder="Cari nama customer..." class="form-control" style="max-width:250px;">

            <select id="filterStatus" class="form-control" style="max-width:180px;">
                <option value="">Status: Semua</option>
                <option value="pending">Pending</option>
                <option value="process">On Process</option>
                <option value="done">Done</option>
                <option value="cancel">Cancelled</option>
            </select>

            <input type="date" id="filterTanggal" class="form-control" style="max-width:180px;">

            <select id="filterLayanan" class="form-control" style="max-width:180px;">
                <option value="">Semua Layanan</option>
                <option value="deep cleaning">Deep Cleaning</option>
                <option value="ac cleaning">AC Cleaning</option>
                <option value="home cleaning">Home Cleaning</option>
            </select>

        </div>

        <!-- TABLE ORDER -->
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>ID Order</th>
                    <th>Nama Customer</th>
                    <th>Layanan</th>
                    <th>Jadwal</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Cleaner</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="orderData">

                <tr>
                    <td>#1021</td>
                    <td>Siti Maryam</td>
                    <td>Deep Cleaning</td>
                    <td>2025-01-21</td>
                    <td>Rp 250.000</td>
                    <td><span class="badge-status pending">Pending</span></td>
                    <td>-</td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail">Detail</button>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdate">Update</button>
                    </td>
                </tr>

                <tr>
                    <td>#0998</td>
                    <td>Rudi Hartono</td>
                    <td>AC Cleaning</td>
                    <td>2025-01-20</td>
                    <td>Rp 150.000</td>
                    <td><span class="badge-status process">On Process</span></td>
                    <td>Andi</td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetail">Detail</button>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalUpdate">Update</button>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</div>


<!-- MODAL DETAIL ORDER -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Detail Order</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6><b>Info Customer</b></h6>
        <p>Nama: Siti Maryam<br>No. HP: 0812345678</p>

        <h6><b>Info Layanan</b></h6>
        <p>Layanan: Deep Cleaning<br>Harga: Rp 250.000</p>

        <h6><b>Detail Alamat</b></h6>
        <p>Perumahan Griya Mulya Asri Blok B12</p>

        <h6><b>Catatan Customer</b></h6>
        <p>Tolong perhatikan bagian dapur.</p>

        <h6><b>Metode Pembayaran</b></h6>
        <p>Transfer Bank</p>

        <h6><b>Status Tracking</b></h6>
        <p><span class="badge-status pending">Menunggu Pembersih</span></p>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>


<!-- MODAL UPDATE STATUS -->
<div class="modal fade" id="modalUpdate" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Update Status Order</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <label class="form-label">Pilih Status</label>
        <select class="form-control">
            <option value="pending">Pending</option>
            <option value="process">On Process</option>
            <option value="done">Done</option>
            <option value="cancel">Cancelled</option>
        </select>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn-cleanify">Simpan</button>
      </div>

    </div>
  </div>
</div>


<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>

    // SEARCH
    document.getElementById("searchOrder").addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        document.querySelectorAll("#orderData tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });

    // FILTER STATUS
    document.getElementById("filterStatus").addEventListener("change", function () {
        let filter = this.value;
        document.querySelectorAll("#orderData tr").forEach(row => {
            let status = row.querySelector("td:nth-child(6)").innerText.toLowerCase();
            row.style.display = filter === "" || status.includes(filter) ? "" : "none";
        });
    });

    // FILTER TANGGAL
    document.getElementById("filterTanggal").addEventListener("change", function () {
        let tanggal = this.value;
        document.querySelectorAll("#orderData tr").forEach(row => {
            let date = row.querySelector("td:nth-child(4)").innerText.trim();
            row.style.display = tanggal === "" || tanggal === date ? "" : "none";
        });
    });

    // FILTER LAYANAN
    document.getElementById("filterLayanan").addEventListener("change", function () {
        let layanan = this.value.toLowerCase();
        document.querySelectorAll("#orderData tr").forEach(row => {
            let service = row.querySelector("td:nth-child(3)").innerText.toLowerCase();
            row.style.display = layanan === "" || service.includes(layanan) ? "" : "none";
        });
    });

</script>

</body>
</html>
