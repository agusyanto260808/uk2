<?php
include '../../../config/connection.php';

$id_rute = intval($_POST['id_rute'] ?? 0);
$tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
$rute_awal = mysqli_real_escape_string($conn, $_POST['rute_awal']);
$rute_ahir = mysqli_real_escape_string($conn, $_POST['rute_ahir']);
$harga = intval($_POST['harga']);
$id_transportasi = intval($_POST['id_transportasi']);
$jam_berangkat = $_POST['jam_berangkat'] ?? [];
$jam_tiba = $_POST['jam_tiba'] ?? [];

if ($id_rute <= 0) {
    echo "<script>alert('ID tidak valid!'); window.location.href='../../pages/entri data/rute.php';</script>";
    exit;
}

// Update data rute
$update = mysqli_query($conn, "
    UPDATE rute SET 
        tujuan = '$tujuan',
        rute_awal = '$rute_awal',
        rute_ahir = '$rute_ahir',
        harga = $harga,
        id_transportasi = $id_transportasi
    WHERE id_rute = $id_rute
");

if (!$update) {
    die('Gagal update rute: ' . mysqli_error($conn));
}

// Hapus jadwal lama
mysqli_query($conn, "DELETE FROM jadwal WHERE id_rute = $id_rute");

// Simpan jadwal baru (jam berangkat & jam tiba)
$stmt = $conn->prepare("INSERT INTO jadwal (id_rute, jam_berangkat, jam_tiba) VALUES (?, ?, ?)");
for ($i = 0; $i < count($jam_berangkat); $i++) {
    $berangkat = $jam_berangkat[$i];
    $tiba = $jam_tiba[$i];
    if (!empty($berangkat) && !empty($tiba)) {
        $stmt->bind_param('iss', $id_rute, $berangkat, $tiba);
        $stmt->execute();
    }
}
$stmt->close();

echo "<script>alert('Data rute berhasil diperbarui!'); window.location.href='../../pages/entri data/rute.php';</script>";
