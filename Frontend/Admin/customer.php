<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer | Cleanify Admin</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

    body {
        background: #f5f6fa;
        font-family: 'Lato', sans-serif;
    }

    .content {
        margin-left: 20px;
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
    }

    /* SEARCH + FILTER */
    .action-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .action-row input,
    .action-row select {
        border-radius: 8px;
        border: 1px solid #dcdde1;
        padding: 8px 12px;
        font-size: 14px;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        font-size: 14px;
    }

    thead {
        background: #41aba0;
        color: white;
    }

    th, td {
        border: 1px solid #dcdcdc;
        padding: 10px;
        text-align: center;
    }

    .status-active {
        background: #2ecc71;
        padding: 6px 12px;
        border-radius: 6px;
        color: #fff;
        font-size: 12px;
    }

    .status-off {
        background: #e74c3c;
        padding: 6px 12px;
        border-radius: 6px;
        color: #fff;
        font-size: 12px;
    }

    .btn-sm {
        padding: 4px 10px;
        font-size: 12px;
        border-radius: 6px;
    }

</style>

</head>
<body>

<div class="content">

    <div class="page-title">Kelola Customer</div>

    <div class="card-box">

        <!-- SEARCH + FILTER -->
        <div class="action-row">
            <input type="text" id="searchInput" placeholder="Cari nama atau email..." class="form-control" style="max-width:300px;">
            
            <select id="filterStatus" class="form-control" style="max-width:200px;">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>

        <!-- TABLE -->
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Total Order</th>
                    <th>Status Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="customerData">
                <tr>
                    <td>Merin Kharista</td>
                    <td>merin@gmail.com</td>
                    <td>081234567890</td>
                    <td>12</td>
                    <td><span class="status-active">Aktif</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="openDetail()">Detail</button>
                        <button class="btn btn-danger btn-sm">Nonaktifkan</button>
                    </td>
                </tr>

                <tr>
                    <td>Budi</td>
                    <td>budi@gmail.com</td>
                    <td>081298765432</td>
                    <td>5</td>
                    <td><span class="status-off">Nonaktif</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="openDetail()">Detail</button>
                        <button class="btn btn-success btn-sm">Aktifkan</button>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</div>

<!-- ===================== MODAL DETAIL ===================== -->
<!--<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:12px;">

      <div class="modal-header">
        <h5 class="modal-title">Detail Customer</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6><b>Profil Customer</b></h6>
        <p><b>Nama:</b> Merin Kharista</p>
        <p><b>Email:</b> merin@gmail.com</p>
        <p><b>No HP:</b> 081234567890</p>

        <hr>

        <h6><b>Alamat</b></h6>
        <p>Jl. Kenanga No. 12, Cirebon</p>

        <hr>

        <h6><b>Riwayat Order</b></h6>

        <ul>
            <li>#00123 - Deep Cleaning (Selesai)</li>
            <li>#00111 - AC Cleaning (Selesai)</li>
            <li>#00109 - Regular Cleaning (Batal)</li>
        </ul>

        <hr>

        <h6><b>Catatan</b></h6>
        <p>- Customer sering pesan weekend<br>
           - Lebih suka cleaner perempuan</p>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>

    </div>
  </div>
</div>--!>

<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>

// === SEARCH ===
document.getElementById("searchInput").addEventListener("keyup", function () {
    let q = this.value.toLowerCase();
    document.querySelectorAll("#customerData tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
    });
});

// === FILTER STATUS ===
document.getElementById("filterStatus").addEventListener("change", function () {
    let filter = this.value;

    document.querySelectorAll("#customerData tr").forEach(row => {
        let status = row.querySelector("td:nth-child(5)").innerText.toLowerCase();
        row.style.display = filter === "" || status.includes(filter) ? "" : "none";
    });
});

// === OPEN DETAIL MODAL ===
function openDetail() {
    let modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();
}

</script>

</body>
</html>
