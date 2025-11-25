<?php
session_start();
include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

// 🔹 Cek login
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../users/login.php';
    </script>";
    exit();
}

// 🔹 Ambil semua data pemesanan dengan status pending, urutkan dari terbaru
$q = mysqli_query($conn, "
    SELECT p.*, u.nama_penumpang 
    FROM pemesanan p
    JOIN penumpang u ON u.id_penumpang = u.id_penumpang
    WHERE p.status_pemesanan = 'pending'
    ORDER BY p.id_pemesanan DESC
");
?>

<div class="page-inner mt-5 px-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fa fa-check-circle me-2"></i> Verifikasi Pemesanan
            </h5>
            <span class="badge bg-light text-primary fw-semibold">
                <?= mysqli_num_rows($q); ?> Pending
            </span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle datatable">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th class="text-center">Tanggal Pesan</th>
                            <th class="text-center">Status</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($q) > 0): ?>
                            <?php $no = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($q)): ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= htmlspecialchars($row['nama_penumpang']); ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            <?= htmlspecialchars($row['status_pemesanan']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="approve.php?id=<?= $row['id_pemesanan']; ?>"
                                            class="btn btn-success btn-sm me-1 btn-approve">
                                            <i class="fa fa-check"></i> Terima
                                        </a>
                                        <a href="reject.php?id=<?= $row['id_pemesanan']; ?>"
                                            class="btn btn-danger btn-sm btn-reject">
                                            <i class="fa fa-times"></i> Tolak
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada pemesanan pending.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .page-inner {
        margin-top: 90px;
        margin-left: 26px;
        transition: all 0.3s ease;
    }

    .sidebar-collapsed .page-inner {
        margin-left: 80px;
    }

    .card {
        border-radius: 16px;
    }

    .table thead th {
        font-weight: 600;
    }
</style>

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
            order: [
                [0, "asc"]
            ], // kolom No tetap urut normal
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

        function updateInfoText(api) {
            let perPage = api.page.len();
            $(".dataTables_length").append(
                `<span class="ms-2 text-muted fw-bold" id="perPageInfo">
                    (${perPage} data per halaman)
                </span>`
            );
        }

        table.on("length.dt", function(e, settings, len) {
            $("#perPageInfo").remove();
            $(".dataTables_length").append(
                `<span class="ms-2 text-muted fw-bold" id="perPageInfo">
                    (${len === -1 ? "Semua" : len} data per halaman)
                </span>`
            );
        });

        $(document).on("click", ".btn-approve", function(e) {
            if (!confirm("Yakin ingin menyetujui pemesanan ini?")) {
                e.preventDefault();
            }
        });

        $(document).on("click", ".btn-reject", function(e) {
            if (!confirm("Yakin ingin menolak pemesanan ini?")) {
                e.preventDefault();
            }
        });
    });
</script>