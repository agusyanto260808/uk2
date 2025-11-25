<?php
session_start();
include '../config/connection.php';
include 'partials/header.php';
include 'partials/navbar.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_rute        = intval($_POST['id_rute']);
    $id_jadwal      = intval($_POST['id_jadwal']);
    $tanggal        = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $total          = floatval($_POST['total']);
    $kode_pemesanan = mysqli_real_escape_string($conn, $_POST['kode_pemesanan']);

    // Buat kode kursi otomatis (kalau sistem belum ada pilih kursi manual)
    $kode_kursi = 'KRS' . rand(100, 999);

    // Pastikan rute dan jadwal valid
    $query = "
        SELECT r.id_rute, j.id_jadwal, r.rute_ahir
        FROM rute r
        JOIN jadwal j ON r.id_rute = j.id_rute
        WHERE r.id_rute = $id_rute AND j.id_jadwal = $id_jadwal
        LIMIT 1
    ";
    $result = mysqli_query($conn, $query);

    if (!$result || mysqli_num_rows($result) === 0) {
        echo "<script>alert('Rute atau jadwal tidak ditemukan'); history.back();</script>";
        exit;
    }

    $r = mysqli_fetch_assoc($result);

    // Simpan ke tabel pemesanan (pakai id_jadwal + kode_kursi)
    $insert = "
        INSERT INTO pemesanan (
            kode_pemesanan, 
            tanggal_pemesanan, 
            id_rute,
            id_jadwal,
            tujuan, 
            tanggal_berangkat,
            total_bayar, 
            status_pemesanan,
            kode_kursi
        ) VALUES (
            '$kode_pemesanan',
            NOW(),
            {$r['id_rute']},
            {$r['id_jadwal']},
            '{$r['rute_ahir']}',
            '$tanggal',
            $total,
            'pending',
            '$kode_kursi'
        )
    ";

    if (mysqli_query($conn, $insert)) {
        $id_pemesanan = mysqli_insert_id($conn);
        header("Location: cetak_tiket.php?id=$id_pemesanan");
        exit;
    } else {
        echo "Gagal menyimpan pemesanan: " . mysqli_error($conn);
    }
}
