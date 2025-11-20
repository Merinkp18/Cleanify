<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Layanan | Cleanify Admin</title>

<link rel="stylesheet" href="assets/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

    body {
        background: #f5f6fa;
        font-family: 'Lato', sans-serif;
    }

    .content {
        margin-left: 20px;
        padding: 10px;
    }

    .page-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    /* CARD WRAPPER */
    .card-box {
        background: #fff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 3px 8px rgba(0,0,0,.1);
        margin-bottom: 40px;
    }

    /* SEARCH & FILTER ROW */
    .action-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .action-row input,
    .action-row select {
        border-radius: 8px;
        border: 1px solid #dcdde1;
        padding: 8px 12px;
    }

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

    /* TABLE STYLING */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead th {
        background: #41aba0;
        color: white;
        padding: 12px;
        font-weight: bold;
    }

    tbody tr {
        background: #fff;
        border-bottom: 1px solid #eee;
    }

    tbody td {
        padding: 12px;
        vertical-align: middle;
    }

    .status-active {
        background: #2ecc71;
        padding: 6px 12px;
        border-radius: 8px;
        color: white;
        font-size: 12px;
    }

    .status-off {
        background: #e74c3c;
        padding: 6px 12px;
        border-radius: 8px;
        color: white;
        font-size: 12px;
    }

    .btn-sm {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
    }

    .modal-content {
        border-radius: 12px;
        padding: 10px;
    }

    .modal-header {
        border-bottom: none;
    }

</style>

</head>
<body>

<div class="content">

    <div class="page-title">Kelola Layanan</div>

    <div class="card-box">

        <!-- SEARCH + FILTER + ADD BUTTON -->
        <div class="action-row">

            <input type="text" id="searchInput" placeholder="Cari nama layanan..." class="form-control" style="max-width:250px;">

            <select id="filterStatus" class="form-control" style="max-width:180px;">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Tidak Aktif</option>
            </select>

            <button class="btn-cleanify" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa fa-plus"></i> Tambah Layanan
            </button>

        </div>

        <!-- TABLE -->
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th style="cursor:pointer;" onclick="sortHarga()">Harga <i class="fa fa-sort"></i></th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="layananData">
                <tr>
                    <td>Deep Cleaning</td>
                    <td>Pembersihan menyeluruh ruangan</td>
                    <td data-harga="250000">Rp 250.000</td>
                    <td>3 Jam</td>
                    <td><span class="status-active">Aktif</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>

                <tr>
                    <td>AC Cleaning</td>
                    <td>Pembersihan AC lengkap</td>
                    <td data-harga="150000">Rp 150.000</td>
                    <td>1 Jam</td>
                    <td><span class="status-off">Tidak Aktif</span></td>
                    <td>
                        <button class="btn btn-warning btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</div>


<!-- MODAL TAMBAH LAYANAN -->
<!-- <div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:12px;">

      <div class="modal-header">
        <h5 class="modal-title">Tambah Layanan</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Nama Layanan</label>
            <input type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga</label>
            <input type="number" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Estimasi Durasi</label>
            <input type="text" class="form-control" placeholder="Contoh: 2 Jam">
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar (opsional)</label>
            <input type="file" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status Layanan</label>
            <select class="form-control">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Tidak Aktif</option>
            </select>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn-cleanify">Simpan</button>
      </div>

    </div>
  </div>
</div> --!>


<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>

    // Search
    document.getElementById("searchInput").addEventListener("keyup", function () {
        let value = this.value.toLowerCase();
        document.querySelectorAll("#layananData tr").forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });

    // Filter Status
    document.getElementById("filterStatus").addEventListener("change", function () {
        let filter = this.value;
        document.querySelectorAll("#layananData tr").forEach(row => {
            let status = row.querySelector("td:nth-child(5)").innerText.toLowerCase();
            row.style.display = filter === "" || status.includes(filter) ? "" : "none";
        });
    });

    // Sort Harga
    let asc = true;
    function sortHarga() {
        let tbody = document.getElementById("layananData");
        let rows = Array.from(tbody.querySelectorAll("tr"));

        rows.sort((a, b) => {
            let h1 = parseInt(a.querySelector("[data-harga]").dataset.harga);
            let h2 = parseInt(b.querySelector("[data-harga]").dataset.harga);
            return asc ? h1 - h2 : h2 - h1;
        });

        asc = !asc;
        rows.forEach(r => tbody.appendChild(r));
    }

</script>

</body>
</html>
