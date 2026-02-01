<?php
$role = $_GET['role'] ?? '';
$error = $_GET['error'] ?? '';

if ($role != 'user' && $role != 'admin') {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login <?= ucfirst($role); ?> | Plaza Top Up</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Plaza Top Up.png">
</head>
<body>

<div class="login-wrapper">

    <div class="brand-title">Plaza Top Up</div>

    <div class="login-container">
        <h2>Login <?= ucfirst($role); ?></h2>

        <!-- PESAN ERROR -->
        <?php if ($error): ?>
            <div class="error-msg"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <input type="hidden" name="role" value="<?= $role; ?>">

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <a href="index.php" class="back-link">← Kembali</a>
    </div>

</div>

</body>
</html>
