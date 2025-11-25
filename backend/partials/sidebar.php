<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$level = $_SESSION['level'] ?? null;
$current_folder = basename(dirname($_SERVER['SCRIPT_NAME']));
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Tiket</title>

    <!-- Font & Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: #f5f6fa;
            display: flex;
        }

        /* === Sidebar Style === */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #121633;
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.15);
        }

        /* Logo */
        .sidebar-logo {
            padding: 25px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo img {
            width: 60px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .sidebar-logo span {
            display: block;
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-top: 10px;
        }

        /* Navigation */
        .nav {
            list-style: none;
            margin: 0;
            padding: 0;
            margin-top: 10px;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #bfc6d4;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .nav-item a i {
            margin-right: 12px;
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Aktif / Hover */
        .nav-item.active>a,
        .nav-item a:hover {
            background-color: #1a1f47;
            color: #fff;
            border-left: 3px solid #6c63ff;
            /* Warna ungu */
        }

        /* Sidebar Hover Effect */
        .nav-item a:hover {
            transform: translateX(3px);
        }

        main {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            min-height: 100vh;
        }

        h1 {
            color: #333;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <a href="../dashbord/index.php" class="text-decoration-none text-white">
                <!-- <img src="../../tp-ad/assets/img/image.png" alt="Logo" width="35" /> -->
                <span>Aplikasi Tiket</span>
            </a>
        </div>

        <ul class="nav">
            <li class="nav-item <?= ($current_folder == 'dashbord') ? 'active' : '' ?>">
                <a href="../dashbord/index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <?php if ($level === 'admin'): ?>
                <li class="nav-item <?= ($current_folder == 'entri data') ? 'active' : '' ?>">
                    <a href="../entri data/index.php">
                        <i class="fas fa-database"></i>
                        <span>Entri Data</span>
                    </a>
                </li>

                <li class="nav-item <?= ($current_folder == 'user_activity') ? 'active' : '' ?>">
                    <a href="../user_activity/index.php">
                        <i class="fas fa-list-alt"></i>
                        <span>Aktivitas Log</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (in_array($level, ['petugas', 'admin'])): ?>
                <li class="nav-item <?= ($current_folder == 'Verifikasi_validasi') ? 'active' : '' ?>">
                    <a href="../Verifikasi_validasi/index.php">
                        <i class="fas fa-check-circle"></i>
                        <span>Verifikasi & Validasi</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>


</body>

</html>