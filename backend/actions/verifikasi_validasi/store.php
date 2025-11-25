<?php
session_start();
include '../../app.php';

include '../../activity_log/get_activity_detail.php';
$qkelas = mysqli_query($connect, "SELECT * FROM kelas ORDER BY nama_kelas ASC") or die(mysqli_error($connect));
if (isset($_POST['tombol'])) {
    $nama_kelas        = mysqli_real_escape_string($connect, $_POST['nama_kelas']);
    $kompetensi_keahlian     = mysqli_real_escape_string($connect, $_POST['kompetensi_keahlian']);



    $qInsert = "INSERT INTO kelas
(nama_kelas, kompetensi_keahlian) 
VALUES 
('$nama_kelas','$kompetensi_keahlian')";
    if (mysqli_query($connect, $qInsert)) {

        echo "<script>
                alert('Data berhasil disimpan');
                window.location.href='../../pages/data_kelas/index.php';
              </script>";
    } else {
        $err = mysqli_error($connect);
        echo "<script>
                alert('Gagal simpan ke database');
                console.log(`Error: $err`);
                history.back();
              </script>";
    }
}
