<?php
session_start();
include '../../../config/connection.php';

// --- Ambil ID dari URL dengan validasi aman ---
$id_transportasi = intval($_GET['id_transportasi'] ?? 0);

if ($id_transportasi <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location.href='../../pages/entri data/transportasi.php';</script>";
    exit;
}

// --- Cek apakah data ada di database ---
$cek = mysqli_query($conn, "SELECT * FROM transportasi WHERE id_transportasi = $id_transportasi");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='../../pages/entri data/transportasi.php';</script>";
    exit;
}

// --- Hapus data ---
$delete = mysqli_query($conn, "DELETE FROM transportasi WHERE id_transportasi = $id_transportasi");

if ($delete) {
    echo "<script>alert('Data transportasi berhasil dihapus!'); window.location.href='../../pages/entri data/transportasi.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus data: " . mysqli_error($conn) . "'); window.location.href='../../pages/entri data/transportasi.php';</script>";
}
