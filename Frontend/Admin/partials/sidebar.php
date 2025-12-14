<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// CSRF token untuk aksi sensitif (logout, delete, dll)
if (empty($_SESSION['csrf'])) {
  $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$current = $_GET['page'] ?? 'home';
function navItem(string $key, string $label, string $current) {
  $active = $key === $current ? 'active' : '';
  echo '<a class="'.$active.'" href="dashboard.php?page='.htmlspecialchars($key).'">'.htmlspecialchars($label).'</a>';
}
?>
<div style="display:flex;gap:10px;align-items:center;margin-bottom:14px;">
  <div style="width:36px;height:36px;border-radius:12px;background:#111;"></div>
  <div>
    <div style="font-weight:800;">Cleanify</div>
    <div style="font-size:12px;color:#666;">Admin Panel</div>
  </div>
</div>

<nav class="nav">
  <?php navItem('home', 'Home', $current); ?>
  <?php navItem('pekerja', 'Pekerja', $current); ?>
  <?php navItem('order', 'Order', $current); ?>
  <?php navItem('jadwal', 'Jadwal', $current); ?>
  <?php navItem('layanan', 'Layanan', $current); ?>
</nav>

<hr style="border:none;border-top:1px solid #eee;margin:14px 0">

<form action="../../Backend/logout.php" method="POST" style="margin:0;">
  <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf']) ?>">
  <button type="submit"
    style="display:block;width:100%;text-align:left;padding:10px 12px;border-radius:10px;background:#fff3f3;border:none;cursor:pointer;">
    Logout
  </button>
</form>
