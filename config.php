<?php
$host   = "sqlXXX.infinityfree.com";
$user   = "if0_40656771";
$pass   = "Passs";
$db     = "if0_40656771_bengkel";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Gagal terhubung: " . mysqli_connect_error());
}
?>
