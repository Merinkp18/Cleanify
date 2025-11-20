<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Jadwal | Cleanify Admin</title>

<!-- Bootstrap -->
<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<!-- Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- FullCalendar -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet"/>

<style>
    body {
        background: #f5f6fa;
        font-family: 'Lato', sans-serif;
    }

    .content {
        margin-left: 20px;
        padding: 30px;
    }

    .card-box {
        background: #fff;
        padding: 25px;
        border-radius: 14px;
        box-shadow: 0 3px 8px rgba(0,0,0,.1);
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .status-scheduled {
        background: #3498db;
        padding: 6px 12px;
        border-radius: 8px;
        color: white;
        font-size: 12px;
    }

    .status-completed {
        background: #2ecc71;
        padding: 6px 12px;
        border-radius: 8px;
        color: white;
        font-size: 12px;
    }

    table tbody tr {
        background: #fff;
    }
	.schedule-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
    font-size: 14px;
    background: #fff;
	}

	.schedule-table th,
	.schedule-table td {
		border: 1px solid #d1d1d1;   /* GARIS KOTAK-KOTAK */
		padding: 10px;
		text-align: center;
	}

	.schedule-table thead {
		background: #41aba0;
		color: white;
		font-weight: bold;
	}

	.badge {
		padding: 5px 10px;
		border-radius: 6px;
		font-size: 12px;
		color: white;
	}

	.scheduled {
		background: #3498db;
	}

	.completed {
		background: #2ecc71;
	}

</style>

</head>
<body>

<div class="content">

    <div class="page-title">Kelola Jadwal</div>

    <!-- ========================================================= -->
    <!-- KALENDER -->
    <!-- ========================================================= -->
    <div class="card-box">
        <h5 class="mb-3">Kalender Jadwal</h5>
        <div id="calendar"></div>
    </div>

    <!-- ========================================================= -->
    <!-- FILTER + TABEL JADWAL -->
    <!-- ========================================================= -->
    <div class="card-box">

        <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
            <select id="filterCleaner" class="form-control" style="max-width:200px;">
                <option value="">Semua Cleaner</option>
                <option value="Rani">Rani</option>
                <option value="Doni">Doni</option>
                <option value="Sinta">Sinta</option>
            </select>

            <select id="filterService" class="form-control" style="max-width:200px;">
                <option value="">Semua Layanan</option>
                <option value="Deep Cleaning">Deep Cleaning</option>
                <option value="AC Cleaning">AC Cleaning</option>
                <option value="Office Cleaning">Office Cleaning</option>
            </select>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa fa-plus"></i> Tambah Jadwal
            </button>
        </div>

        <!-- TABEL -->
        <table class="schedule-table">
    <thead>
        <tr>
            <th>ID Jadwal</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Layanan</th>
            <th>Cleaner</th>
            <th>ID Order</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td>#SCH001</td>
            <td>20 Nov 2025</td>
            <td>09:00 - 11:00</td>
            <td>Deep Cleaning</td>
            <td>Rani</td>
            <td>#00123</td>
            <td><span class="badge scheduled">Scheduled</span></td>
        </tr>

        <tr>
            <td>#SCH002</td>
            <td>21 Nov 2025</td>
            <td>13:00 - 15:00</td>
            <td>AC Cleaning</td>
            <td>Doni</td>
            <td>#00124</td>
            <td><span class="badge completed">Completed</span></td>
        </tr>
    </tbody>
</table>



    </div>
</div>

<!-- ========================================================= -->
<!-- MODAL TAMBAH JADWAL -->
<!-- ========================================================= -->
<!--<div class="modal fade" id="modalTambah" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Tambah Jadwal</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Pilih Order</label>
            <select class="form-control">
                <option>#00123 - Deep Cleaning</option>
                <option>#00124 - AC Cleaning</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Jam</label>
            <input type="time" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Cleaner</label>
            <select class="form-control">
                <option>Rani</option>
                <option>Doni</option>
                <option>Sinta</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-control">
                <option>Scheduled</option>
                <option>Completed</option>
            </select>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-success">Simpan</button>
      </div>

    </div>
  </div>
</div>--!>

<!-- ========================================================= -->
<!-- FULLCALENDAR SCRIPT + BOOTSTRAP -->
<!-- ========================================================= -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>

<script>
    // =======================================================
    // FULLCALENDAR CONFIG
    // =======================================================
    document.addEventListener('DOMContentLoaded', function () {

        let calendarEl = document.getElementById('calendar');

        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 650,
            events: [
                {
                    title: 'Deep Cleaning - Rani',
                    start: '2025-11-20',
                    color: '#3498db'
                },
                {
                    title: 'AC Cleaning - Doni',
                    start: '2025-11-21',
                    color: '#2ecc71'
                }
            ]
        });

        calendar.render();
    });


    // =======================================================
    // FILTER TABLE BY CLEANER OR SERVICE
    // =======================================================
    document.getElementById("filterCleaner").addEventListener("change", filterTable);
    document.getElementById("filterService").addEventListener("change", filterTable);

    function filterTable() {
        let cleaner = document.getElementById("filterCleaner").value.toLowerCase();
        let service = document.getElementById("filterService").value.toLowerCase();

        document.querySelectorAll("#jadwalTable tr").forEach(row => {
            let cleanerText = row.children[4].innerText.toLowerCase();
            let serviceText = row.children[3].innerText.toLowerCase();

            row.style.display =
                (cleaner === "" || cleanerText.includes(cleaner)) &&
                (service === "" || serviceText.includes(service))
                ? "" : "none";
        });
    }
</script>

</body>
</html>
