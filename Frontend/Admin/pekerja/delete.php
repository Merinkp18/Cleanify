<?php
require __DIR__ . '/../../../Backend/Admin/auth_admin.php';
require __DIR__ . '/../../../Backend/db.php';
require __DIR__ . '/../../../Backend/Logic/employee_logic.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$return = $_GET['return'] ?? 'pekerja';

if ($id > 0) {
    employee_delete($conn, $id);
}

header("Location: ../dashboard.php?page=" . urlencode($return));
exit;
?>
