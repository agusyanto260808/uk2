<?php
session_start();

include '../../app.php';
include '../../activity_log/get_activity_detail.php';
include './show.php';

// Ambil ID dari GET
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    echo "<script>alert('ID tidak valid');history.back();</script>";
    exit;
}

// Hapus data kelas
$qDelete = "DELETE FROM kelas WHERE id = $id";
$result = mysqli_query($connect, $qDelete);

if ($result) {
    $userId = $_SESSION['user_id'] ?? null;
    saveActivity($connect, $userId, 'Hapus', "Menghapus data kelas ID $id");
    echo "
        <script>
            alert('Data berhasil dihapus');
            window.location.href = '../../pages/kelas/index.php';
        </script>
    ";
} else {
    echo "
        <script>
            alert('Data gagal dihapus: " . mysqli_error($connect) . "');
            window.location.href = '../../pages/kelas/index.php';
        </script>
    ";
}
