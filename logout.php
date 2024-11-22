<?php
session_start();

// Periksa apakah sesi login sudah diatur
if (!isset($_SESSION['id_user'])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu untuk logout.');
        document.location='index.php'; // Alihkan ke halaman login
    </script>";
    exit(); // Hentikan eksekusi script
}

// Jika sesi login ada, proses logout
unset($_SESSION['id_user']);
unset($_SESSION['password']);
unset($_SESSION['nama_pengguna']);
unset($_SESSION['username']);

session_destroy();
echo "<script>
    alert('Anda berhasil logout..!');
    document.location='index.php'; // Alihkan ke halaman login setelah logout
</script>";
?>
