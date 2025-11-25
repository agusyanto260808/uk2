<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/connection.php';
include 'partials/header.php';

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - Tiket Ajaib</title>
    <style>
        :root {
            --primary-blue: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3f37c9;
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            --gradient-secondary: linear-gradient(135deg, #4cc9f0 0%, #4895ef 100%);
            --gradient-bg: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            --gradient-welcome: linear-gradient(135deg, #1a73e8 0%, #4285f4 50%, #00bcd4 100%);
        }

        body {
            background: var(--gradient-bg);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .booking-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .booking-card {
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
            text-align: center;
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

        .card-header-custom h1 {
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

        .features-section {
            padding: 2rem;
            background: linear-gradient(135deg, #f8fbff 0%, #e8f0fe 100%);
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid rgba(67, 97, 238, 0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-secondary);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(67, 97, 238, 0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-secondary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
            box-shadow: 0 8px 20px rgba(76, 201, 240, 0.3);
        }

        .feature-card h5 {
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .feature-card p {
            color: #6c757d;
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .form-section {
            padding: 3rem 2.5rem;
        }

        .form-label {
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.3rem rgba(67, 97, 238, 0.15);
            transform: translateY(-2px);
        }

        .info-text {
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            border-radius: 15px;
            padding: 1.125rem 3rem;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .btn-primary-custom:hover::before {
            left: 100%;
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

        /* Loading state for select */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .booking-container {
                padding: 1rem 0.5rem;
            }

            .card-header-custom {
                padding: 2rem 1rem;
            }

            .card-header-custom h1 {
                font-size: 2rem;
            }

            .features-section {
                padding: 1.5rem 1rem;
            }

            .form-section {
                padding: 2rem 1.5rem;
            }

            .feature-card {
                padding: 1.5rem 1rem;
                margin-bottom: 1rem;
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .btn-primary-custom {
                padding: 1rem 2rem;
                font-size: 1rem;
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .card-header-custom h1 {
                font-size: 1.75rem;
            }

            .form-section {
                padding: 1.5rem 1rem;
            }
        }
    </style>

</head>

<body>
    <?php include 'partials/navbar.php'; ?>
    <div class="booking-container">
        <div class="booking-card fade-in-up">
            <!-- Header -->
            <div class="card-header-custom">
                <h1>
                    <i class="fas fa-ticket-alt me-3"></i>Pesan Tiket Perjalanan
                </h1>
                <p>Cari dan pesan tiket perjalanan Anda dengan mudah dan cepat</p>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <div class="row g-4">
                    <div class="col-md-3 col-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <h5>Cari Rute</h5>
                            <p>Pilih stasiun asal dan tujuan perjalanan Anda</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h5>Pilih Tanggal</h5>
                            <p>Tentukan tanggal keberangkatan yang diinginkan</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h5>Pilih Jam</h5>
                            <p>Pilih jam keberangkatan yang tersedia</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5>Tentukan Penumpang</h5>
                            <p>Atur jumlah penumpang yang akan berangkat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Section -->
            <div class="form-section">
                <form method="POST" action="hasil_cari.php" id="bookingForm">
                    <div class="row g-4">
                        <!-- Rute Awal -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                Stasiun Asal
                            </label>
                            <select name="rute_awal" class="form-select" required id="ruteAwal">
                                <option value="">Pilih Stasiun Asal</option>
                                <?php
                                $asal = mysqli_query($conn, "SELECT DISTINCT rute_awal FROM rute ORDER BY rute_awal");
                                while ($row = mysqli_fetch_assoc($asal)) {
                                    $selected = (isset($_POST['rute_awal']) && $_POST['rute_awal'] == $row['rute_awal']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($row['rute_awal']) . "' $selected>" . htmlspecialchars($row['rute_awal']) . "</option>";
                                }
                                ?>
                            </select>
                            <div class="info-text">
                                <i class="fas fa-info-circle text-primary"></i>
                                Pilih stasiun keberangkatan Anda
                            </div>
                        </div>

                        <!-- Rute Akhir -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-flag text-primary"></i>
                                Stasiun Tujuan
                            </label>
                            <select name="rute_ahir" class="form-select" required id="ruteAkhir">
                                <option value="">Pilih Stasiun Tujuan</option>
                                <?php
                                $tujuan = mysqli_query($conn, "SELECT DISTINCT rute_ahir FROM rute ORDER BY rute_ahir");
                                while ($row = mysqli_fetch_assoc($tujuan)) {
                                    $selected = (isset($_POST['rute_ahir']) && $_POST['rute_ahir'] == $row['rute_ahir']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($row['rute_ahir']) . "' $selected>" . htmlspecialchars($row['rute_ahir']) . "</option>";
                                }
                                ?>
                            </select>
                            <div class="info-text">
                                <i class="fas fa-info-circle text-primary"></i>
                                Pilih stasiun tujuan Anda
                            </div>
                        </div>

                        <!-- Tanggal Berangkat -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-calendar-day text-primary"></i>
                                Tanggal Berangkat
                            </label>
                            <input type="date" name="tanggal_berangkat" class="form-control"
                                min="<?= date('Y-m-d'); ?>"
                                value="<?= isset($_POST['tanggal_berangkat']) ? $_POST['tanggal_berangkat'] : date('Y-m-d'); ?>"
                                required>
                            <div class="info-text">
                                <i class="fas fa-info-circle text-primary"></i>
                                Pilih tanggal keberangkatan
                            </div>
                        </div>

                        <!-- Jadwal Keberangkatan -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-clock text-primary"></i>
                                Pilih Jadwal
                            </label>
                            <select name="id_jadwal" id="id_jadwal" class="form-select" required>
                                <option value="">Pilih jadwal keberangkatan</option>
                            </select>
                            <div class="info-text" id="infoJadwal">
                                <i class="fas fa-info-circle text-primary"></i>
                                Silakan pilih rute asal & tujuan
                            </div>
                        </div>

                        <!-- Jumlah Penumpang -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-users text-primary"></i>
                                Jumlah Penumpang
                            </label>
                            <select name="jumlah" class="form-select" required>
                                <?php
                                for ($i = 1; $i <= 10; $i++) {
                                    $selected = (isset($_POST['jumlah']) && $_POST['jumlah'] == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i Penumpang</option>";
                                }
                                ?>
                            </select>
                            <div class="info-text">
                                <i class="fas fa-info-circle text-primary"></i>
                                Maksimal 10 penumpang
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-search me-2"></i>Cari Tiket Tersedia
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const dateInput = document.querySelector('input[name="tanggal_berangkat"]');
            if (!dateInput.value) {
                dateInput.value = today;
            }

            const asal = document.getElementById('ruteAwal');
            const tujuan = document.getElementById('ruteAkhir');
            const jadwalSelect = document.getElementById('id_jadwal');
            const infoJadwal = document.getElementById('infoJadwal');
            const form = document.getElementById('bookingForm');

            function loadJadwal() {
                const ruteAwal = asal.value;
                const ruteAkhir = tujuan.value;

                if (ruteAwal && ruteAkhir && ruteAwal !== ruteAkhir) {
                    jadwalSelect.classList.add('loading');
                    infoJadwal.innerHTML = '<i class="fas fa-spinner fa-spin text-primary"></i> Memuat jadwal...';

                    fetch(`get_jadwal.php?rute_awal=${encodeURIComponent(ruteAwal)}&rute_ahir=${encodeURIComponent(ruteAkhir)}`)
                        .then(res => res.json())
                        .then(data => {
                            jadwalSelect.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(j => {
                                    const opt = document.createElement('option');
                                    opt.value = j.id_jadwal;
                                    opt.textContent = `${j.jam_berangkat} → ${j.jam_tiba}`;
                                    jadwalSelect.appendChild(opt);
                                });
                                infoJadwal.innerHTML = '<i class="fas fa-check-circle text-success"></i> Pilih jadwal keberangkatan yang tersedia.';
                            } else {
                                jadwalSelect.innerHTML = '<option value="">Tidak ada jadwal tersedia</option>';
                                infoJadwal.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Tidak ditemukan jadwal untuk rute ini.';
                            }
                        })
                        .catch(() => {
                            jadwalSelect.innerHTML = '<option value="">Terjadi kesalahan memuat data</option>';
                            infoJadwal.innerHTML = '<i class="fas fa-exclamation-circle text-danger"></i> Gagal memuat data jadwal.';
                        })
                        .finally(() => {
                            jadwalSelect.classList.remove('loading');
                        });
                } else {
                    jadwalSelect.innerHTML = '<option value="">Pilih rute terlebih dahulu</option>';
                    if (ruteAwal && ruteAkhir && ruteAwal === ruteAkhir) {
                        infoJadwal.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Stasiun asal dan tujuan tidak boleh sama.';
                    } else {
                        infoJadwal.innerHTML = '<i class="fas fa-info-circle text-primary"></i> Silakan pilih rute asal & tujuan yang berbeda.';
                    }
                }
            }

            asal.addEventListener('change', loadJadwal);
            tujuan.addEventListener('change', loadJadwal);

            form.addEventListener('submit', function(e) {
                const ruteAwal = asal.value;
                const ruteAkhir = tujuan.value;

                if (ruteAwal === ruteAkhir) {
                    e.preventDefault();
                    alert('Stasiun asal dan tujuan tidak boleh sama!');
                    return false;
                }
            });

            // Initial load if values are already set
            if (asal.value && tujuan.value) {
                loadJadwal();
            }
        });
    </script>
</body>

</html>

<?php include 'partials/footer.php'; ?>