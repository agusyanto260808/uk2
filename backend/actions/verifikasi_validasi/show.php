<?php
include '../../app.php'; // << ini yang penting, biar $connect tersedia

if (!isset($_GET['id_kelas'])) {
    echo "
<script>
alert('Tidak bisa memilih Id ini');
window.location.href='../../pages/data_kelas/index.php';
</script>
";
    exit;
}

$id_kelas = $_GET['id_kelas'];

$qSelect = "SELECT * FROM kelas WHERE id_kelas='$id_kelas'";
$result = mysqli_query($connect, $qSelect) or die(mysqli_error($connect));

$kelas = $result->fetch_object();
if (!$kelas) {
    die("Data tidak ditemukan");
}
