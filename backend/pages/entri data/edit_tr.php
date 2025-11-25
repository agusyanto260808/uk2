<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// --- Ambil ID dari URL ---
$id_transportasi = intval($_GET['id_transportasi'] ?? 0);

// --- Validasi ID ---
if ($id_transportasi <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location.href='../entri data/transportasi.php';</script>";
    exit;
}

// --- Ambil data transportasi berdasarkan ID ---
$q = mysqli_query($conn, "SELECT * FROM transportasi WHERE id_transportasi = $id_transportasi");
$transportasi = mysqli_fetch_assoc($q);

// --- Jika data tidak ditemukan ---
if (!$transportasi) {
    echo "<script>alert('Data transportasi tidak ditemukan!'); window.location.href='../entri data/transportasi.php';</script>";
    exit;
}

// --- Ambil semua type transportasi untuk dropdown ---
$type_query = mysqli_query($conn, "SELECT * FROM type_transportasi");
?>


<div class="container-fluid">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Data Transportasi</h5>
                <a href="../entri data/transportasi.php" class="btn btn-secondary btn-sm">
                    ← Kembali
                </a>
            </div>

            <div class="card-body">
                <form action="../../actions/entri data/update tr.php" method="POST">
                    <input type="hidden" name="id_transportasi" value="<?= htmlspecialchars($transportasi['id_transportasi']); ?>">

                    <div class="row">
                        <!-- Kode Transportasi -->
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label fw-semibold">Kode Transportasi</label>
                            <input type="text" class="form-control" id="kode" name="kode"
                                value="<?= htmlspecialchars($transportasi['kode']); ?>" required>
                        </div>

                        <!-- Jumlah Kursi -->
                        <div class="col-md-6 mb-3">
                            <label for="jumlah_kursi" class="form-label fw-semibold">Jumlah Kursi</label>
                            <input type="number" class="form-control" id="jumlah_kursi" name="jumlah_kursi"
                                value="<?= htmlspecialchars($transportasi['jumlah_kursi']); ?>" required min="1">
                        </div>

                        <!-- Tipe Transportasi -->
                        <div class="col-md-6 mb-3">
                            <label for="id_type_transportasi" class="form-label fw-semibold">Tipe Transportasi</label>
                            <select class="form-select" id="id_type_transportasi" name="id_type_transportasi" required>
                                <option value="">-- Pilih Tipe Transportasi --</option>
                                <?php while ($type = mysqli_fetch_assoc($type_query)): ?>
                                    <option value="<?= $type['id_type_transportasi']; ?>"
                                        <?= ($type['id_type_transportasi'] == $transportasi['id_type_transportasi']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($type['nama_type']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Keterangan -->
                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label fw-semibold">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2"><?= htmlspecialchars($transportasi['keterangan']); ?></textarea>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>