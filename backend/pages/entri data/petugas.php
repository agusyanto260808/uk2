<?php
session_start();
if ($_SESSION['id_level'] != 1) {
    die("Akses ditolak!");
}

include '../../partials/header.php';
include '../../partials/navbar.php';
include '../../partials/sidebar.php';
include '../../../config/connection.php';

$q = mysqli_query($conn, "
    SELECT p.*, l.nama_level 
    FROM petugas p 
    JOIN level l ON p.id_level = l.id_level
");
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu');
        window.location.href = '../users/login.php';
    </script>";
    exit();
}
?>

<div class="container-fluid">
    <div class="page-inner">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5>Data Petugas</h5>

            </div>
            <div class="card-body">
                <table class="table table-bordered datatable">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($q)): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($row['username']); ?></td>
                                <td><?= htmlspecialchars($row['nama_petugas']); ?></td>
                                <td><?= htmlspecialchars($row['nama_level']); ?></td>
                                <td>
                                    <a href="../entri data/edit_petugas.php?id_petugas=<?= $row['id_petugas']; ?>"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <a href="delete.php?id=<?= $row['id_petugas']; ?>"
                                        class="btn btn-danger btn-sm btn-delete">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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
        // 🔹 Inisialisasi DataTable
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

        // 🔹 Tambahkan info jumlah per halaman
        function updateInfoText(api) {
            let perPage = api.page.len();
            $(".dataTables_length").append(
                `<span class="ms-2 text-muted fw-bold" id="perPageInfo">(${perPage} data per halaman)</span>`
            );
        }

        table.on("length.dt", function(e, settings, len) {
            $("#perPageInfo").remove();
            $(".dataTables_length").append(
                `<span class="ms-2 text-muted fw-bold" id="perPageInfo">(${len === -1 ? "Semua" : len} data per halaman)</span>`
            );
        });

        // 🔹 Konfirmasi sebelum hapus
        $(document).on("click", ".btn-delete", function(e) {
            if (!confirm("Yakin ingin menghapus data ini?")) {
                e.preventDefault();
            }
        });
    });
</script>