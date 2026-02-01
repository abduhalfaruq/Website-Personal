<?php
session_start();

$_SESSION['role'] = 'user';
$_SESSION['username'] = 'Pembeli';

header("Location: user_dashboard.php");
exit;
