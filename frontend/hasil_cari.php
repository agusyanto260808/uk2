<?php
session_start();
include '../config/connection.php';
include 'partials/header.php';
include 'partials/navbar.php';


// Ambil data dari form (sudah di-escape)
$rute_awal = mysqli_real_escape_string($conn, $_POST['rute_awal'] ?? '');
$rute_ahir  = mysqli_real_escape_string($conn, $_POST['rute_ahir'] ?? '');
$tanggal    = mysqli_real_escape_string($conn, $_POST['tanggal_berangkat'] ?? '');
$jumlah     = intval($_POST['jumlah'] ?? 1);

// Validasi input
if (empty($rute_awal) || empty($rute_ahir) || empty($tanggal)) {
    echo "<script>alert('Lengkapi semua data!'); history.back();</script>";
    exit;
}

// Query: ambil rute + transportasi + jadwal (jam berangkat & jam tiba)
$sql = "
    SELECT 
        r.id_rute,
        r.tujuan,
        r.rute_awal,
        r.rute_ahir,
        r.harga,
        t.kode,
        t.jumlah_kursi,
        ty.nama_type,
        j.id_jadwal,
        TIME_FORMAT(j.jam_berangkat, '%H:%i') AS jam_berangkat,
        TIME_FORMAT(j.jam_tiba, '%H:%i') AS jam_tiba
    FROM rute r
    JOIN transportasi t ON r.id_transportasi = t.id_transportasi
    JOIN type_transportasi ty ON t.id_type_transportasi = ty.id_type_transportasi
    JOIN jadwal j ON r.id_rute = j.id_rute
    WHERE r.rute_awal = '$rute_awal'
      AND r.rute_ahir  = '$rute_ahir'
    ORDER BY j.jam_berangkat ASC
";

$q = mysqli_query($conn, $sql);
if (!$q) {
    die('Query error: ' . mysqli_error($conn));
}

