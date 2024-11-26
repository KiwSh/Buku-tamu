<?php 
session_start();
include 'header.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

// Jika tombol simpan di klik
if (isset($_POST['bsimpan']) && !isset($_SESSION['data_saved'])) {
    $tgl = date('Y-m-d');
    $nama = htmlspecialchars($_POST['nama'], ENT_QUOTES);
    $alamat = htmlspecialchars($_POST['alamat'], ENT_QUOTES);
    $tujuan = htmlspecialchars($_POST['tujuan'], ENT_QUOTES);
    $nope = htmlspecialchars($_POST['nope'], ENT_QUOTES);

    // Simpan data ke database
    $simpan = mysqli_query($koneksi, "INSERT INTO tamu VALUES ('','$tgl','$nama','$alamat','$tujuan','$nope')");

    if ($simpan) {
        $_SESSION['data_saved'] = true;
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Simpan Data Berhasil!',
                text: 'Terima kasih telah mengisi buku tamu.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'admin.php';
                }
            });
        </script>";
        exit();
    } else {
        echo "<script>alert('Simpan data gagal, Mohon coba lagi..!');</script>";
    }
}
?>

<body style="background: linear-gradient(0deg, rgba(43,166,191,1) 0%, rgba(194,194,194,1) 100%);">
<div class="container">
    <div class="head text-center">
        <img src="assets/img/logo-diskominfo.png" width="500">
        <h2 class="text-white" style="text-shadow: 1px 1px 3px black;">Buku Tamu <br>DISKOMINFO KAB.BOGOR</h2>
    </div>

    <div class="row mt-2">
        <!-- Form Input Data -->
        <div class="col-lg-7 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="h4 text-center">Identitas Pengunjung</h1>
                    <form method="POST" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nama" placeholder="Nama Pengunjung" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="alamat" placeholder="Alamat Pengunjung" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="tujuan" placeholder="Tujuan Pengunjung" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="nope" placeholder="No.HP Pengunjung" required>
                        </div>
                        <button type="submit" name="bsimpan" class="btn btn-primary btn-block">Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistik Pengunjung -->
        <div class="col-lg-5 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <h1 class="h4 text-center">Statistik Pengunjung</h1>
                    <?php
                    $tgl_sekarang = date('Y-m-d');
                    $kemarin = date('Y-m-d', strtotime('-1 day'));
                    $bulan_ini = date('Y-m');
                    $minggu_lalu = date('Y-m-d', strtotime('-1 week'));

                    $tgl_sekarang_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal = '$tgl_sekarang'"))[0];
                    $kemarin_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal = '$kemarin'"))[0];
                    $bulan_ini_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal LIKE '$bulan_ini%'"))[0];
                    $total_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu"))[0];
                    ?>

                    <table class="table">
                        <tr><td>Hari Ini</td><td>: <?= $tgl_sekarang_count ?></td></tr>
                        <tr><td>Kemarin</td><td>: <?= $kemarin_count ?></td></tr>
                        <tr><td>Bulan Ini</td><td>: <?= $bulan_ini_count ?></td></tr>
                        <tr><td>Total</td><td>: <?= $total_count ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pengunjung -->
    <div class="card shadow mb-4">
        <div class="card-header"><h6>Data Pengunjung Hari Ini [<?= date('d-m-Y') ?>]</h6></div>
        <div class="card-body">
            <a href="rekapitulasi.php" class="btn btn-success mb-3">Rekapitulasi data pengunjung</a>
            <a href="logout.php" class="btn btn-danger mb-3">Logout</a>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr><th>No.</th><th>Tanggal</th><th>Nama</th><th>Alamat</th><th>Tujuan</th><th>No. HP</th></tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = mysqli_query($koneksi, "SELECT * FROM tamu WHERE tanggal = '$tgl_sekarang'");
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['tanggal']}</td>
                                <td>{$row['nama']}</td>
                                <td>{$row['alamat']}</td>
                                <td>{$row['tujuan']}</td>
                                <td>{$row['nope']}</td>
                              </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
