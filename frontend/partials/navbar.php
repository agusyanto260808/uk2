<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Navbar Start -->
<div class="container-fluid sticky-top bg-white shadow-sm" style="z-index: 1000;">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <a href="index.php" class="navbar-brand">
                <h1 class="fw-bold text-primary">Tiket Ajaib</h1>
            </a>
            <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
                data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="index.php" class="nav-item nav-link ">Home</a>
                    <a href="pemesanan.php" class="nav-item nav-link">Pemesanan</a>
                    <a href="dashboard.php" class="nav-item nav-link">Riwayat</a>

                    <?php if (isset($_SESSION['penumpang_logged_in']) && $_SESSION['penumpang_logged_in'] === true): ?>
                        <!-- Jika sudah login -->
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle text-capitalize" data-bs-toggle="dropdown">
                                <?php echo htmlspecialchars($_SESSION['nama_penumpang']); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                <a href="actions/login.php" class="dropdown-item text-danger">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Jika belum login -->
                        <a href="actions/login.php" class="nav-item nav-link">Login</a>
                        <a href="actions/register.php" class="nav-item nav-link">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->