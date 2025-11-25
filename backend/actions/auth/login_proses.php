<?php
session_start();
include __DIR__ . "/../../../config/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan password wajib diisi!');window.location.href='../../pages/users/login.php';</script>";
        exit();
    }

    // JOIN ke tabel level biar bisa ambil nama_level
    $sql = "
        SELECT p.*, l.nama_level 
        FROM petugas p
        JOIN level l ON p.id_level = l.id_level
        WHERE p.username = ?
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Query gagal: ' . $conn->error);
    }

    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // ✅ Simpan semua data ke session
            $_SESSION['logged_in']    = true;
            $_SESSION['id_petugas']   = $row['id_petugas'];
            $_SESSION['username']     = $row['username'];
            $_SESSION['nama_petugas'] = $row['nama_petugas'];
            $_SESSION['id_level']     = $row['id_level'];
            $_SESSION['level']        = strtolower($row['nama_level']); // penting!

            // Arahkan ke dashboard
            header("Location: ../../pages/dashbord/index.php");
            exit();
        } else {
            echo "<script>alert('Password salah!');window.location.href='../../pages/users/login.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');window.location.href='../../pages/users/login.php';</script>";
    }

    $stmt->close();
}
