<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// Ambil semua transportasi untuk dropdown
$tq = mysqli_query($conn, "SELECT * FROM transportasi ORDER BY kode ASC");
?>

<div class="container-fluid">
    <div class="page-inner mt-4 px-3">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Tambah Data Rute</h5>
                <a href="../entri data/rute.php" class="btn btn-light btn-sm">← Kembali</a>
            </div>

            <div class="card-body">
                <form action="../../actions/entri data/store_rute.php" method="POST">
                    <div class="row">
                        <!-- Tujuan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tujuan</label>
                            <input type="text" name="tujuan" class="form-control" required>
                        </div>

                        <!-- Rute Awal -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rute Awal</label>
                            <input type="text" name="rute_awal" class="form-control" required>
                        </div>

                        <!-- Rute Akhir -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rute Akhir</label>
                            <input type="text" name="rute_ahir" class="form-control" required>
                        </div>

                        <!-- Harga -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number" name="harga" class="form-control" min="0" required>
                        </div>

                        <!-- Transportasi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Transportasi</label>
                            <select name="id_transportasi" class="form-select" required>
                                <option value="">-- Pilih Transportasi --</option>
                                <?php while ($t = mysqli_fetch_assoc($tq)): ?>
                                    <option value="<?= $t['id_transportasi']; ?>">
                                        <?= htmlspecialchars($t['kode']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Jam Berangkat dan Jam Tiba -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Jadwal Keberangkatan & Kedatangan</label>
                            <div id="jam-wrapper">
                                <div class="row mb-2 jam-item">
                                    <div class="col-md-5">
                                        <input type="time" name="jam_berangkat[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="time" name="jam_tiba[]" class="form-control" required>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100" onclick="this.closest('.jam-item').remove()">Hapus</button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="addJamBtn">+ Tambah Jadwal</button>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Tambah input baru untuk jam berangkat & tiba
    document.getElementById('addJamBtn').addEventListener('click', function() {
        const wrapper = document.getElementById('jam-wrapper');
        const div = document.createElement('div');
        div.classList.add('row', 'mb-2', 'jam-item');
        div.innerHTML = `
            <div class="col-md-5">
                <input type="time" name="jam_berangkat[]" class="form-control" required>
            </div>
            <div class="col-md-5">
                <input type="time" name="jam_tiba[]" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100" onclick="this.closest('.jam-item').remove()">Hapus</button>
            </div>
        `;
        wrapper.appendChild(div);
    });
</script>

<?php include '../../partials/footer.php'; ?>