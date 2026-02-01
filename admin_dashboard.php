<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}
include "connect.php";

/* ===== PARAMETER ===== */
$page   = $_GET['page']   ?? 'harga';
$search = $_GET['search'] ?? '';
$sort   = $_GET['sort']   ?? '';
$order  = $_GET['order']  ?? 'asc';
$order  = ($order === 'desc') ? 'desc' : 'asc';

/* ===== SORT LINK ===== */
function sortLink($label, $column, $page, $search, $sort, $order) {
    $arrow = '';
    if ($sort === $column) {
        $arrow = ($order === 'asc') ? ' ▲' : ' ▼';
    }

    $newOrder = ($sort === $column && $order === 'asc') ? 'desc' : 'asc';

    return "<a href='?page=$page&search=$search&sort=$column&order=$newOrder'>
                $label<span class='arrow'>$arrow</span>
            </a>";
}

/* ===== CRUD HARGA ===== */
if (isset($_POST['add_harga'])) {
    mysqli_query($conn, "INSERT INTO harga VALUES (
        '$_POST[id_diamond]',
        '$_POST[jenis_diamond]',
        '$_POST[harga]'
    )");
    header("Location: admin_dashboard.php?page=harga");
    exit;
}

if (isset($_POST['edit_harga'])) {
    mysqli_query($conn, "UPDATE harga SET
        jenis_diamond='$_POST[jenis_diamond]',
        harga='$_POST[harga]'
        WHERE id_diamond='$_POST[id_diamond]'
    ");
    header("Location: admin_dashboard.php?page=harga");
    exit;
}

if (isset($_GET['hapus'])) {
    mysqli_query($conn, "DELETE FROM harga WHERE id_diamond='$_GET[hapus]'");
    header("Location: admin_dashboard.php?page=harga");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Plaza Top Up</title>
<link rel="icon" href="Plaza Top Up.png">

<style>
body { background:#3A3A3A; font-family:Arial; }
.container { background:#fff; margin:30px; padding:30px; border-radius:14px; }

.nav { display:flex; align-items:center; margin-bottom:25px; }
.nav-left { display:flex; gap:12px; }
.nav-left a {
    padding:10px 16px; background:#E6C11F; color:#3A3A3A;
    text-decoration:none; font-weight:bold; border-radius:8px;
}
.logout {
    margin-left:auto; background:#d9534f; color:white;
    padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:bold;
}

/* SEARCH */
.search-row {
    display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;
}
.search-box { display:flex; gap:8px; }
.search-box input { padding:8px; width:280px; }
.search-box button {
    padding:8px 14px; border:none; font-weight:bold;
    border-radius:6px; cursor:pointer; background:#E6C11F;
}
.btn-back {
    background:#999; color:white; padding:8px 14px;
    border-radius:6px; text-decoration:none; font-weight:bold;
}

/* FORM TAMBAH */
.form-inline { display:flex; gap:10px; margin-bottom:15px; }
.form-inline input { padding:8px; width:25%; }
.form-inline button {
    background:#E6C11F; border:none; padding:8px 16px;
    font-weight:bold; border-radius:6px; cursor:pointer;
}

/* TABLE */
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; text-align:center; vertical-align:middle; }
th { background:#E6C11F; }
td { border-bottom:1px solid #ddd; }
th a { color:#000; text-decoration:none; }

/* AKSI */
.aksi { display:flex; justify-content:center; gap:8px; }
.btn-update {
    background:#E6C11F; border:none; padding:6px 12px;
    border-radius:6px; font-weight:bold; cursor:pointer;
}
.btn-delete {
    background:#d9534f; color:white; padding:6px 12px;
    border-radius:6px; text-decoration:none; font-weight:bold;
}
table input { width:100%; padding:6px; }

th a {
    color:#000;
    text-decoration:none;
    font-weight:bold;
}

th a:hover {
    text-decoration:underline;
}

.arrow {
    font-size:12px;
}

</style>
</head>

<body>

<div class="container">
<h2>Admin Dashboard</h2>

<div class="nav">
    <div class="nav-left">
        <a href="?page=username">Username</a>
        <a href="?page=harga">Diamond</a>
        <a href="?page=transaksi">Transaksi</a>
    </div>
    <a href="logout.php" class="logout">Logout</a>
</div>

<!-- ================= USERNAME ================= -->
<?php if ($page == 'username') { ?>
<div class="search-row">
    <h3>Data Username</h3>
    <form class="search-box">
        <input type="hidden" name="page" value="username">
        <input name="search" value="<?= $search ?>" placeholder="Cari ID / Username">
        <button>Cari 🔎</button>
        <a href="?page=username" class="btn-back">Reset</a>
    </form>
</div>

<table>
<tr>
    <th><?= sortLink('ID Game','id_game','username',$search,$sort,$order) ?></th>
    <th><?= sortLink('Username','username','username',$search,$sort,$order) ?></th>
    <th><?= sortLink('Jumlah Transaksi','jumlah_transaksi','username',$search,$sort,$order) ?></th>
</tr>

<?php
$sql = "
SELECT 
    DISTINCT(u.id_game),
    u.username,
    COUNT(t.id_order) AS jumlah_transaksi
FROM username u
LEFT JOIN transaksi t ON u.id_game = t.id_game
WHERE u.id_game LIKE '%$search%'
   OR u.username LIKE '%$search%'
GROUP BY u.id_game, u.username
";

if ($sort) $sql .= " ORDER BY $sort $order";
$q = mysqli_query($conn,$sql);
while ($d=mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $d['id_game'] ?></td>
    <td><?= $d['username'] ?></td>
    <td><?= $d['jumlah_transaksi'] ?></td>
</tr>
<?php } ?>
</table>
<?php } ?>

<!-- ================= DIAMOND ================= -->
<?php if ($page == 'harga') { ?>
<div class="search-row">
    <h3>Data Harga Diamond</h3>
    <form class="search-box">
        <input type="hidden" name="page" value="harga">
        <input name="search" value="<?= $search ?>" placeholder="Cari ID / Jenis diamond / Harga">
        <button>Cari 🔎</button>
        <a href="?page=harga" class="btn-back">Reset</a>
    </form>
</div>

<form method="POST" class="form-inline">
    <input name="id_diamond" placeholder="ID Diamond" required>
    <input name="jenis_diamond" placeholder="Jenis Diamond" required>
    <input name="harga" placeholder="Harga" required>
    <button name="add_harga">+ Tambah</button>
</form>

<table>
<tr>
    <th><?= sortLink('ID','id_diamond','harga',$search,$sort,$order) ?></th>
    <th><?= sortLink('Jenis Diamond','jenis_diamond','harga',$search,$sort,$order) ?></th>
    <th><?= sortLink('Harga','harga','harga',$search,$sort,$order) ?></th>
    <th><?= sortLink('Jumlah Pembelian','jumlah_pembelian','harga',$search,$sort,$order) ?></th>
    <th>Aksi</th>
</tr>

<?php
$sql = "
SELECT
    h.id_diamond,
    h.jenis_diamond,
    h.harga,
COUNT(t.id_order) AS jumlah_pembelian
FROM harga h
LEFT JOIN transaksi t
    ON h.id_diamond = t.id_diamond
WHERE h.id_diamond LIKE '%$search%'
    OR jenis_diamond LIKE '%$search%'
    OR harga LIKE '%$search%'
GROUP BY h.id_diamond, h.jenis_diamond, h.harga";
if ($sort) $sql .= " ORDER BY $sort $order";
$q = mysqli_query($conn,$sql);
while ($d=mysqli_fetch_assoc($q)) {
?>
<tr>
<form method="POST">
    <td><?= $d['id_diamond'] ?>
        <input type="hidden" name="id_diamond" value="<?= $d['id_diamond'] ?>">
    </td>
    <td><input name="jenis_diamond" value="<?= $d['jenis_diamond'] ?>"></td>
    <td><input name="harga" value="<?= $d['harga'] ?>"></td>
    <td><?= $d['jumlah_pembelian'] ?>
        <input type="hidden" name="jumlah_pembelian" value="<?= $d['jumlah_pembelian'] ?>">
    </td>
    <td class="aksi">
        <button class="btn-update" name="edit_harga">Update</button>
        <a class="btn-delete"
           href="?page=harga&hapus=<?= $d['id_diamond'] ?>"
           onclick="return confirm('Hapus data?')">Hapus</a>
    </td>
</form>
</tr>
<?php } ?>
</table>
<?php } ?>

<!-- ================= TRANSAKSI ================= -->
<?php if ($page == 'transaksi') { ?>
<div class="search-row">
    <h3>Data Transaksi</h3>
    <form class="search-box">
        <input type="hidden" name="page" value="transaksi">
        <input name="search" value="<?= $search ?>" placeholder="Cari ID Order / Username / Diamond / Tanggal / Waktu">
        <button>Cari 🔎</button>
        <a href="?page=transaksi" class="btn-back">Reset</a>
    </form>
</div>

<table>
<tr>
    <th><?= sortLink('ID Order','id_order','transaksi',$search,$sort,$order) ?></th>
    <th><?= sortLink('Username','username','transaksi',$search,$sort,$order) ?></th>
    <th><?= sortLink('Diamond','jenis_diamond','transaksi',$search,$sort,$order) ?></th>
    <th><?= sortLink('Waktu Transaksi','waktu_transaksi','transaksi',$search,$sort,$order) ?></th>
</tr>

<?php
$sql = "
SELECT t.id_order,u.username,h.jenis_diamond,concat_ws(' & ',t.tanggal,t.waktu) as waktu_transaksi
FROM transaksi t
JOIN username u ON t.id_game=u.id_game
JOIN harga h ON t.id_diamond=h.id_diamond
WHERE t.id_order LIKE '%$search%'
OR u.username LIKE '%$search%'
OR h.jenis_diamond LIKE '%$search%'
OR t.tanggal LIKE '%$search%'
OR t.waktu LIKE '%$search%'
";
if ($sort) $sql .= " ORDER BY $sort $order";
$q = mysqli_query($conn,$sql);
while ($d=mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $d['id_order'] ?></td>
    <td><?= $d['username'] ?></td>
    <td><?= $d['jenis_diamond'] ?></td>
    <td><?= $d['waktu_transaksi'] ?></td>
</tr>
<?php } ?>
</table>
<?php } ?>

</div>
</body>
</html>
