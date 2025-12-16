<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require __DIR__ . '/../../Backend/db.php';

// URL login (sesuaikan kalau beda)
$loginUrl = '/cleanify/Frontend/Akun/login.php';

// Wajib login untuk booking
$isLoggedIn = isset($_SESSION['user_id'], $_SESSION['role']) && $_SESSION['role'] === 'user';
if (!$isLoggedIn) {
  $next = urlencode('/cleanify/Frontend/User/booking.php' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
  header("Location: {$loginUrl}?next={$next}");
  exit;
}

$customerId = (int)$_SESSION['user_id'];

// ambil services active
$services = [];
$serviceId = (int)($_GET['service_id'] ?? $_POST['service_id'] ?? 0);

$stmt = $conn->prepare("SELECT id, name, short_description, price, duration_minutes FROM services WHERE status='active' ORDER BY price ASC");
$stmt->execute();
$res = $stmt->get_result();
$services = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

$selectedService = null;
if ($serviceId > 0) {
  $stmt = $conn->prepare("SELECT id, name, short_description, price, duration_minutes FROM services WHERE id=? AND status='active' LIMIT 1");
  $stmt->bind_param("i", $serviceId);
  $stmt->execute();
  $r = $stmt->get_result();
  $selectedService = $r ? $r->fetch_assoc() : null;
}

$errors = [];
$success = false;

// helper order code
function makeOrderCode(): string {
  $ymd = date('Ymd');
  $rand = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
  return "ORD-{$ymd}-{$rand}";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $serviceId = (int)($_POST['service_id'] ?? 0);
  $scheduledDate = trim($_POST['scheduled_date'] ?? '');
  $startTime = trim($_POST['scheduled_start_time'] ?? '');
  $notes = trim($_POST['notes'] ?? ''); // tidak disimpan ke orders (karena kolom tidak ada)

  // validasi service (biar gak "service tidak valid")
  $stmt = $conn->prepare("SELECT id, name, price, duration_minutes FROM services WHERE id=? AND status='active' LIMIT 1");
  $stmt->bind_param("i", $serviceId);
  $stmt->execute();
  $r = $stmt->get_result();
  $svc = $r ? $r->fetch_assoc() : null;

  if (!$svc) $errors[] = "Service tidak valid / tidak aktif.";
  if ($scheduledDate === '') $errors[] = "Tanggal jadwal wajib diisi.";
  if ($startTime === '') $errors[] = "Jam mulai wajib diisi.";

  // hitung end time otomatis dari duration_minutes
  $endTime = null;
  if ($svc && $scheduledDate !== '' && $startTime !== '') {
    $minutes = (int)$svc['duration_minutes'];
    $startDT = DateTime::createFromFormat('H:i', $startTime);
    if (!$startDT) {
      $errors[] = "Format jam mulai tidak valid.";
    } else {
      $endDT = clone $startDT;
      $endDT->modify("+{$minutes} minutes");
      $endTime = $endDT->format('H:i:s');
      $startTime = $startDT->format('H:i:s');
    }
  }

  if (!$errors) {
    $orderCode = makeOrderCode();
    $totalCost = (float)$svc['price'];

    // Insert sesuai kolom orders (order_code, customer_id, service_id, scheduled_date, scheduled_start_time, scheduled_end_time, total_cost)
    // Status default 'baru' sesuai table.
    $stmt = $conn->prepare("
      INSERT INTO orders (order_code, customer_id, service_id, scheduled_date, scheduled_start_time, scheduled_end_time, total_cost)
      VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
      "siisssd",
      $orderCode,
      $customerId,
      $serviceId,
      $scheduledDate,
      $startTime,
      $endTime,
      $totalCost
    );

    if ($stmt->execute()) {
      $success = true;

      // refresh selected service
      $selectedService = [
        'id' => $svc['id'],
        'name' => $svc['name'],
        'price' => $svc['price'],
        'duration_minutes' => $svc['duration_minutes']
      ];
    } else {
      $errors[] = "Gagal membuat order. Coba lagi.";
    }
  }
}

// default pilih pertama kalau belum ada
if (!$selectedService && !empty($services)) {
  $selectedService = $services[0];
  if ($serviceId <= 0) $serviceId = (int)$selectedService['id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking - Cleanify</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    body{margin:0;font-family:Inter,sans-serif;background:#e6e6e6;color:#111;}
    .wrap{max-width:980px;margin:40px auto;padding:0 18px;}
    .card{
      border-radius:18px; overflow:hidden; background:#fff;
      box-shadow:0 18px 50px rgba(0,0,0,.12);
      display:grid; grid-template-columns: 1.2fr .9fr;
      border:1px solid rgba(255,255,255,.6);
    }
    @media(max-width:900px){ .card{grid-template-columns:1fr;} }

    .left{padding:28px 28px 26px;background:#fbfbfb;}
    .right{padding:26px;background:linear-gradient(135deg,#0f0b3e,#1a0d5e 45%,#2b0a57); color:#fff;}

    .title{font-size:18px;font-weight:800;margin:0 0 12px;}
    .sub{color:#667085;font-size:13px;margin:0 0 18px;}
    .row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
    @media(max-width:520px){ .row{grid-template-columns:1fr;} }

    label{font-size:12px;color:#475467;font-weight:600;display:block;margin:10px 0 6px;}
    input, select, textarea{
      width:100%; padding:12px 12px; border-radius:10px;
      border:1px solid #e4e7ec; outline:none; background:#fff;
      font-family:inherit;
    }
    textarea{min-height:90px;resize:vertical;}
    .btn{
      width:100%; margin-top:16px;
      background:#1a33ff; color:#fff; border:none; border-radius:10px;
      padding:12px 14px; font-weight:800; cursor:pointer;
    }
    .alert{margin:10px 0 0;padding:12px 12px;border-radius:10px;font-size:13px;}
    .alert.err{background:#ffe7e7;color:#8a1f1f;border:1px solid #ffbcbc;}
    .alert.ok{background:#e7fff0;color:#0f6a2a;border:1px solid #bff0cc;}

    .cartTitle{font-size:18px;font-weight:900;margin:0 0 14px;}
    .cartBox{
      background:rgba(255,255,255,.10);
      border:1px solid rgba(255,255,255,.14);
      border-radius:14px; padding:14px;
    }
    .cartItem{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 0;border-bottom:1px solid rgba(255,255,255,.12);}
    .cartItem:last-child{border-bottom:none;}
    .itemLeft{display:flex;align-items:center;gap:10px;min-width:0;}
    .thumb{
      width:36px;height:36px;border-radius:10px;background:rgba(255,255,255,.18);
      display:flex;align-items:center;justify-content:center;font-weight:900;
    }
    .itemName{font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:220px;}
    .itemMeta{font-size:12px;color:rgba(255,255,255,.75);}
    .price{font-weight:900;}

    .details{margin-top:16px;background:rgba(0,0,0,.18);border-radius:14px;padding:14px;border:1px solid rgba(255,255,255,.12);}
    .drow{display:flex;justify-content:space-between;align-items:center;padding:7px 0;color:rgba(255,255,255,.88);font-size:13px;}
    .drow b{color:#fff;}
    .total{margin-top:10px;padding-top:10px;border-top:1px solid rgba(255,255,255,.12);font-size:14px;}
    .hint{font-size:12px;color:#6b7280;margin-top:8px;}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <!-- LEFT: FORM -->
      <div class="left">
        <h1 class="title">Checkout</h1>
        <p class="sub">Isi jadwal booking kamu. Service dipilih dari database.</p>

        <?php if (!empty($errors)): ?>
          <div class="alert err">
            <?php foreach ($errors as $e): ?>
              • <?= htmlspecialchars($e) ?><br>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="alert ok">
            Order berhasil dibuat ✅ (status: <b>baru</b>). Silakan lanjut sesuai alur pembayaran kamu.
          </div>
        <?php endif; ?>

        <form method="POST">
          <label>Service</label>
          <select name="service_id" id="serviceSelect" required>
            <?php foreach ($services as $s): ?>
              <option
                value="<?= (int)$s['id'] ?>"
                <?= ((int)$serviceId === (int)$s['id']) ? 'selected' : '' ?>
                data-price="<?= htmlspecialchars((string)$s['price']) ?>"
                data-duration="<?= (int)$s['duration_minutes'] ?>"
              >
                <?= htmlspecialchars($s['name']) ?> — Rp <?= number_format((float)$s['price'],0,',','.') ?>
              </option>
            <?php endforeach; ?>
          </select>

          <div class="row">
            <div>
              <label>Tanggal Jadwal</label>
              <input type="date" name="scheduled_date" value="<?= htmlspecialchars($_POST['scheduled_date'] ?? '') ?>" required>
            </div>
            <div>
              <label>Jam Mulai</label>
              <input type="time" name="scheduled_start_time" value="<?= htmlspecialchars($_POST['scheduled_start_time'] ?? '') ?>" required>
            </div>
          </div>

          <label>Catatan (opsional)</label>
          <textarea name="notes" placeholder="Contoh: fokus area dapur, bawa alat sendiri, dll."><?= htmlspecialchars($_POST['notes'] ?? '') ?></textarea>

          <button class="btn" type="submit">Proceed</button>
          <div class="hint">* Jam selesai akan dihitung otomatis dari durasi service.</div>
        </form>
      </div>

      <!-- RIGHT: SUMMARY -->
      <div class="right">
        <div class="cartTitle">Your Cart</div>

        <div class="cartBox" id="cartBox">
          <div class="cartItem">
            <div class="itemLeft">
              <div class="thumb">🧽</div>
              <div style="min-width:0;">
                <div class="itemName" id="svcName">
                  <?= htmlspecialchars($selectedService['name'] ?? 'Service') ?>
                </div>
                <div class="itemMeta">
                  Durasi: <span id="svcDur"><?= (int)($selectedService['duration_minutes'] ?? 0) ?></span> menit
                </div>
              </div>
            </div>
            <div class="price" id="svcPrice">
              Rp <?= number_format((float)($selectedService['price'] ?? 0),0,',','.') ?>
            </div>
          </div>
        </div>

        <div class="details">
          <div class="drow"><span>Items</span><b id="itemsCost">Rp <?= number_format((float)($selectedService['price'] ?? 0),0,',','.') ?></b></div>
          <div class="drow"><span>VAT</span><b>Rp 0</b></div>
          <div class="drow"><span>Shipping</span><b>Rp 0</b></div>
          <div class="drow total"><span>Total</span><b id="totalCost">Rp <?= number_format((float)($selectedService['price'] ?? 0),0,',','.') ?></b></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // update summary saat ganti service (tanpa reload)
    const sel = document.getElementById('serviceSelect');
    const svcName = document.getElementById('svcName');
    const svcDur = document.getElementById('svcDur');
    const svcPrice = document.getElementById('svcPrice');
    const itemsCost = document.getElementById('itemsCost');
    const totalCost = document.getElementById('totalCost');

    function formatRupiah(n){
      n = Number(n || 0);
      return "Rp " + n.toLocaleString('id-ID');
    }

    function updateSummary(){
      const opt = sel.options[sel.selectedIndex];
      const name = opt.text.split('—')[0].trim();
      const price = opt.getAttribute('data-price') || 0;
      const dur = opt.getAttribute('data-duration') || 0;

      svcName.textContent = name;
      svcDur.textContent = dur;
      svcPrice.textContent = formatRupiah(price);
      itemsCost.textContent = formatRupiah(price);
      totalCost.textContent = formatRupiah(price);
    }
    if (sel) sel.addEventListener('change', updateSummary);
  </script>
</body>
</html>