// helper
function h($s)
{
    return htmlspecialchars($s ?? '');
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Hasil Pencarian Tiket - Tiket Ajaib</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3f37c9;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            --gradient-secondary: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
            --gradient-success: linear-gradient(135deg, #28a745, #20c997);
            --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .search-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .search-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .card-header-custom {
            background: var(--gradient-primary);
            color: white;
            padding: 2.5rem 2rem;
            border-bottom: none;
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

        .search-summary {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .badge-custom {
            background: rgba(255, 255, 255, 0.9);
            color: var(--primary-dark);
            padding: 0.6rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .ticket-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border: 2px solid #f1f3f4;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
        }

        .ticket-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 6px;
            height: 100%;
            background: var(--gradient-secondary);
            transition: all 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
            border-color: var(--primary-light);
        }

        .ticket-card:hover::before {
            width: 8px;
            background: var(--gradient-primary);
        }

        .transport-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-secondary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 20px rgba(76, 201, 240, 0.3);
            transition: all 0.3s ease;
        }

        .ticket-card:hover .transport-icon {
            transform: scale(1.1);
            background: var(--gradient-primary);
        }

        .transport-info h5 {
            color: var(--primary-dark);
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .transport-info small {
            color: #6c757d;
            font-weight: 600;
        }

        .route-display {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .time-info {
            color: #6c757d;
            font-weight: 600;
        }

        .time-info i {
            color: var(--primary-blue);
        }

        .seat-info {
            color: #6c757d;
            font-weight: 500;
        }

        .price-tag {
            font-size: 1.8rem;
            font-weight: 800;
            color: #28a745;
            margin-bottom: 0.25rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .price-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-book {
            background: var(--gradient-success);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-book:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
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

        .btn-back {
            background: var(--gradient-primary);
            border: none;
            border-radius: 15px;
            padding: 1rem 2rem;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            transform: translateY(-3px);
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
            .search-container {
                padding: 1rem 0.5rem;
            }

            .card-header-custom {
                padding: 2rem 1.5rem;
                text-align: center;
            }

            .search-summary {
                margin-top: 1rem;
                width: 100%;
            }

            .ticket-card {
                padding: 1.5rem;
                text-align: center;
            }

            .transport-icon {
                margin: 0 auto 1rem;
            }

            .price-tag {
                font-size: 1.5rem;
            }

            .btn-book {
                width: 100%;
                margin-top: 1rem;
            }

            .route-display {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .card-header-custom {
                padding: 1.5rem 1rem;
            }

            .ticket-card {
                padding: 1rem;
            }

            .transport-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="search-container">
        <div class="search-card fade-in-up">
            <!-- Header -->
            <div class="card-header-custom">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold mb-3">
                            <i class="fas fa-search me-3"></i>Hasil Pencarian Tiket
                        </h1>
                        <p class="mb-0 opacity-90">Berikut tiket yang tersedia sesuai pencarian Anda</p>
                    </div>
                    <div class="col-md-4">
                        <div class="search-summary">
                            <div class="d-flex flex-column gap-2">
                                <span class="badge-custom">
                                    <i class="fas fa-route me-2"></i><?= h($rute_awal) ?> → <?= h($rute_ahir) ?>
                                </span>
                                <span class="badge-custom">
                                    <i class="fas fa-calendar-alt me-2"></i><?= date("d M Y", strtotime($tanggal)) ?>
                                </span>
                                <span class="badge-custom">
                                    <i class="fas fa-users me-2"></i><?= h($jumlah) ?> Penumpang
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results -->
            <div class="card-body p-4">
                <?php if (mysqli_num_rows($q) > 0): ?>
                    <div class="row">
                        <?php while ($row = mysqli_fetch_assoc($q)): ?>
                            <div class="col-12 mb-4">
                                <div class="ticket-card fade-in-up">
                                    <div class="row align-items-center">
                                        <!-- Transport Info -->
                                        <div class="col-md-3 d-flex align-items-center mb-3 mb-md-0">
                                            <div class="transport-icon me-3">
                                                <i class="fas fa-bus"></i>
                                            </div>
                                            <div class="transport-info">
                                                <h5 class="mb-1"><?= h($row['kode']); ?></h5>
                                                <small><?= h($row['nama_type']); ?></small>
                                            </div>
                                        </div>

                                        <!-- Route & Time -->
                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <div class="route-display">
                                                <?= h($row['rute_awal']) ?>
                                                <i class="fas fa-arrow-right mx-2 text-primary"></i>
                                                <?= h($row['rute_ahir']) ?>
                                            </div>
                                            <div class="time-info mb-1">
                                                <i class="fas fa-clock me-2"></i>
                                                <?= $row['jam_berangkat'] ? h($row['jam_berangkat']) : '-' ?>
                                                <?php if ($row['jam_tiba']): ?>
                                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                    <?= h($row['jam_tiba']) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="seat-info">
                                                <i class="fas fa-chair me-2"></i>
                                                <?= h($row['jumlah_kursi']) ?> kursi tersedia
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="col-md-3 text-center mb-3 mb-md-0">
                                            <div class="price-tag">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></div>
                                            <div class="price-label">per orang</div>
                                        </div>

                                        <!-- Action -->
                                        <div class="col-md-3 text-center">
                                            <a href="konfirmasi.php?id_rute=<?= urlencode($row['id_rute']) ?>&id_jadwal=<?= urlencode($row['id_jadwal']) ?>&tgl=<?= urlencode($tanggal) ?>&jml=<?= urlencode($jumlah) ?>"
                                                class="btn-book">
                                                <i class="fas fa-ticket-alt me-2"></i>Pesan Tiket
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-ticket-alt"></i>
                        <h3 class="fw-bold text-muted mb-3">Tiket Tidak Ditemukan</h3>
                        <p class="text-muted mb-4">Maaf, tidak ada jadwal untuk rute dan tanggal yang Anda pilih.</p>
                        <a href="javascript:history.back()" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i>Kembali Pencarian
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add animation to ticket cards
        document.addEventListener('DOMContentLoaded', function() {
            const ticketCards = document.querySelectorAll('.ticket-card');

            ticketCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>

</html>