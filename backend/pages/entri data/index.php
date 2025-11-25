<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../users/login.php';
    </script>";
    exit();
}
?>

<div class="container-fluid py-4">
    <div class="page-inner">
        <h2 class="fw-bold mb-4 text-center text-primary">📊 Entri Data</h2>

        <div class="row justify-content-center g-4">
            <!-- Data Transportasi -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm hover-shadow-lg rounded-4">
                    <div class="card-body">
                        <i class="fas fa-bus fa-3x text-primary mb-3"></i>
                        <h5 class="fw-semibold mb-3">Data Transportasi</h5>
                        <a href="../entri data/transportasi.php" class="btn btn-primary px-4">Kelola</a>
                    </div>
                </div>
            </div>

            <!-- Data Rute -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm hover-shadow-lg rounded-4">
                    <div class="card-body">
                        <i class="fas fa-route fa-3x text-success mb-3"></i>
                        <h5 class="fw-semibold mb-3">Data Rute</h5>
                        <a href="../entri data/rute.php" class="btn btn-success px-4">Kelola</a>
                    </div>
                </div>
            </div>

            <!-- Data Penumpang -->
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card text-center border-0 shadow-sm hover-shadow-lg rounded-4">
                    <div class="card-body">
                        <i class="fas fa-user fa-3x text-info mb-3"></i>
                        <h5 class="fw-semibold mb-3">Data Penumpang</h5>
                        <a href="../entri data/penumpang.php" class="btn btn-info px-4 text-white">Kelola</a>
                    </div>
                </div>
            </div>

            <!-- Data Petugas (hanya untuk admin) -->
            <?php if ($_SESSION['id_level'] == 1): ?>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card text-center border-0 shadow-sm hover-shadow-lg rounded-4">
                        <div class="card-body">
                            <i class="fas fa-user-shield fa-3x text-warning mb-3"></i>
                            <h5 class="fw-semibold mb-3">Data Petugas</h5>
                            <a href="../entri data/petugas.php" class="btn btn-warning px-4 text-white">Kelola</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<style>
    /* ✅ Tambahkan jarak dari navbar agar konten tidak ketutup */
    body {
        padding-top: 80px;
        /* sesuaikan dengan tinggi navbar kamu */
        background-color: #f8faff;
    }

    .hover-shadow-lg {
        transition: all 0.3s ease;
    }

    .hover-shadow-lg:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
    }
</style>

<script>
    // 🔧 Otomatis menyesuaikan jarak atas jika tinggi navbar berubah
    document.addEventListener("DOMContentLoaded", () => {
        const navbar = document.querySelector(".navbar");
        if (navbar) {
            document.body.style.paddingTop = navbar.offsetHeight + "px";
        }
    });
</script>

<?php include '../../partials/footer.php'; ?>