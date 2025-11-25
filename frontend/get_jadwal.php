<?php
include '../config/connection.php';

$rute_awal = mysqli_real_escape_string($conn, $_GET['rute_awal'] ?? '');
$rute_ahir = mysqli_real_escape_string($conn, $_GET['rute_ahir'] ?? '');

if ($rute_awal && $rute_ahir) {
    $query = mysqli_query($conn, "
        SELECT 
            j.id_jadwal, 
            TIME_FORMAT(j.jam_berangkat, '%H:%i') AS jam_berangkat,
            TIME_FORMAT(j.jam_tiba, '%H:%i') AS jam_tiba
        FROM jadwal j
        JOIN rute r ON j.id_rute = r.id_rute
        WHERE r.rute_awal = '$rute_awal' AND r.rute_ahir = '$rute_ahir'
        ORDER BY j.jam_berangkat ASC
    ");

    $data = [];
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
