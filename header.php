<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Periksa apakah pengguna sudah login
if (empty($_SESSION['username'])) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
        <title>Notifikasi</title>
        <!-- SweetAlert2 CSS -->
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'>
        <!-- SweetAlert2 JS -->
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            html: 'Mohon untuk <b>login terlebih dahulu</b> jika ingin mengakses halaman ini..!',
            showCloseButton: true,
            confirmButtonText: 'Login',
            confirmButtonAriaLabel: 'Login'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'index.php'; // Redirect ke halaman login
            }
        });
    </script>
    </body>
    </html>";
    exit; // Hentikan eksekusi lebih lanjut jika user belum login
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Buku Tamu | DISKOMINFO KAB.BOGOR</title>
    <link rel="icon" href="assets/img/logo-diskominfo.png" type="image/x-icon">
    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Custom styles for this page -->
    <link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-success">
    <div class="container">
    <?php include 'koneksi.php'; ?>