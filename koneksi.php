<?php
$host = "localhost"; // Nama host
$user = "root";      // Username MySQL
$pass = "";          // Password MySQL
$db   = "db_bukutamu"; // Ganti dengan nama database Anda

// Membuat koneksi
$koneksi = new mysqli($host, $user, $pass, $db);

// Periksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
