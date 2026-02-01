<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Login | Plaza Top Up</title>
    <link rel="icon" href="Plaza Top Up.png">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            background-color: #3A3A3A;
        }

        .wrapper {
            display: grid;
            grid-template-columns: 50% 50%;
            height: 100vh;
        }

        .left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .right {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .right-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .brand-title {
            font-size: 32px;
            font-weight: bold;
            color: #E6C11F;
            margin-bottom: 20px;
        }

        .login-box {
            width: 350px;
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #3A3A3A;
        }

        .role {
            display: flex;
            gap: 15px;
        }

        .role a {
            flex: 1;
            text-align: center;
            padding: 15px;
            background: #E6C11F;
            color: #3A3A3A;
            font-weight: bold;
            text-decoration: none;
            border-radius: 8px;
            transition: 0.3s;
        }

        .role a:hover {
            background: #d1ae1c;
        }
    </style>
</head>

<body>

<div class="wrapper">

    <div class="left">
        <img src="Plaza Top Up.png" alt="Plaza Top Up">
    </div>

    <div class="right">
        <div class="right-content">

            <div class="brand-title">Plaza Top Up</div>

            <div class="login-box">
                <h2>Masuk Sebagai?</h2>

                <div class="role">
                    <a href="user_enter.php">Pembeli</a>
                    <a href="login.php?role=admin">Admin</a>
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
