<?php
include '../../../Backend/db.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM orders WHERE id=$id");
}

header("Location: order.php");
exit;
?>
