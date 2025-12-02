<?php
include '../../../Backend/db.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM schedules WHERE id=$id");
}

header("Location: jadwal.php");
exit;
?>
