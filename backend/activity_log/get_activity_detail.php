<?php
function saveActivity($connect, $id_petugas, $aksi, $tabel, $keterangan)
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '-';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '-';

    $aksi = mysqli_real_escape_string($connect, $aksi);
    $tabel = mysqli_real_escape_string($connect, $tabel);
    $keterangan = mysqli_real_escape_string($connect, $keterangan);

    $q = "
        INSERT INTO activity_log (id_petugas, aksi, tabel, keterangan, ip_address, user_agent) 
        VALUES ('$id_petugas', '$aksi', '$tabel', '$keterangan', '$ip', '$ua')
    ";
    mysqli_query($connect, $q);
}
