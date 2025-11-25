<?php
session_start();
include '../config/connection.php';
include 'partials/header.php';
include 'partials/navbar.php';

$id = intval($_GET['id'] ?? 0);

// Debug sementara
// echo "ID Tiket: $id<br>";
$q = mysqli_query($conn, "
    SELECT 
        p.id_pemesanan,
        p.kode_pemesanan,
        p.tanggal_pemesanan,
        p.tanggal_berangkat,
        p.total_bayar,
        p.kode_kursi,
        pn.nama_penumpang,
        r.rute_awal,
        r.rute_ahir,
        r.tujuan,
        r.harga,
        j.jam_berangkat,
        j.jam_tiba
    FROM pemesanan p
    JOIN rute r ON p.id_rute = r.id_rute
    JOIN jadwal j ON p.id_jadwal = j.id_jadwal
    JOIN penumpang pn ON pn.id_penumpang = pn.id_penumpang
    WHERE p.id_pemesanan = $id
");

if (!$q) {
    die("Error query: " . mysqli_error($conn));
}

if (mysqli_num_rows($q) == 0) {
    die("<h3 style='color:red;text-align:center;margin-top:50px'>❌ Data tiket tidak ditemukan di database.</h3>");
}

$tiket = mysqli_fetch_assoc($q);

// --- Ambil data dari hasil query ---
$kode_pemesanan     = $tiket['kode_pemesanan'];
$rute_awal          = $tiket['rute_awal'];
$rute_ahir          = $tiket['rute_ahir'];
$tujuan             = $tiket['tujuan'];
$nama_penumpang     = $tiket['nama_penumpang'];
$tanggal_pemesanan  = $tiket['tanggal_pemesanan'];
$tanggal_berangkat  = $tiket['tanggal_berangkat'];
$jam_berangkat      = $tiket['jam_berangkat'];
$jam_tiba           = $tiket['jam_tiba'];
$total_bayar        = $tiket['total_bayar'];
$kode_kursi         = $tiket['kode_kursi'];

// Helper function
function safe_html($value)
{
    return htmlspecialchars($value ?? '-');
}
function safe_date($date, $format = 'd F Y')
{
    return ($date && strtotime($date)) ? date($format, strtotime($date)) : '-';
}
function safe_time($time)
{
    return ($time && strtotime($time)) ? date('H:i', strtotime($time)) : '-';
}
function safe_number($number)
{
    return number_format($number ?? 0, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Pemesanan - <?= safe_html($kode_pemesanan) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3f37c9;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            --gradient-secondary: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
            --gradient-success: linear-gradient(135deg, #7b4de6ff, #5b24cbff);
            --gradient-warning: linear-gradient(135deg, #0b2da9ff, #2e1cb4ff);
            --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            padding: 20px 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .ticket-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .ticket-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
        }

        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: var(--gradient-success);
        }

        .ticket-header {
            background: var(--gradient-success);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .ticket-header::before {
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

        .ticket-code {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            opacity: 0.9;
            position: relative;
        }

        .ticket-title {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .status-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            position: relative;
        }

        .ticket-body {
            padding: 2.5rem;
        }

        .route-section {
            background: linear-gradient(135deg, #f8fbff, #e8f0fe);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            border: 2px solid var(--primary-blue);
            position: relative;
            overflow: hidden;
        }

        .route-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.05"><path d="M20,20 L80,80 M80,20 L20,80" stroke="%234361ee" stroke-width="2"/></svg>');
        }

        .route-main {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            position: relative;
        }

        .route-sub {
            color: #6c757d;
            font-size: 1.1rem;
            position: relative;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            border: 2px solid #f1f3f4;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-secondary);
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-light);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background: var(--gradient-secondary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .info-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }

        .price-section {
            background: var(--gradient-warning);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .price-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><circle cx="50" cy="50" r="40" fill="%23ffffff"/></svg>');
        }

        .price-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .price-amount {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .btn-outline-custom {
            background: white;
            border: 2px solid var(--primary-blue);
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: var(--primary-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-outline-custom:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
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

        /* Print Styles */
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }

            .ticket-container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: none !important;
            }

            .ticket-card {
                box-shadow: none !important;
                border: 2px solid #333 !important;
            }

            .action-buttons {
                display: none !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .ticket-container {
                padding: 1rem 0.5rem;
            }

            .ticket-header {
                padding: 2rem 1rem;
            }

            .ticket-title {
                font-size: 1.8rem;
            }

            .ticket-body {
                padding: 1.5rem;
            }

            .route-main {
                font-size: 1.4rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .price-amount {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .ticket-header {
                padding: 1.5rem 1rem;
            }

            .ticket-title {
                font-size: 1.5rem;
            }

            .route-section {
                padding: 1.5rem;
            }

            .route-main {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="ticket-container">
        <div class="ticket-card fade-in-up">
            <!-- Header -->
            <div class="ticket-header">
                <div class="ticket-code">
                    <i class="fas fa-ticket-alt me-2"></i><?= safe_html($kode_pemesanan) ?>
                </div>
                <h1 class="ticket-title">Tiket Perjalanan</h1>
                <div class="status-badge">
                    <i class="fas fa-check-circle me-2"></i>Berhasil Dipesan
                </div>
            </div>

            <!-- Body -->
            <div class="ticket-body">
                <!-- Route Information -->
                <div class="route-section">
                    <div class="route-main">
                        <?= safe_html($rute_awal) ?>
                        <i class="fas fa-arrow-right mx-3 text-primary"></i>
                        <?= safe_html($rute_ahir) ?>
                    </div>
                    <div class="route-sub">
                        <i class="fas fa-map-marker-alt me-2"></i>Tujuan: <?= safe_html($tujuan) ?>
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-label">Nama Penumpang</div>
                        <div class="info-value"><?= safe_html($nama_penumpang) ?></div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-label">Tanggal Pemesanan</div>
                        <div class="info-value"><?= safe_date($tanggal_pemesanan) ?></div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="info-label">Tanggal Berangkat</div>
                        <div class="info-value"><?= safe_date($tanggal_berangkat) ?></div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-label">Waktu Berangkat</div>
                        <div class="info-value"><?= safe_time($jam_berangkat) ?> WIB</div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <div class="info-label">Waktu Tiba</div>
                        <div class="info-value"><?= safe_time($jam_tiba) ?> WIB</div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="info-label">Nomor Kursi</div>
                        <div class="info-value"><?= safe_html($kode_kursi) ?></div>
                    </div>
                </div>

                <!-- Price Section -->
                <div class="price-section">
                    <div class="price-label">Total Pembayaran</div>
                    <div class="price-amount">Rp <?= safe_number($total_bayar) ?></div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="dashboard.php" class="btn-primary-custom">
                        <i class="fas fa-home me-2"></i>Kembali 
                    </a>
                    <button onclick="window.print()" class="btn-outline-custom">
                        <i class="fas fa-print me-2"></i>Cetak Tiket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to info cards
        document.addEventListener('DOMContentLoaded', function() {
            const infoCards = document.querySelectorAll('.info-card');

            infoCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in-up');
            });
        });

        // Enhanced print functionality
        function printTicket() {
            window.print();
        }
    </script>
</body>

</html>