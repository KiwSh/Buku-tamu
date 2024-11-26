<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi database sudah benar

// Mengambil input dari form dengan validasi
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Periksa apakah username dan password tidak kosong
if (!empty($username) && !empty($password)) {
    // Gunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ? AND status = 'Aktif'");
    $stmt->bind_param("s", $username);

    // Eksekusi query
    $stmt->execute();
    $result = $stmt->get_result();

    // Periksa apakah pengguna ditemukan
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Periksa apakah password sesuai
        if ($data['password'] === md5($password)) {
            // Set session untuk data pengguna
            $_SESSION['id_user'] = $data['id_user'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['nama_pengguna'] = $data['nama_pengguna'];

            // Redirect ke halaman admin
            header("Location: admin.php");
            exit;
        } else {
            // Jika password salah
            $_SESSION['login_error'] = "Username atau password salah.";
            header("Location: index.php");
            exit;
        }
    } else {
        // Jika username tidak ditemukan atau akun tidak aktif
        $_SESSION['login_error'] = "Username tidak ditemukan atau akun tidak aktif.";
        header("Location: index.php");
        exit;
    }
} else {
    // Jika username atau password kosong
    $_SESSION['login_error'] = "Username dan password wajib diisi.";
    header("Location: index.php");
    exit;
}
?>