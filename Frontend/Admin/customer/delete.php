<?php
include '../../../Backend/db.php';

if(isset($_GET['id'])){
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM customers WHERE id=$id");
}

header("Location: customer.php");
exit;
?>
