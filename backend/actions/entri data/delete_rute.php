<?php
include '../../../config/connection.php';

$id_rute = intval($_GET['id_rute'] ?? 0);

if ($id_rute <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location.href='../../pages/entri data/rute.php';</script>";
    exit;
}

// Cek apakah data rute masih ada
$cek = mysqli_query($conn, "SELECT id_rute FROM rute WHERE id_rute = $id_rute");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Data rute tidak ditemukan!'); window.location.href='../../pages/entri data/rute.php';</script>";
    exit;
}

// Hapus semua jadwal terkait rute ini terlebih dahulu
mysqli_query($conn, "DELETE FROM jadwal WHERE id_rute = $id_rute");

// (Opsional) Jika kamu punya tabel pemesanan yang juga terhubung dengan rute, hapus juga:
// mysqli_query($conn, "DELETE FROM pemesanan WHERE id_rute = $id");

// Hapus data rute
$hapus = mysqli_query($conn, "DELETE FROM rute WHERE id_rute = $id_rute");

if ($hapus) {
    echo "<script>alert('Data rute dan jadwal terkait berhasil dihapus!'); window.location.href='../../pages/entri data/rute.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data rute!'); window.location.href='../../pages/entri data/rute.php';</script>";
}
