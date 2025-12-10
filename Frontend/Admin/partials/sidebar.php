<?php
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

<a href="../../Backend/logout.php" style="display:block;padding:10px 12px;border-radius:10px;background:#fff3f3;">
  Logout
</a>
