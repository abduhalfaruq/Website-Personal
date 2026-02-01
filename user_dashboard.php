<?php
session_start();
include "connect.php";

function generateIdTransaksi() {
    return '#' . str_pad(rand(0, 999999999999999), 15, '0', STR_PAD_LEFT);
}

/* ================= CART ================= */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* TAMBAH KE KERANJANG */
if (isset($_GET['add'])) {
    $id = $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header("Location: user_dashboard.php?success_add=1");
    exit;
}

/* TAMBAH QTY */
if (isset($_GET['plus'])) {
    $_SESSION['cart'][$_GET['plus']]++;
    header("Location: user_dashboard.php?page=cart");
    exit;
}

/* KURANGI QTY */
if (isset($_GET['minus'])) {
    $_SESSION['cart'][$_GET['minus']]--;
    if ($_SESSION['cart'][$_GET['minus']] <= 0) {
        unset($_SESSION['cart'][$_GET['minus']]);
    }
    header("Location: user_dashboard.php?page=cart");
    exit;
}

$page   = $_GET['page']   ?? 'katalog';
$search = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>User Dashboard | Plaza Top Up</title>
<link rel="icon" href="Plaza Top Up.png">

<style>
body { background:#3A3A3A; font-family:Arial; }
.container { background:#fff; margin:30px; padding:30px; border-radius:14px; }

.nav {
    display:flex; align-items:center; margin-bottom:20px;
}
.nav a {
    background:#E6C11F; padding:10px 16px;
    margin-right:10px; border-radius:8px;
    font-weight:bold; text-decoration:none; color:#000;
}
.logout {
    margin-left:auto;
    background:#d9534f !important;
    color:white !important;
}

/* SEARCH */
.search-box {
    display:flex;
    justify-content:flex-end;
    margin-bottom:20px;
    gap:8px;
}
.search-box input {
    padding:8px;
    width:260px;
}
.search-box button {
    background:#E6C11F;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    font-weight:bold;
}

.btn-back {
    background:#999; color:white; padding:8px 14px;
    border-radius:6px; text-decoration:none; font-weight:bold;
}

/* KATALOG */
.grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
}
.card {
    border:1px solid #ddd;
    padding:15px;
    border-radius:10px;
    text-align:center;
}
.card h4 { margin:10px 0; }
.card a {
    display:inline-block; margin-top:10px;
    background:#E6C11F; padding:8px 12px;
    border-radius:6px; text-decoration:none;
    font-weight:bold; color:#000;
}

/* TABLE */
table { width:100%; border-collapse:collapse; }
th,td { padding:12px; text-align:center; }
th { background:#E6C11F; }
td { border-bottom:1px solid #ddd; }

/* BUTTON */
.btn {
    padding:6px 10px;
    border-radius:6px;
    font-weight:bold;
    text-decoration:none;
}
.btn-plus { background:#5cb85c; color:white; }
.btn-minus { background:#d9534f; color:white; }

/* FORM */
.form-pay {
    margin-top:20px;
    display:flex; gap:10px;
}
.form-pay input {
    padding:8px; width:30%;
}
.form-pay button {
    background:#E6C11F; border:none;
    padding:8px 16px; font-weight:bold;
    border-radius:6px;
}

/* ALERT */
.success {
    background:#dff0d8;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
}
</style>
</head>

<body>

<div class="container">
<h2>User Dashboard</h2>

<div class="nav">
    <a href="user_dashboard.php">Katalog</a>
    <a href="user_dashboard.php?page=cart">Keranjang</a>
    <a href="user_logout.php" class="logout">Logout</a>
</div>

<?php if (isset($_GET['success_add'])) { ?>
<div class="success">✅ Diamond berhasil masuk ke keranjang</div>
<?php } ?>

<!-- ================= KATALOG ================= -->
<?php if ($page == 'katalog') { ?>

<form class="search-box">
    <input type="text" name="search" placeholder="Cari Diamond / Harga"
           value="<?= $search ?>">
    <button>Cari 🔎</button>
    <a href="?page=katalog" class="btn-back">Reset</a>
</form>

<div class="grid">
<?php
$sql = "SELECT * FROM harga
        WHERE jenis_diamond LIKE '%$search%'
        OR harga LIKE '%$search%'";
$q = mysqli_query($conn,$sql);

while ($d = mysqli_fetch_assoc($q)) {
?>
<div class="card">
    <h4><?= strtoupper($d['jenis_diamond']) ?></h4>
    <p>Rp <?= $d['harga'] ?></p>
    <a href="?add=<?= $d['id_diamond'] ?>">+ Keranjang</a>
</div>
<?php } ?>
</div>
<?php } ?>

<!-- ================= KERANJANG ================= -->
<?php if ($page == 'cart') { ?>
<table>
<tr>
    <th>Diamond</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Aksi</th>
</tr>

<?php
$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $d = mysqli_fetch_assoc(
        mysqli_query($conn,"SELECT * FROM harga WHERE id_diamond='$id'")
    );
    $total += $d['harga'] * $qty;
?>
<tr>
    <td><?= strtoupper($d['jenis_diamond']) ?></td>
    <td>Rp <?= $d['harga'] ?></td>
    <td><?= $qty ?></td>
    <td>
        <a class="btn btn-plus" href="?page=cart&plus=<?= $id ?>">+</a>
        <a class="btn btn-minus" href="?page=cart&minus=<?= $id ?>">−</a>
    </td>
</tr>
<?php } ?>

<tr>
    <th colspan="3">Total</th>
    <th>Rp <?= $total ?></th>
</tr>
</table>

<?php if (!empty($_SESSION['cart'])) { ?>
<p>Silakan masukan ID dan username akun Mobile Legends anda!</p>
<form method="POST" action="payment.php" class="form-pay">
    <input name="id_game" placeholder="ID Game" required>
    <input name="username" placeholder="Username" required>
    <button name="bayar">Bayar Sekarang</button>
</form>
<?php } ?>

<?php } ?>

</div>
</body>
</html>