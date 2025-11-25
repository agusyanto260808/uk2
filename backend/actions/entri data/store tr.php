<?php
include '../../../config/connection.php';

$kode = mysqli_real_escape_string($conn, $_POST['kode']);
$jumlah_kursi = intval($_POST['jumlah_kursi']);
$id_type_transportasi = intval($_POST['id_type_transportasi']);
$keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

$query = "
    INSERT INTO transportasi (kode, jumlah_kursi, id_type_transportasi, keterangan)
    VALUES ('$kode', $jumlah_kursi, $id_type_transportasi, '$keterangan')
";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('Data transportasi berhasil ditambahkan!');
        window.location.href = '../../pages/entri data/transportasi.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal menambahkan data: " . mysqli_error($conn) . "');
        window.history.back();
    </script>";
}
