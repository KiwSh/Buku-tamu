<?php 
session_start();
include 'header.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: index.php");
    exit;
}

// Jika sudah login, tampilkan halaman admin
?>
<div class="container mt-3">
    <div class="alert alert-info text-center" style="border: 1px solid #007bff; border-radius: 8px; padding: 15px; background-color: #e9f7fd;">
        <strong>Selamat datang, <?= $_SESSION['nama_pengguna'] ?>!</strong>
    </div>
</div>

<!-- Uji coba jika tombol simpan di klik -->
<?php
if (isset($_POST['bsimpan']) && !isset($_SESSION['data_saved'])) {
    $tgl = date('Y-m-d');

    $nama = htmlspecialchars($_POST['nama'], ENT_QUOTES);
    $alamat = htmlspecialchars($_POST['alamat'], ENT_QUOTES);
    $tujuan = htmlspecialchars($_POST['tujuan'], ENT_QUOTES);
    $nope = htmlspecialchars($_POST['nope'], ENT_QUOTES);

    // Simpan data ke database
    $simpan = mysqli_query($koneksi, "INSERT INTO tamu VALUES ('','$tgl','$nama','$alamat','$tujuan','$nope')");

    if ($simpan) {
        $_SESSION['data_saved'] = true; // Tandai bahwa data telah tersimpan
        // Redirect ke halaman admin dengan notifikasi
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Simpan Data Berhasil!',
                text: 'Terima kasih telah mengisi buku tamu.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'admin.php'; // Redirect setelah penyimpanan
                }
            });
        </script>";
        exit();
    } else {
        echo "<script>alert('Simpan data gagal, Mohon coba lagi..!');</script>";
    }
}
?>

<body style="background: rgb(43,166,191); background: linear-gradient(0deg, rgba(43,166,191,1) 0%, rgba(194,194,194,1) 100%);">
<div class="container">
    <div class="head text-center">
        <img src="assets/img/logo-diskominfo.png" width="500">
        <h2 class="text-white" style="text-shadow: 1px 1px 3px black;">Buku Tamu <br>DISKOMINFO KAB.BOGOR</h2>
    </div>

    <div class="row mt-2">
        <div class="col-lg-7 mb-3">
            <div class="card shadow bg-gradient-light">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4" style="text-shadow: 1px 1px 3px gray;">Identitas Pengunjung</h1>
                    </div>
                    <form class="user" method="POST" action="">
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nama" placeholder="Nama Pengunjung" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="alamat" placeholder="Alamat Pengunjung" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="tujuan" placeholder="Tujuan Pengunjung" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" name="nope" placeholder="No.HP Pengunjung" required>
                        </div>
                        <button type="submit" name="bsimpan" class="btn btn-primary btn-user btn-block">Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-3">
            <div class="card shadow">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4" style="text-shadow: 1px 1px 3px gray;">Statistik Pengunjung</h1>
                    </div>
                    <?php
                        $tgl_sekarang = date('Y-m-d');
                        $kemarin = date('Y-m-d', strtotime('-1 day'));
                        $bulan_ini = date('Y-m');
                        $minggu_lalu = date('Y-m-d', strtotime('-1 week'));

                        // Query untuk menghitung jumlah pengunjung
                        $tgl_sekarang_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal = '$tgl_sekarang'"))[0];
                        $kemarin_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal = '$kemarin'"))[0];
                        $bulan_ini_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal LIKE '$bulan_ini%'"))[0];
                        $minggu_ini_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu WHERE tanggal BETWEEN '$minggu_lalu' AND '$tgl_sekarang'"))[0];
                        $total_count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) FROM tamu"))[0];
                    ?>

                    <table class="table table-bordered">
                        <tr>
                            <td>Hari Ini</td>
                            <td>: <?= $tgl_sekarang_count ?></td>
                        </tr>
                        <tr>
                            <td>Kemarin</td>
                            <td>: <?= $kemarin_count ?></td>
                        </tr>
                        <tr>
                            <td>Minggu Ini</td>
                            <td>: <?= $minggu_ini_count ?></td>
                        </tr>
                        <tr>
                            <td>Bulan Ini</td>
                            <td>: <?= $bulan_ini_count ?></td>
                        </tr>
                        <tr>
                            <td>Keseluruhan</td>
                            <td>: <?= $total_count ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" >Data Pengunjung Hari Ini [<?= date('d-m-Y') ?>]</h6>
        </div>
        <div class="card-body">
            <a href="rekapitulasi.php" class="btn btn-success mb-3"><i class="fa fa-table"></i> Rekapitulasi data pengunjung</a>
            <a href="logout.php" class="btn btn-danger mb-3"><i class="fa fa-sign-out-alt"></i> logout</a>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama Pengunjung</th>
                            <th>Alamat</th>
                            <th>Tujuan</th>
                            <th>No. HP</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No.</th>
                            <th>Tanggal</th>
                            <th>Nama Pengunjung</th>
                            <th>Alamat</th>
                            <th>Tujuan</th>
                            <th>No. HP</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $tampil = mysqli_query($koneksi, "SELECT * FROM tamu WHERE tanggal = '$tgl_sekarang' ORDER BY id DESC");
                        $no = 1;
                        while ($data = mysqli_fetch_array($tampil)) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $data['tanggal'] ?></td>
                                <td><?= $data['nama'] ?></td>
                                <td><?= $data['alamat'] ?></td>
                                <td><?= $data['tujuan'] ?></td>
                                <td><?= $data['nope'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>

<?php
// Hapus session setelah selesai
unset($_SESSION['data_saved']);
include 'footer.php';
?>
