<?php
session_start();
include '../../Backend/db.php';

if (isset($_POST['login'])) {
    $input    = $_POST['email'];  // bisa username atau email
    $password = $_POST['password'];

    // CEK USERNAME ATAU EMAIL
    $sql = "SELECT * FROM users WHERE email='$input' OR username='$input'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // CEK PASSWORD
    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: ../Admin/dashboard.php");
            exit;
        } 
        elseif ($user['role'] === 'cleaner') {
            header("Location: ../Admin/dashboard.php");
            exit;
        } 
        else {
            header("Location: home.php");
            exit;
        }

    } else {
        echo "<script>alert('Email atau password salah!');</script>";
    }
}
?>