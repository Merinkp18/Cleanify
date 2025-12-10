<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/service_logic.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$return = $_GET['return'] ?? 'layanan';

if ($id > 0) {
    service_delete($conn, $id);
}

header("Location: ../dashboard.php?page=" . urlencode($return));
exit;
?>
