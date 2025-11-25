<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// Ambil data type transportasi untuk dropdown
$type_query = mysqli_query($conn, "SELECT * FROM type_transportasi");
?>

<div class="container-fluid">
    <div class="page-inner">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Tambah Data Transportasi</h5>
                <a href="../entri data/transportasi.php" class="btn btn-secondary btn-sm">← Kembali</a>
            </div>
            <div class="card-body">
                <form action="../../actions/entri data/store tr.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="kode" class="form-label">Kode Transportasi</label>
                            <input type="text" class="form-control" id="kode" name="kode" required placeholder="Misal: BUS-001">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="jumlah_kursi" class="form-label">Jumlah Kursi</label>
                            <input type="number" class="form-control" id="jumlah_kursi" name="jumlah_kursi" required min="1" placeholder="Misal: 40">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="id_type_transportasi" class="form-label">Tipe Transportasi</label>
                            <select class="form-select" id="id_type_transportasi" name="id_type_transportasi" required>
                                <option value="" selected disabled>-- Pilih Tipe Transportasi --</option>
                                <?php while ($type = mysqli_fetch_assoc($type_query)): ?>
                                    <option value="<?= $type['id_type_transportasi']; ?>">
                                        <?= htmlspecialchars($type['nama_type']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Opsional"></textarea>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>