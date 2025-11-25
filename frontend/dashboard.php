<?php
session_start();
include '../config/connection.php';
include 'partials/header.php';
include 'partials/navbar.php';

// Pastikan penumpang sudah login
if (!isset($_SESSION['id_penumpang'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = 'actions/login.php';
    </script>";
    exit();
}

$id_penumpang = $_SESSION['id_penumpang'];

// Query ambil semua pemesanan milik penumpang ini
$q = mysqli_query($conn, "
    SELECT 
        p.id_pemesanan,
        p.kode_pemesanan,
        p.tanggal_pemesanan,
        p.tanggal_berangkat,
        p.total_bayar,
        p.status_pemesanan,
        p.kode_kursi,
        r.rute_awal,
        r.rute_ahir,
        r.tujuan,
        j.jam_berangkat,
        j.jam_tiba,
        pn.nama_penumpang
    FROM pemesanan p
    JOIN rute r ON p.id_rute = r.id_rute
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    JOIN penumpang pn ON pn.id_penumpang = pn.id_penumpang
    WHERE pn.id_penumpang = '$id_penumpang'
    ORDER BY p.id_pemesanan DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pemesanan - Tiket Ajaib</title>
    <style>
        :root {
            --primary-blue: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3f37c9;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            --gradient-secondary: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
            --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .history-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .history-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-header-custom {
            background: var(--gradient-primary);
            color: white;
            padding: 2.5rem 2rem;
            border-bottom: none;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header-custom::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
            transform: rotate(30deg);
            animation: float 20s linear infinite;
        }

        .card-header-custom h2 {
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .card-header-custom p {
            font-size: 1.2rem;
            opacity: 0.95;
            margin: 0;
            position: relative;
        }

        .table-container {
            padding: 0;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        .custom-table thead {
            background: var(--gradient-secondary);
        }

        .custom-table thead th {
            color: white;
            font-weight: 700;
            padding: 1.5rem 1rem;
            border: none;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
        }

        .custom-table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1rem;
            right: 1rem;
            height: 2px;
            background: rgba(255, 255, 255, 0.3);
        }

        .custom-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f1f3f4;
        }

        .custom-table tbody tr:last-child {
            border-bottom: none;
        }

        .custom-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fbff, #e8f0fe);
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
        }

        .custom-table tbody td {
            padding: 1.5rem 1rem;
            vertical-align: middle;
            border: none;
        }

        .kode-booking {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 0.9rem;
        }

        .route-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }

        .route-arrow {
            color: var(--primary-blue);
            font-size: 1rem;
        }

        .date-time-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .date-time-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            color: #666;
        }

        .total-amount {
            font-weight: 800;
            color: #28a745;
            font-size: 1.1rem;
        }

        .status-badge {
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .status-badge:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
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

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            color: #dee2e6;
            opacity: 0.7;
        }

        .empty-state h4 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #495057;
        }

        .btn-primary-custom {
            background: var(--gradient-primary);
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            border: none;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(67, 97, 238, 0.4);
            color: white;
        }

        @keyframes float {
            0% {
                transform: translateX(0) rotate(30deg);
            }

            50% {
                transform: translateX(-10px) rotate(30deg);
            }

            100% {
                transform: translateX(0) rotate(30deg);
            }
        }

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

        /* Responsive Design */
        @media (max-width: 768px) {
            .history-container {
                padding: 1rem 0.5rem;
            }

            .card-header-custom {
                padding: 2rem 1rem;
            }

            .card-header-custom h2 {
                font-size: 2rem;
            }

            .table-responsive {
                font-size: 0.875rem;
            }

            .custom-table thead {
                display: none;
            }

            .custom-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                padding: 1rem;
            }

            .custom-table tbody td {
                display: flex;
                justify-content: between;
                align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid #f1f3f4;
            }

            .custom-table tbody td:last-child {
                border-bottom: none;
            }

            .custom-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: var(--primary-blue);
                min-width: 120px;
                text-transform: uppercase;
                font-size: 0.8rem;
            }

            .status-badge {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <div class="history-container">
        <div class="history-card fade-in-up">
            <!-- Header -->
            <div class="card-header-custom">
                <h2>
                    <i class="fas fa-history me-3"></i>Riwayat Pemesanan Tiket
                </h2>
                <p>Daftar semua tiket yang pernah Anda pesan</p>
            </div>

            <!-- Table Content -->
            <div class="table-container">
                <?php if (mysqli_num_rows($q) > 0): ?>
                    <div class="">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Kode Booking</th>
                                    <th>Penumpang</th>
                                    <th>Rute</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Total</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($q)): ?>
                                    <tr>
                                        <td data-label="Kode Booking">
                                            <div class="kode-booking">
                                                <i class="fas fa-ticket-alt me-2"></i>
                                                <?= htmlspecialchars($row['kode_pemesanan']) ?>
                                            </div>
                                        </td>
                                        <td data-label="Penumpang">
                                            <strong><?= htmlspecialchars($row['nama_penumpang']) ?></strong>
                                        </td>
                                        <td data-label="Rute">
                                            <div class="route-info">
                                                <span class="fw-semibold"><?= htmlspecialchars($row['rute_awal']) ?></span>
                                                <i class="fas fa-arrow-right route-arrow"></i>
                                                <span class="fw-semibold"><?= htmlspecialchars($row['rute_ahir']) ?></span>
                                            </div>
                                        </td>
                                        <td data-label="Tanggal & Waktu">
                                            <div class="date-time-info">
                                                <div class="date-time-item">
                                                    <i class="fas fa-calendar text-primary"></i>
                                                    <?= date('d M Y', strtotime($row['tanggal_berangkat'])) ?>
                                                </div>
                                                <div class="date-time-item">
                                                    <i class="fas fa-clock text-primary"></i>
                                                    <?= date('H:i', strtotime($row['jam_berangkat'])) ?> - <?= date('H:i', strtotime($row['jam_tiba'])) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Total" class="total-amount">
                                            Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?>
                                        </td>
                                        <td data-label="Status" class="text-center">
                                            <?php
                                            $status = strtolower($row['status_pemesanan']);
                                            switch ($status) {
                                                case 'terima':
                                                    $badge = "badge-success";
                                                    $text = "Diterima";
                                                    break;
                                                case 'tolak':
                                                    $badge = "badge-danger";
                                                    $text = "Ditolak";
                                                    break;
                                                case 'pending':
                                                    $badge = "badge-warning";
                                                    $text = "Menunggu";
                                                    break;
                                                default:
                                                    $badge = "badge-secondary";
                                                    $text = "Pending";
                                            }
                                            ?>
                                            <span class="status-badge <?= $badge ?>"><?= $text ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <h4>Belum Ada Riwayat Pemesanan</h4>
                        <p class="mb-4">Anda belum melakukan pemesanan tiket apapun.</p>
                        <a href="../pemesanan/index.php" class="btn-primary-custom">
                            <i class="fas fa-search me-2"></i>Cari Tiket Sekarang
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to table rows
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.custom-table tbody tr');

            tableRows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                row.classList.add('fade-in-up');
            });
        });

        // Hide spinner when page loads
        window.addEventListener('load', function() {
            const spinner = document.getElementById('spinner');
            if (spinner) {
                spinner.style.display = 'none';
            }
        });
    </script>
</body>

</html>

<?php include 'partials/footer.php'; ?>