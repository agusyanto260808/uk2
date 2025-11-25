<?php
include '../../../config/connection.php';

$tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
$rute_awal = mysqli_real_escape_string($conn, $_POST['rute_awal']);
$rute_ahir = mysqli_real_escape_string($conn, $_POST['rute_ahir']);
$harga = intval($_POST['harga']);
$id_transportasi = intval($_POST['id_transportasi']);
$jam_berangkat_list = $_POST['jam_berangkat'] ?? [];
$jam_tiba_list = $_POST['jam_tiba'] ?? [];

if (empty($tujuan) || empty($rute_awal) || empty($rute_ahir) || $harga <= 0 || $id_transportasi <= 0) {
    echo "<script>alert('Harap isi semua data dengan benar!'); window.history.back();</script>";
    exit;
}

// Simpan data rute
mysqli_query($conn, "
    INSERT INTO rute (tujuan, rute_awal, rute_ahir, harga, id_transportasi)
    VALUES ('$tujuan', '$rute_awal', '$rute_ahir', $harga, $id_transportasi)
");

$id_rute = mysqli_insert_id($conn);

// Simpan data jadwal (jam berangkat dan tiba)
for ($i = 0; $i < count($jam_berangkat_list); $i++) {
    $jam_berangkat = mysqli_real_escape_string($conn, $jam_berangkat_list[$i]);
    $jam_tiba = mysqli_real_escape_string($conn, $jam_tiba_list[$i]);
    if (!empty($jam_berangkat) && !empty($jam_tiba)) {
        mysqli_query($conn, "
            INSERT INTO jadwal (id_rute, jam_berangkat, jam_tiba)
            VALUES ($id_rute, '$jam_berangkat', '$jam_tiba')
        ");
    }
}

echo "<script>alert('Data rute berhasil ditambahkan!'); window.location.href='../../pages/entri data/rute.php';</script>";
