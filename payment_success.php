<?php
session_start();
include "connect.php";

if (!isset($_SESSION['id_order'])) {
    header("Location: user_dashboard.php");
    exit;
}

$id_order = $_SESSION['id_order'];
$id_game  = $_SESSION['pay_id_game'];
$username = $_SESSION['pay_username'];

/* SIMPAN USER (AMAN DARI DUPLIKAT) */
mysqli_query($conn,"
    INSERT IGNORE INTO username (id_game, username)
    VALUES ('$id_game','$username')
");

/* SIMPAN TRANSAKSI */
foreach ($_SESSION['cart'] as $id_diamond => $qty) {
    for ($i=0; $i<$qty; $i++) {
        mysqli_query($conn,"
            INSERT INTO transaksi (id_order, id_game, id_diamond, tanggal, waktu)
            VALUES ('$id_order','$id_game','$id_diamond',CURDATE(),CURTIME())
        ");
    }
}

/* RESET SESSION */
unset($_SESSION['cart']);
unset($_SESSION['id_order']);
unset($_SESSION['pay_id_game']);
unset($_SESSION['pay_username']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pembayaran Berhasil</title>
<link rel="icon" href="Plaza Top Up.png">

<style>
body { background:#3A3A3A; font-family:Arial; }
.container {
    background:#fff; margin:60px auto;
    padding:30px; width:450px;
    border-radius:14px; text-align:center;
}
.success {
    background:#dff0d8; padding:15px;
    border-radius:8px; margin-bottom:20px;
}
.btn {
    background:#E6C11F; padding:10px 18px;
    border-radius:8px; text-decoration:none;
    font-weight:bold; color:#000;
}
</style>
</head>

<body>

<div class="container">
<div class="success">
    <h3>✅ Pembayaran Berhasil</h3>
    <p>Terima kasih telah melakukan top up</p>
</div>
<p></p>
<a class="btn" href="user_dashboard.php">Kembali ke Katalog</a>
</div>

</body>
</html>
