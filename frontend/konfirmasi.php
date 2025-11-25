<?php
session_start();
include '../config/connection.php';
include 'partials/header.php';
include 'partials/navbar.php';


// Ambil data dari URL
$id_rute   = intval($_GET['id_rute'] ?? 0);
$id_jadwal = intval($_GET['id_jadwal'] ?? 0);
$tanggal   = $_GET['tgl'] ?? date('Y-m-d');
$jumlah    = intval($_GET['jml'] ?? 1);
$id_penumpang = $_SESSION['id_penumpang'] ?? 0;

// Validasi input dasar
if ($id_rute <= 0) {
    echo "<script>alert('ID rute tidak valid'); window.location='index.php';</script>";
    exit;
}
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
    $tanggal = date('Y-m-d');
}

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
        TIME_FORMAT(j.jam_tiba, '%H:%i') AS jam_tiba,
        (t.jumlah_kursi - IFNULL((
            SELECT COUNT(*) FROM pemesanan p2
            WHERE p2.id_rute = r.id_rute
              AND p2.tanggal_berangkat = '{$tanggal}'
        ), 0)) AS kursi_tersisa
    FROM rute r
    JOIN transportasi t ON r.id_transportasi = t.id_transportasi
    JOIN type_transportasi ty ON t.id_type_transportasi = ty.id_type_transportasi
    JOIN jadwal j ON r.id_rute = j.id_rute
    WHERE r.id_rute = {$id_rute}
    ORDER BY j.jam_berangkat ASC
    LIMIT 1
";

$res = mysqli_query($conn, $sql);
if (!$res) die("Query error: " . mysqli_error($conn));

$rute = mysqli_fetch_assoc($res);
if (!$rute) {
    echo "<div style='text-align:center;margin-top:50px;color:red;font-weight:bold;'>
            Rute atau jadwal tidak ditemukan.<br><a href='javascript:history.back()'>Kembali</a>
          </div>";
    exit;
}

// Hitung total harga & kode unik
$total = $rute['harga'] * $jumlah;
$kode_pemesanan = 'TKT' . date('YmdHis') . rand(100, 999);

// Cek ketersediaan kursi
$kursi_tersisa = intval($rute['kursi_tersisa']);
$boleh_pesan = ($kursi_tersisa >= $jumlah && $jumlah > 0);

