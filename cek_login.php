<?php
session_start();
include "koneksi.php";

// Mengambil input dari form dengan validasi
$username = isset($_POST['username']) ? mysqli_real_escape_string($koneksi, trim($_POST['username'])) : '';
$password = isset($_POST['password']) ? md5(mysqli_real_escape_string($koneksi, trim($_POST['password']))) : '';

// Cek apakah username dan password tidak kosong
if ($username && $password) {
    // Query untuk memeriksa kredensial pengguna di database
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password' AND status = 'Aktif'";
    $login = mysqli_query($koneksi, $query);

    // Periksa apakah pengguna ditemukan
    if (mysqli_num_rows($login) > 0) {
        $data = mysqli_fetch_assoc($login);

        // Set session untuk data pengguna
        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama_pengguna'] = $data['nama_pengguna'];

        // Redirect ke halaman admin
        header("Location: admin.php");
        exit;
    } else {
        // Jika login gagal, tampilkan pesan error
        $_SESSION['login_error'] = "Username atau password salah, atau akun tidak aktif.";
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
