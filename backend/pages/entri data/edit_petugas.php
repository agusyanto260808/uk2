<?php
session_start();
if ($_SESSION['id_level'] != 1) {
    die("Akses ditolak!");
}

include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// Ambil ID dari URL
$id_petugas = intval($_GET['id_petugas'] ?? 0);
if ($id_petugas <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location.href='index.php';</script>";
    exit;
}

// Ambil data petugas berdasarkan ID
$q = mysqli_query($conn, "SELECT * FROM petugas WHERE id_petugas = $id_petugas");
$petugas = mysqli_fetch_assoc($q);
if (!$petugas) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

// Ambil daftar level
$lvl = mysqli_query($conn, "SELECT * FROM level ORDER BY id_level ASC");
?>

<div class="container-fluid">
    <div class="page-inner mt-4 px-3">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Data Petugas</h5>
                <a href="../entri data/petugas.php" class="btn btn-light btn-sm">← Kembali</a>
            </div>

            <div class="card-body">
                <form action="../../actions/entri data/update_petugas.php" method="POST">
                    <input type="hidden" name="id_petugas" value="<?= $petugas['id_petugas']; ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="username" class="form-control"
                            value="<?= htmlspecialchars($petugas['username']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Petugas</label>
                        <input type="text" name="nama_petugas" class="form-control"
                            value="<?= htmlspecialchars($petugas['nama_petugas']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password (opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ubah">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level</label>
                        <select name="id_level" class="form-select" required>
                            <option value="">-- Pilih Level --</option>
                            <?php while ($row = mysqli_fetch_assoc($lvl)): ?>
                                <option value="<?= $row['id_level']; ?>"
                                    <?= ($row['id_level'] == $petugas['id_level']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($row['nama_level']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>