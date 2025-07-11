<?php

$server = "localhost";
$user = "root";
$pass = "";
$db = "UASPWL2514";

$koneksi = new mysqli($server, $user, $pass, $db);

if ($koneksi->connect_error) {
    exit("Koneksi Gagal"); 
}
?>