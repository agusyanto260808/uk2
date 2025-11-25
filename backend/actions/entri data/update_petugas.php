<?php
include '../../../config/connection.php';

$id_petugas = intval($_POST['id_petugas'] ?? 0);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$nama_petugas = mysqli_real_escape_string($conn, $_POST['nama_petugas']);
$id_level = intval($_POST['id_level']);
$password = $_POST['password'] ?? '';

if ($id_petugas <= 0 || empty($username) || empty($nama_petugas) || $id_level <= 0) {
    echo "<script>alert('Data tidak lengkap!'); window.history.back();</script>";
    exit;
}

if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "
        UPDATE petugas 
        SET username = '$username', nama_petugas = '$nama_petugas', 
            password = '$hashed', id_level = $id_level 
        WHERE id_petugas = $id_petugas
    ";
} else {
    $sql = "
        UPDATE petugas 
        SET username = '$username', nama_petugas = '$nama_petugas', 
            id_level = $id_level 
        WHERE id_petugas = $id_petugas
    ";
}

if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Data petugas berhasil diperbarui!'); window.location.href='../../pages/entri data/petugas/index.php';</script>";
} else {
    echo "<script>alert('Terjadi kesalahan: " . mysqli_error($conn) . "'); window.history.back();</script>";
}
