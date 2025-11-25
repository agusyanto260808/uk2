<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

$q = mysqli_query($conn, "SELECT * FROM transportasi");
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../users/login.php';
    </script>";
    exit();
}
?>

<div class="container-fluid">
    <div class="page-inner mt-4 px-3">
        <div class="card shadow border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0">Data Transportasi</h5>
                <a href="../entri data/create tr.php" class="btn btn-light btn-sm">+ Tambah</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle datatable">
                        <thead class="table-light text-center">
                            <tr>
                                <th width="5%">ID</th>
                                <th>Kode</th>
                                <th>Jumlah Kursi</th>
                                <th>Keterangan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($q) > 0): ?>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($q)): ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td><?= htmlspecialchars($row['kode']); ?></td>
                                        <td><?= htmlspecialchars($row['jumlah_kursi']); ?></td>
                                        <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                        <td>
                                            <a href="../entri data/edit_tr.php?id_transportasi=<?= $row['id_transportasi']; ?>"
                                                class="btn btn-warning btn-sm">
                                                Edit
                                            </a>
                                            <a href="../../actions/entri data/delete_tr.php?id_transportasi=<?= $row['id_transportasi']; ?>"
                                                class="btn btn-danger btn-sm btn-delete">
                                                Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data transportasi.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="">
                    <a href="../entri data/index.php" class="btn btn-secondary">
                        ← Kembali
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include '../../partials/footer.php'; ?>

<!-- ======================== DATATABLES ASSETS ======================== -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // ✅ Inisialisasi DataTable
        const table = $(".datatable").DataTable({
            pageLength: 10,
            lengthMenu: [
                [10, 20, 50, 100, -1],
                [10, 20, 50, 100, "Semua"]
            ],
            dom: '<"d-flex justify-content-between align-items-center mb-3"lfB>rtip',
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info btn-sm'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm'
                },
                {
                    extend: 'print',
                    className: 'btn btn-primary btn-sm'
                }
            ],
            language: {
                lengthMenu: "_MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang ditampilkan",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                search: "Cari:"
            },
            initComplete: function() {
                updateInfoText(this.api());
            }
        });

        // ✅ Tambahkan info jumlah per halaman
        function updateInfoText(api) {
            let perPage = api.page.len();
            $(".dataTables_length").append(
                `<span class="ms-2 text-muted fw-bold" id="perPageInfo">
                    (${perPage} data per halaman)
                </span>`
            );
        }

        // ✅ Update info saat user ubah jumlah data per halaman
        table.on("length.dt", function(e, settings, len) {
            $("#perPageInfo").remove();
            $(".dataTables_length").append(
                `<span class="ms-2 text-muted fw-bold" id="perPageInfo">
                    (${len === -1 ? "Semua" : len} data per halaman)
                </span>`
            );
        });

        // ✅ Konfirmasi sebelum hapus
        $(document).on("click", ".btn-delete", function(e) {
            if (!confirm("Yakin ingin menghapus data ini?")) {
                e.preventDefault();
            }
        });
    });
</script>