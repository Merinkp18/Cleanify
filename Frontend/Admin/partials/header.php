<?php
$titleMap = [
  'home' => 'Dashboard',
  'pekerja' => 'Pekerja',
  'order' => 'Order',
  'jadwal' => 'Jadwal',
  'layanan' => 'Layanan',
];
$page = $_GET['page'] ?? 'home';
$title = $titleMap[$page] ?? 'Dashboard';
?>
<header>
  <div>
    <div style="font-size:18px;font-weight:800;"><?= htmlspecialchars($title) ?></div>
    <div style="font-size:12px;color:#666;">Kelola data aplikasi</div>
  </div>

  <div style="display:flex;gap:10px;align-items:center;">
    <div class="card" style="padding:8px 12px;">
      <?= htmlspecialchars($_SESSION['username'] ?? 'admin') ?>
      <span style="color:#666;font-size:12px;">(<?= htmlspecialchars($_SESSION['role'] ?? '-') ?>)</span>
    </div>
  </div>
</header>
