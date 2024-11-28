<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi database sudah benar

// ReCAPTCHA Secret Key
$recaptchaSecret = '6LeOX4wqAAAAAMCWYqOPt91CBn14IV29rtmVwyre'; // Ganti dengan secret key reCAPTCHA Anda

// Mengambil input dari form dengan validasi
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$recaptchaResponse = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

// Periksa apakah username dan password tidak kosong dan reCAPTCHA sudah diisi
if (!empty($username) && !empty($password) && !empty($recaptchaResponse)) {
    // Verifikasi reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        // Jika reCAPTCHA gagal
        $_SESSION['login_error'] = "Verifikasi CAPTCHA gagal. Silakan coba lagi.";
        header("Location: index.php");
        exit();
    }

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
    // Jika username atau password kosong, atau reCAPTCHA tidak terisi
    $_SESSION['login_error'] = "Username, password, dan CAPTCHA wajib diisi.";
    header("Location: index.php");
    exit;
}
?>
