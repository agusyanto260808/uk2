<?php
session_start();
include __DIR__ . '/../../../config/connection.php';

// Cek session petugas
if (isset($_SESSION['id_petugas'])) {
    $id_petugas = $_SESSION['id_petugas'];
    $nama       = $_SESSION['nama_petugas'] ?? 'Unknown';
    $level      = $_SESSION['level'] ?? '';
    $ip         = $_SERVER['REMOTE_ADDR'];

    // ✅ Sesuaikan nama kolom dengan tabel kamu
    $qLog = "INSERT INTO activity_log (user_id, aksi, tabel, keterangan, ip_address) 
             VALUES (?, 'logout', 'auth', ?, ?)";
    if ($stmt = $conn->prepare($qLog)) {
        $keterangan = "Petugas $nama logout";
        $stmt->bind_param("iss", $id_petugas, $keterangan, $ip);
        $stmt->execute();
        $stmt->close();
    }
}

// Hapus session
session_unset();
session_destroy();

// Redirect ke login page
header("Location: ../../pages/users/login.php");
exit();