// Fungsi aman HTML
function h($v)
{
    return htmlspecialchars($v ?? '', ENT_QUOTES);
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Konfirmasi Pemesanan - Tiket Ajaib</title>
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
            --gradient-warning: linear-gradient(135deg, #ffc107, #fd7e14);
            --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .confirmation-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .confirmation-card {
            background: white;
            border-radius: 25px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
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

        .card-body-custom {
            padding: 3rem 2.5rem;
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

        .route-main {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }

        .route-sub {
            color: #6c757d;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

        .summary-section {
            background: var(--gradient-success);
            border-radius: 20px;
            padding: 2rem;
            margin: 2rem 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .summary-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><circle cx="50" cy="50" r="40" fill="%23ffffff"/></svg>');
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .summary-amount {
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .seat-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1rem;
        }

        .seat-available {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
            border: 2px solid #28a745;
        }

        .seat-unavailable {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            border: 2px solid #dc3545;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-confirm {
            background: var(--gradient-success);
            border: none;
            border-radius: 15px;
            padding: 1.25rem 2rem;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .btn-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(40, 167, 69, 0.4);
            color: white;
        }

        .btn-cancel {
            background: white;
            border: 2px solid var(--primary-blue);
            border-radius: 15px;
            padding: 1.25rem 2rem;
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            color: var(--primary-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-cancel:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
        }

        .alert-custom {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            border: 2px solid #f5c6cb;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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
            .confirmation-container {
                padding: 1rem 0.5rem;
            }

            .card-header-custom {
                padding: 2rem 1.5rem;
            }

            .card-body-custom {
                padding: 2rem 1.5rem;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                grid-template-columns: 1fr;
            }

            .route-main {
                font-size: 1.4rem;
            }

            .summary-amount {
                font-size: 2rem;
            }

            .btn-confirm,
            .btn-cancel {
                padding: 1rem 1.5rem;
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .card-header-custom {
                padding: 1.5rem 1rem;
            }

            .card-body-custom {
                padding: 1.5rem 1rem;
            }

            .route-section {
                padding: 1.5rem;
            }

            .route-main {
                font-size: 1.2rem;
            }

            .info-card {
                padding: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <div class="confirmation-container">
        <div class="confirmation-card fade-in-up">
            <!-- Header -->
            <div class="card-header-custom text-center">
                <h1 class="fw-bold mb-3">
                    <i class="fas fa-ticket-alt me-3"></i>Konfirmasi Pemesanan
                </h1>
                <p class="mb-0 opacity-90">Tinjau detail pemesanan Anda sebelum melanjutkan</p>
            </div>

            <!-- Body -->
            <div class="card-body-custom">
                <!-- Route Information -->
                <div class="route-section">
                    <div class="route-main">
                        <?= h($rute['rute_awal']) ?>
                        <i class="fas fa-arrow-right mx-3 text-primary"></i>
                        <?= h($rute['rute_ahir']) ?>
                    </div>
                    <div class="route-sub">
                        <i class="fas fa-map-marker-alt me-2"></i>Tujuan: <?= h($rute['tujuan']) ?>
                    </div>
                </div>

                <!-- Information Grid -->
                <div class="info-grid">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-bus"></i>
                        </div>
                        <div class="info-label">Transportasi</div>
                        <div class="info-value"><?= h($rute['kode']) ?> - <?= h($rute['nama_type']) ?></div>
                        <small class="text-muted">Kapasitas: <?= h($rute['jumlah_kursi']) ?> kursi</small>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-label">Tanggal & Waktu</div>
                        <div class="info-value"><?= date("d F Y", strtotime($tanggal)) ?></div>
                        <small class="text-muted">
                            <?= h($rute['jam_berangkat']) ?> - <?= h($rute['jam_tiba']) ?>
                        </small>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="info-label">Jumlah Penumpang</div>
                        <div class="info-value"><?= $jumlah ?> Orang</div>
                        <small class="text-muted">Total pemesanan</small>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="info-label">Kursi Tersedia</div>
                        <div class="info-value"><?= $kursi_tersisa ?> Kursi</div>
                        <div class="mt-2">
                            <span class="seat-status <?= $kursi_tersisa > 0 ? 'seat-available' : 'seat-unavailable' ?>">
                                <i class="fas <?= $kursi_tersisa > 0 ? 'fa-check-circle' : 'fa-exclamation-circle' ?> me-2"></i>
                                <?= $kursi_tersisa > 0 ? 'Tersedia' : 'Habis' ?>
                            </span>
                        </div>
                    </div>
                </div>

                <?php if (!$boleh_pesan): ?>
                    <!-- Error Message -->
                    <div class="alert-custom text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3 text-danger"></i>
                        <h4 class="text-danger fw-bold">Kursi Tidak Mencukupi</h4>
                        <p class="mb-0">Maaf, kursi yang tersedia tidak mencukupi untuk jumlah penumpang yang dipilih.</p>
                    </div>

                    <div class="text-center">
                        <a href="javascript:history.back()" class="btn-cancel">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Pencarian
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Price Summary -->
                    <div class="summary-section">
                        <div class="summary-label">Total Pembayaran</div>
                        <div class="summary-amount">Rp <?= number_format($total, 0, ',', '.') ?></div>
                        <small class="text-white opacity-90">
                            <?= h($rute['harga']) ?> x <?= $jumlah ?> penumpang
                        </small>
                    </div>

                    <!-- Action Buttons -->
                    <form method="POST" action="simpan_pemesanan.php">
                        <input type="hidden" name="id_rute" value="<?= h($rute['id_rute']) ?>">
                        <input type="hidden" name="id_jadwal" value="<?= h($rute['id_jadwal']) ?>">
                        <input type="hidden" name="tanggal" value="<?= h($tanggal) ?>">
                        <input type="hidden" name="jumlah" value="<?= h($jumlah) ?>">
                        <input type="hidden" name="total" value="<?= h($total) ?>">
                        <input type="hidden" name="kode_pemesanan" value="<?= h($kode_pemesanan) ?>">
                        <input type="hidden" name="id_penumpang" value="<?= h($id_penumpang) ?>">

                        <div class="action-buttons">
                            <button type="submit" class="btn-confirm">
                                <i class="fas fa-check-circle me-2"></i>Konfirmasi & Bayar
                            </button>
                            <a href="hasil_cari.php" class="btn-cancel">
                                <i class="fas fa-times me-2"></i>Batalkan
                            </a>
                        </div>
                    </form>
                <?php endif; ?>
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
    </script>
</body>

</html>