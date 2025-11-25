<?php
session_start();
include '../../../config/connection.php';

// Pastikan hanya admin yang login bisa akses
if (!isset($_SESSION['id_petugas'])) {
    echo "<script>
        alert('Anda belum login!');
        window.location.href='../../users/login.php';
    </script>";
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Update status ke "Tolak"
    $sql = "UPDATE pemesanan SET status_pemesanan='Tolak' WHERE id_pemesanan=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Simpan log aktivitas
        $user_id = $_SESSION['id_petugas'];
        $aksi = "Verifikasi Pemesanan";
        $keterangan = "Admin ID $user_id menolak pemesanan dengan ID $id (Tolak).";

        $log = $conn->prepare("INSERT INTO activity_log (user_id, aksi, keterangan) VALUES (?, ?, ?)");
        $log->bind_param("iss", $user_id, $aksi, $keterangan);
        $log->execute();

        echo "<script>
            alert('Pemesanan berhasil ditolak (Tolak)!');
            window.location.href='index.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menolak data!');
            window.location.href='index.php';
        </script>";
    }
} else {
    header("Location: index.php");
    exit();
}
