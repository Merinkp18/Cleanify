<?php
include '../../../Backend/db.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM services WHERE id=$id");
}

header("Location: layanan.php");
exit;
?>
