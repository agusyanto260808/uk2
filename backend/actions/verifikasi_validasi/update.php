<?php
session_start();
include '../../app.php';
include '../../activity_log/get_activity_detail.php';

if (isset($_POST['tombol'])) {

    function escape($value)
    {
        global $connect;
        return mysqli_real_escape_string($connect, trim($value));
    }

    // Ambil input dari form
    $id_kelas = intval($_POST['id_kelas'] ?? 0); // ✅ ambil id_kelas dari form
    $nama_kelas = escape($_POST['nama_kelas'] ?? '');
    $kompetensi_keahlian = escape($_POST['kompetensi_keahlian'] ?? '');



    // ✅ Perbaiki query UPDATE (hapus koma sebelum WHERE)
    $qUpdate = "
        UPDATE kelas SET 
            nama_kelas = '$nama_kelas',
            kompetensi_keahlian = '$kompetensi_keahlian'
        WHERE id_kelas = '$id_kelas'
    ";

    if (mysqli_query($connect, $qUpdate)) {

        echo "
        <script>
            alert('Data berhasil diperbarui');
            window.location.href = '../../pages/data_kelas/index.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Gagal update: " . mysqli_error($connect) . "');
            history.back();
        </script>
        ";
    }
}
