<?php
session_start();
include "connect.php";

if (empty($_SESSION['cart'])) {
    header("Location: user_dashboard.php");
    exit;
}

$id_game  = $_POST['id_game']  ?? '';
$username = $_POST['username'] ?? '';

if ($id_game == '' || $username == '') {
    header("Location: user_dashboard.php?page=cart");
    exit;
}

mysqli_query($conn, "
    UPDATE username
    SET username = '$username'
    WHERE id_game = '$id_game'
");
/* ===== END TAMBAHAN ===== */

/* SIMPAN DATA USER KE SESSION */
$_SESSION['pay_id_game']  = $id_game;
$_SESSION['pay_username'] = $username;

/* GENERATE ID ORDER */
function generateOrderID() {
    return '#' . str_pad(rand(0, 999999999999999), 15, '0', STR_PAD_LEFT);
}

$_SESSION['id_order'] = generateOrderID();

/* HITUNG TOTAL */
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $d = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT harga FROM harga WHERE id_diamond='$id'")
    );
    $total += $d['harga'] * $qty;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran | Plaza Top Up</title>
<link rel="icon" href="Plaza Top Up.png">

<style>
body { background:#3A3A3A; font-family:Arial; }
.container {
    background:#fff; margin:40px auto; padding:30px;
    width:500px; border-radius:14px; text-align:center;
}
.qris {
    margin:20px 0;
}
.qris img {
    width:220px;
}
.btn {
    background:#5cb85c; color:white;
    padding:10px 18px; border-radius:8px;
    text-decoration:none; font-weight:bold;
    display:inline-block;
}
.info {
    background:#f5f5f5; padding:12px;
    border-radius:8px; margin-top:15px;
}
</style>
</head>

<body>

<div class="container">
<h2>Pembayaran QRIS</h2>

<div class="info">
    <p><b>ID Order:</b> <?= $_SESSION['id_order'] ?></p>
    <p><b>ID Game:</b> <?= $id_game ?></p>
    <p><b>Username:</b> <?= $username ?></p>
    <p><b>Total Bayar:</b> Rp <?= ($total) ?></p>
</div>

<div class="qris">
    <p>Scan QRIS di bawah ini</p>
    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=PLAZA_TOPUP_QRIS">
</div>

<a class="btn" href="payment_success.php">Saya Sudah Bayar</a>

</div>

</body>
</html>
