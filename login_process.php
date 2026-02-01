<?php
session_start();

$role = $_POST['role'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$admin_username = "10123169";
$admin_password = "N1ghtfuryyy";

if ($role === 'admin') {

    if ($username === $admin_username && $password === $admin_password) {
        
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        
        header("Location: login.php?role=admin&error=Username atau Password salah!");
        exit;
    }

} else {
    
    header("Location: index.php");
    exit;
}
