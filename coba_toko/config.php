<?php

$host = 'localhost'; // Host database
$db   = 'coba_tokoo'; // Nama database
$user = 'root';      // Username database
$pass = '';          // Password database

// Buat koneksi
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>