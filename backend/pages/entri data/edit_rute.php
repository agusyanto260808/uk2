<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// --- Ambil ID dari URL dan validasi ---
$id_rute = intval($_GET['id_rute'] ?? 0);
if ($id_rute <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location.href='../entri data/rute.php';</script>";
    exit;
}

// --- Ambil data rute ---
$q = mysqli_query($conn, "SELECT * FROM rute WHERE id_rute = $id_rute");
$rute = mysqli_fetch_assoc($q);
if (!$rute) {
    echo "<script>alert('Data rute tidak ditemukan!'); window.location.href='../entri data/rute.php';</script>";
    exit;
}

// --- Ambil semua transportasi ---
$tq = mysqli_query($conn, "SELECT * FROM transportasi ORDER BY kode ASC");

// --- Ambil semua jadwal (jam berangkat dan jam tiba) ---
$jq = mysqli_query($conn, "
    SELECT 
        TIME_FORMAT(jam_berangkat, '%H:%i') AS jam_berangkat, 
        TIME_FORMAT(jam_tiba, '%H:%i') AS jam_tiba
    FROM jadwal 
    WHERE id_rute = $id_rute 
    ORDER BY jam_berangkat ASC
");

$jadwal_list = [];
while ($j = mysqli_fetch_assoc($jq)) {
    $jadwal_list[] = $j;
}
?>

<div class="container-fluid">
    <div class="page-inner mt-4 px-3">
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Data Rute</h5>
                <a href="../entri data/rute.php" class="btn btn-light btn-sm">← Kembali</a>
            </div>

            <div class="card-body">
                <form action="../../actions/entri data/update_rute.php" method="POST" onsubmit="return validateForm()">
                    <input type="hidden" name="id_rute" value="<?= htmlspecialchars($rute['id_rute']); ?>">

                    <div class="row">
                        <!-- Tujuan -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tujuan</label>
                            <input type="text" name="tujuan" class="form-control"
                                value="<?= htmlspecialchars($rute['tujuan']); ?>" required>
                        </div>

                        <!-- Rute Awal -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rute Awal</label>
                            <input type="text" name="rute_awal" class="form-control"
                                value="<?= htmlspecialchars($rute['rute_awal']); ?>" required>
                        </div>

                        <!-- Rute Akhir -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Rute Akhir</label>
                            <input type="text" name="rute_ahir" class="form-control"
                                value="<?= htmlspecialchars($rute['rute_ahir']); ?>" required>
                        </div>

                        <!-- Harga -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Harga</label>
                            <input type="number" name="harga" class="form-control"
                                value="<?= htmlspecialchars($rute['harga']); ?>" required min="0">
                        </div>

                        <!-- Transportasi -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Transportasi</label>
                            <select name="id_transportasi" class="form-select" required>
                                <option value="">-- Pilih Transportasi --</option>
                                <?php while ($t = mysqli_fetch_assoc($tq)): ?>
                                    <option value="<?= $t['id_transportasi']; ?>"
                                        <?= ($t['id_transportasi'] == $rute['id_transportasi']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($t['kode']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Jadwal -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Jadwal Keberangkatan & Tiba</label>
                            <div id="jam-wrapper">
                                <?php if (!empty($jadwal_list)): ?>
                                    <?php foreach ($jadwal_list as $j): ?>
                                        <div class="input-group mb-2">
                                            <input type="time" name="jam_berangkat[]" class="form-control"
                                                value="<?= htmlspecialchars($j['jam_berangkat']); ?>" required>
                                            <input type="time" name="jam_tiba[]" class="form-control"
                                                value="<?= htmlspecialchars($j['jam_tiba']); ?>" required>
                                            <button type="button" class="btn btn-danger"
                                                onclick="this.parentElement.remove()">Hapus</button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="input-group mb-2">
                                        <input type="time" name="jam_berangkat[]" class="form-control" required>
                                        <input type="time" name="jam_tiba[]" class="form-control" required>
                                        <button type="button" class="btn btn-danger"
                                            onclick="this.parentElement.remove()">Hapus</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" id="addJamBtn">+ Tambah Jadwal</button>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('addJamBtn').addEventListener('click', function() {
        const div = document.createElement('div');
        div.classList.add('input-group', 'mb-2');
        div.innerHTML = `
            <input type="time" name="jam_berangkat[]" class="form-control" required>
            <input type="time" name="jam_tiba[]" class="form-control" required>
            <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">Hapus</button>
        `;
        document.getElementById('jam-wrapper').appendChild(div);
    });

    // Validasi form: minimal satu jadwal harus diisi
    function validateForm() {
        const berangkat = document.querySelectorAll('input[name="jam_berangkat[]"]');
        const tiba = document.querySelectorAll('input[name="jam_tiba[]"]');
        if (berangkat.length === 0) {
            alert('Minimal satu jadwal keberangkatan harus diisi!');
            return false;
        }
        for (let i = 0; i < berangkat.length; i++) {
            if (!berangkat[i].value || !tiba[i].value) {
                alert('Jam berangkat dan tiba tidak boleh kosong!');
                return false;
            }
        }
        return true;
    }
</script>

<?php include '../../partials/footer.php'; ?>