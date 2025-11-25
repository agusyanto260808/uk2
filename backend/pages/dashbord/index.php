<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';
// Pastikan penumpang sudah login
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../users/login.php';
    </script>";
    exit();
}

// Hitung total data
$total_penumpang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM penumpang"))['total'] ?? 0;
$total_rute      = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rute"))['total'] ?? 0;
$total_jadwal    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jadwal"))['total'] ?? 0;
$total_pemesanan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM pemesanan"))['total'] ?? 0;

// Ambil data pemesanan terbaru
$qLatest = mysqli_query($conn, "
    SELECT p.*, r.rute_awal, r.rute_ahir, j.jam_berangkat, j.jam_tiba
    FROM pemesanan p
    JOIN rute r ON p.id_rute = r.id_rute
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    ORDER BY p.id_pemesanan DESC
    LIMIT 5
");

// Ambil log aktivitas (hanya admin)
$qLog = null;
if ($_SESSION['level'] === 'admin') {
    $qLog = mysqli_query($conn, "
    SELECT al.*, p.nama_petugas 
    FROM activity_log al
    LEFT JOIN petugas p ON p.id_petugas = p.id_petugas
    ORDER BY al.created_at DESC
    LIMIT 10
");
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tiket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e0e9c6d0.js" crossorigin="anonymous"></script>
    <style>
        .navbar {
            box-shadow: none !important;
        }

        :root {
            --primary-blue: #1a73e8;
            --primary-blue-light: #4285f4;
            --primary-blue-dark: #0d47a1;
            --secondary-blue: #e8f0fe;
            --light-blue: #f8fbff;
            --accent-blue: #bbdefb;
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-secondary: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-success: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-warning: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --gradient-info: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .page-inner {
            padding: 20px;
        }

        /* Header Styles */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            transform: rotate(30deg);
        }

        .dashboard-header h1 {
            font-weight: 700;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }

        .dashboard-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
        }

        .stat-card:nth-child(2)::before {
            background: var(--gradient-success);
        }

        .stat-card:nth-child(3)::before {
            background: var(--gradient-warning);
        }

        .stat-card:nth-child(4)::before {
            background: var(--gradient-secondary);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
            background: var(--gradient-primary);
        }

        .stat-card:nth-child(2) .stat-icon {
            background: var(--gradient-success);
        }

        .stat-card:nth-child(3) .stat-icon {
            background: var(--gradient-warning);
        }

        .stat-card:nth-child(4) .stat-icon {
            background: var(--gradient-secondary);
        }

        .stat-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-blue-dark);
            margin-bottom: 0.5rem;
            line-height: 1;
        }

        .stat-label {
            color: #6c757d;
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-light));
            border: none;
            padding: 1.5rem 2rem;
            color: white;
        }

        .card-header-custom h3 {
            font-weight: 700;
            margin: 0;
            font-size: 1.3rem;
        }

        .card-header-custom i {
            font-size: 1.2rem;
            margin-right: 0.5rem;
        }

        /* Tables */
        .table-container {
            padding: 0;
        }

        .custom-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .custom-table thead th {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: var(--primary-blue-dark);
            font-weight: 700;
            padding: 1.2rem 1rem;
            border: none;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .custom-table tbody td {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid #f1f3f4;
            vertical-align: middle;
            transition: all 0.2s ease;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fbff, #e8f0fe);
            transform: scale(1.01);
        }

        .custom-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #dc3545, #e83e8c);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(135deg, #6c757d, #868e96);
            color: white;
        }

        /* Empty States */
        .empty-state {
            padding: 3rem 2rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .dashboard-header {
                padding: 1.5rem;
                text-align: center;
            }

            .dashboard-header h1 {
                font-size: 2rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-number {
                font-size: 1.8rem;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <div class="page-inner">
            <!-- Header -->
            <div class="dashboard-header fade-in-up">
                <div class="row align-items-center">
                    <div class="col">
                        <h1>Dashboard Tiket</h1>
                        <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong>! 👋 Mari kelola sistem tiket Anda.</p>
                    </div>
                    <div class="col-auto">
                        <div class="bg-white bg-opacity-20 rounded-pill px-3 py-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <span id="currentDateTime"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="stats-grid fade-in-up">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number"><?= number_format($total_penumpang) ?></div>
                    <div class="stat-label">Total Penumpang</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-route"></i>
                    </div>
                    <div class="stat-number"><?= number_format($total_rute) ?></div>
                    <div class="stat-label">Total Rute</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number"><?= number_format($total_jadwal) ?></div>
                    <div class="stat-label">Total Jadwal</div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-number"><?= number_format($total_pemesanan) ?></div>
                    <div class="stat-label">Total Pemesanan</div>
                </div>
            </div>

            <!-- Recent Bookings -->
            <div class="content-card fade-in-up">
                <div class="card-header-custom">
                    <h3><i class="fas fa-history"></i>Pemesanan Terbaru</h3>
                </div>
                <div class="table-container">
                    <?php if (mysqli_num_rows($qLatest) > 0): ?>
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <!-- <th>Kode Pemesanan</th> -->
                                    <th>Rute</th>
                                    <th>Jam Berangkat</th>
                                    <th>Jam Tiba</th>
                                    <th>Total Bayar</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($qLatest)): ?>
                                    <tr>
                                        <!-- <td><strong class="text-primary"><?= htmlspecialchars($row['kode_pemesanan']) ?></strong></td> -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                <?= htmlspecialchars($row['rute_awal']) ?>
                                                <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                <?= htmlspecialchars($row['rute_ahir']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="fas fa-clock text-warning me-2"></i>
                                            <?= htmlspecialchars($row['jam_berangkat']) ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-flag-checkered text-success me-2"></i>
                                            <?= htmlspecialchars($row['jam_tiba']) ?>
                                        </td>
                                        <td>
                                            <strong class="text-success">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = match (strtolower($row['status_pemesanan'])) {
                                                'terima' => 'badge-success',
                                                'tolak'  => 'badge-danger',
                                                'pending' => 'badge-warning',
                                                default  => 'badge-secondary'
                                            };
                                            ?>
                                            <span class="status-badge <?= $badgeClass ?>">
                                                <?= ucfirst($row['status_pemesanan']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <h4>Belum ada pemesanan</h4>
                            <p>Tidak ada data pemesanan yang ditemukan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Activity Log (Admin Only) -->
            <?php if ($_SESSION['level'] === 'admin'): ?>
                <div class="content-card fade-in-up">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-list me-2"></i>Log Aktivitas</h3>
                        <a href="../user_activity/index.php" class="btn btn-light btn-sm text-primary fw-bold shadow-sm">
                            <i class="fas fa-eye me-1"></i> Lihat Selengkapnya
                        </a>
                    </div>

                    <div class="table-container">
                        <?php if ($qLog && mysqli_num_rows($qLog) > 0): ?>
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Nama</th>
                                        <th width="15%">Aksi</th>
                                        <!-- <th width="15%">Tabel</th> -->
                                        <th width="30%">Keterangan</th>
                                        <!-- <th width="10%">IP</th> -->
                                        <th width="10%">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1;
                                    while ($row = mysqli_fetch_assoc($qLog)): ?>
                                        <tr>
                                            <td><strong><?= $no++ ?></strong></td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    <?= htmlspecialchars($row['nama_petugas'] ?? 'Tidak diketahui'); ?>
                                                </span>
                                            </td>

                                            <td>
                                                <span class="status-badge badge-success">
                                                    <?= htmlspecialchars($row['aksi'] ?? '-') ?>
                                                </span>
                                            </td>
                                            <!-- <td><?= htmlspecialchars($row['tabel'] ?? '-') ?></td> -->
                                            <td class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($row['keterangan'] ?? '-') ?>">
                                                <?= htmlspecialchars($row['keterangan'] ?? '-') ?>
                                            </td>
                                            <!-- <td><code><?= htmlspecialchars($row['ip_address'] ?? '-') ?></code></td> -->
                                            <td><small class="text-muted"><?= htmlspecialchars($row['created_at'] ?? ($row['waktu'] ?? '-')) ?></small></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-clipboard-list"></i>
                                <h4>Belum ada aktivitas</h4>
                                <p>Tidak ada log aktivitas yang tercatat.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <style>
        /* Hilangkan semua shadow di area atas dashboard */
        .navbar,
        .main-header,
        header,
        .content-header {
            box-shadow: none !important;
            border-bottom: none !important;

        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update current date and time
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
        }

        updateDateTime();
        setInterval(updateDateTime, 1000);

        // Add hover effects
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>

</html>

<?php include '../../partials/footer.php'; ?>