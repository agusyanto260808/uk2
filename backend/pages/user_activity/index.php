<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// ✅ Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ✅ Ambil data aktivitas
$q = $conn->query("
    SELECT a.*, p.nama_petugas 
    FROM activity_log a
    LEFT JOIN petugas p ON a.user_id = p.id_petugas
    ORDER BY a.waktu DESC
");
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../users/login.php';
    </script>";
    exit();
}
?>

<div class="page-inner mt-5 px-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="fw-bold mb-0 ">
                <i class="fa fa-history me-2"></i> Riwayat Aktivitas Admin
            </h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable w-100">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                            <th>Keterangan</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($q && $q->num_rows > 0): ?>
                            <?php
                                 while ($row = $q->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_petugas'] ?? 'Tidak diketahui'); ?></td>
                                    <td><?= htmlspecialchars($row['aksi']); ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                    <td><?= htmlspecialchars($row['waktu']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada data aktivitas.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
        const table = $(".datatable").DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [
                [10, 20, 50, 100, -1],
                [10, 20, 50, 100, "Semua"]
            ],
            dom: '<"d-flex justify-content-between align-items-center mb-3"lfB>rtip',
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-secondary btn-sm',
                    text: '<i class="fa fa-copy me-1"></i>Salin'
                },
                {
                    extend: 'csv',
                    className: 'btn btn-info btn-sm',
                    text: '<i class="fa fa-file-csv me-1"></i>CSV'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success btn-sm',
                    text: '<i class="fa fa-file-excel me-1"></i>Excel'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm',
                    text: '<i class="fa fa-file-pdf me-1"></i>PDF'
                },
                {
                    extend: 'print',
                    className: 'btn btn-primary btn-sm',
                    text: '<i class="fa fa-print me-1"></i>Cetak'
                }
            ],
            language: {
                lengthMenu: "_MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang ditampilkan",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                search: "Cari:",
                paginate: {
                    previous: "Sebelumnya",
                    next: "Berikutnya"
                }
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
    });
</script>