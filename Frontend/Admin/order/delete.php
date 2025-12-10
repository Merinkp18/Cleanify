<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/order_logic.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$return = $_GET['return'] ?? 'order';

if ($id > 0) {
    order_delete($conn, $id);
}

header("Location: ../dashboard.php?page=" . urlencode($return));
exit;
?>
