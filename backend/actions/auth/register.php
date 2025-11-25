<?php
session_start();
include __DIR__ . "/../../../config/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $nama_petugas = trim($_POST['nama_petugas']);
    $id_level = trim($_POST['id_level']); // admin / petugas

    // Validasi sederhana
    if (empty($username) || empty($password) || empty($nama_petugas)) {
        echo "<script>
            alert('Semua field harus diisi!');
            window.location.href='register.php';
        </script>";
        exit();
    }

    // ✅ Cek username sudah dipakai atau belum
    $check = $conn->prepare("SELECT * FROM petugas WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Username sudah digunakan, silakan pilih yang lain!');
            window.location.href='../../pages/users/register.php';
        </script>";
        exit();
    }

    // ✅ Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Insert data
    $sql = "INSERT INTO petugas (username, password, nama_petugas, id_level) 
            VALUES (?, ?, ?, ?)";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sssi", $username, $hashedPassword, $nama_petugas, $id_level);

    if ($stmt->execute()) {
        echo "<script>
            alert('Registrasi berhasil! Silakan login.');
            window.location.href='../../pages/users/login.php';
        </script>";
    } else {
        echo "<script>
            alert('Registrasi gagal, coba lagi.');
            window.location.href='../../pages/users/register.php';
        </script>";
    }
}
