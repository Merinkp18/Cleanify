<?php
$host = "localhost";  
$user = "root";       
$pass = "";           
$db   = "cleanify";   

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
} else {
    // echo "Koneksi berhasil!"; // aktifkan ini buat ngetes
}
?>
