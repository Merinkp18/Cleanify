<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pekerja | Cleanify Admin</title>

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

    /* Card tampilan */
    .card-box {
        background: #fff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 3px 8px rgba(0,0,0,.1);
    }

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

    /* Table */
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
        padding: 10px;
        border: 1px solid #dcdcdc;
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

    .rating {
        background: #f1c40f;
        padding: 4px 10px;
        border-radius: 8px;
        color: #fff;
        font-size: 12px;
    }

    .photo-profile {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #41aba0;
    }

</style>
</head>
<body>

<div class="content">

    <div class="page-title">Kelola Pekerja</div>

    <div class="card-box">

        <!-- SEARCH + FILTER + ADD -->
        <div class="action-row">

            <input type="text" id="searchInput" placeholder="Cari nama/email..." class="form-control" style="max-width:250px;">

            <select id="filterStatus" class="form-control" style="max-width:180px;">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Tidak Aktif</option>
            </select>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa fa-plus"></i> Tambah Pekerja
            </button>
        </div>

        <!-- TABEL PEKERJA -->
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nomor HP</th>
                    <th>Keahlian</th>
                    <th>Job Selesai</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="cleanerData">

                <tr>
                    <td><img src="assets/cleaner1.jpg" class="photo-profile"></td>
                    <td>Rani Putri</td>
                    <td>rani@gmail.com</td>
                    <td>081234567890</td>
                    <td>Deep Cleaning</td>
                    <td>32</td>
                    <td><span class="rating">⭐ 4.8</span></td>
                    <td><span class="status-active">Aktif</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="openDetail()">Detail</button>
                        <button class="btn btn-warning btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>

                <tr>
                    <td><img src="assets/cleaner2.jpg" class="photo-profile"></td>
                    <td>Doni Saputra</td>
                    <td>doni@gmail.com</td>
                    <td>081293847566</td>
                    <td>AC Cleaning</td>
                    <td>20</td>
                    <td><span class="rating">⭐ 4.4</span></td>
                    <td><span class="status-off">Tidak Aktif</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="openDetail()">Detail</button>
                        <button class="btn btn-warning btn-sm">Edit</button>
                        <button class="btn btn-danger btn-sm">Hapus</button>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>

</div>

<!-- ===================== MODAL TAMBAH ===================== -->
<!--<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:12px;">

      <div class="modal-header">
        <h5 class="modal-title">Tambah Pekerja</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor HP</label>
            <input type="text" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Keahlian</label>
            <select class="form-control">
                <option>Deep Cleaning</option>
                <option>Regular Cleaning</option>
                <option>AC Cleaning</option>
                <option>Office Cleaning</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Foto</label>
            <input type="file" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-control">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Tidak Aktif</option>
            </select>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success">Simpan</button>
      </div>

    </div>
  </div>
</div>


<!-- ===================== MODAL DETAIL CLEANER ===================== -->
<!--<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:12px;">

      <div class="modal-header">
        <h5 class="modal-title">Detail Cleaner</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <h6><b>Profil Cleaner</b></h6>
        <p><b>Nama:</b> Rani Putri</p>
        <p><b>Email:</b> rani@gmail.com</p>
        <p><b>HP:</b> 081234567890</p>
        <p><b>Keahlian:</b> Deep Cleaning</p>

        <hr>

        <h6><b>Kinerja</b></h6>
        <ul>
            <li>32 Job selesai</li>
            <li>Rating ⭐ 4.8</li>
            <li>Last job: 18 Nov 2025</li>
        </ul>

        <hr>

        <h6><b>Jadwal Hari Ini</b></h6>
        <p>09:00 - 11:00 • Deep Cleaning • Order #00123</p>

        <hr>

        <h6><b>Penilaian Pelanggan</b></h6>
        <ul>
            <li>"Kerja bersih dan cepat" – ⭐ 5</li>
            <li>"Ramah dan teliti" – ⭐ 4</li>
        </ul>

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
    document.querySelectorAll("#cleanerData tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? "" : "none";
    });
});

// === FILTER STATUS ===
document.getElementById("filterStatus").addEventListener("change", function () {
    let filter = this.value;
    document.querySelectorAll("#cleanerData tr").forEach(row => {
        let status = row.querySelector("td:nth-child(8)").innerText.toLowerCase();
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
