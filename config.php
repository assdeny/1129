<?php
$host = "sql208.byetcluster.com";
$user = "if0_39405445";        // user hosting Anda
$pass = "Maul270702";     // ganti dengan password hosting MySQL Anda
$db   = "if0_39405445_proyek";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode([
        'status' => false,
        'message' => 'Koneksi ke database gagal: ' . $conn->connect_error
    ]));
}
?>
