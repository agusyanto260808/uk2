<?php
include '../../../config/connection.php';

$id = intval($_POST['id_transportasi']);
$kode = mysqli_real_escape_string($conn, $_POST['kode']);
$jumlah_kursi = intval($_POST['jumlah_kursi']);
$id_type_transportasi = intval($_POST['id_type_transportasi']);
$keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

$query = "
    UPDATE transportasi 
    SET 
        kode = '$kode',
        jumlah_kursi = $jumlah_kursi,
        id_type_transportasi = $id_type_transportasi,
        keterangan = '$keterangan'
    WHERE id_transportasi = $id
";

if (mysqli_query($conn, $query)) {
    echo "<script>
        alert('Data transportasi berhasil diperbarui!');
        window.location.href = '../../pages/entri data/transportasi.php';
    </script>";
} else {
    echo "<script>
        alert('Gagal memperbarui data: " . mysqli_error($conn) . "');
        window.history.back();
    </script>";
}
