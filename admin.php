<?php 
session_start();
include 'header.php';
include 'koneksi.php'; // Pastikan ini menyertakan koneksi ke database

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

// Jika tombol simpan di klik
if (isset($_POST['bsimpan'])) {
    $tgl = date('Y-m-d');
    $nama = htmlspecialchars($_POST['nama'], ENT_QUOTES);
    $alamat = htmlspecialchars($_POST['alamat'], ENT_QUOTES);
    $tujuan = htmlspecialchars($_POST['tujuan'], ENT_QUOTES);
    $nope = htmlspecialchars($_POST['nope'], ENT_QUOTES);
    $fotoData = $_POST['foto']; // Foto dalam bentuk base64

    // Memproses foto jika ada
    $fotoName = '';
    if (!empty($fotoData)) {
        $folderPath = "uploads/";
        $image_parts = explode(";base64,", $fotoData);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fotoName = uniqid() . '.' . $image_type;
        $file = $folderPath . $fotoName;

        // Pastikan folder upload ada
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // Menyimpan file ke folder uploads
        $isFileSaved = file_put_contents($file, $image_base64);
        if (!$isFileSaved) {
            echo "<script>alert('Gagal menyimpan foto!');</script>";
            exit;
        }
    }

    // Simpan data ke database
    $simpan = mysqli_query($koneksi, "INSERT INTO tamu (tanggal, nama, alamat, tujuan, nope, foto) 
        VALUES ('$tgl', '$nama', '$alamat', '$tujuan', '$nope', '$fotoName')");

    if ($simpan) {
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
    } else {
        echo "<script>alert('Simpan data gagal, Mohon coba lagi..!');</script>";
        echo "<script>console.error('Error: " . mysqli_error($koneksi) . "');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu - Admin</title>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #foto {
            width: 100%;
            display: none;
        }
        #video {
            width: 100%;
            height: auto;
        }
        #canvas {
            display: none;
        }

        .modal-body {
            position: relative;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>

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
                        <form method="POST" action="" enctype="multipart/form-data">
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

                            <div class="form-group">
                                <label for="foto">Ambil Foto Pengunjung:</label>
                                <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#cameraModal">
                                    Ambil Foto
                                </button>
                            </div>

                            <!-- Input hidden untuk foto -->
                            <input type="hidden" name="foto" id="foto_input" />

                            <button type="submit" name="bsimpan" class="btn btn-primary btn-block">Simpan</button>
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
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Tujuan</th>
                                <th>No.HP</th>
                                <th>Foto</th>
                            </tr>
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
                                    <td><img src='uploads/{$row['foto']}' alt='' width='100'></td>
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

    <!-- Modal untuk ambil foto -->
<div class="modal fade" id="cameraModal" tabindex="-1" role="dialog" aria-labelledby="cameraModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cameraModalLabel">Ambil Foto Pengunjung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <video id="video" width="100%" height="auto" autoplay></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <img id="foto" style="display: none;" src="" alt="Foto Pengunjung" width="100%">

                <button type="button" class="btn btn-success btn-block" id="capture">Ambil Foto</button>
                <button type="button" class="btn btn-warning btn-block" id="retake" style="display: none;">Pencet Ulang</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


    <script>
        // Menampilkan video
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const foto = document.getElementById('foto');
        const captureButton = document.getElementById('capture');
        const retakeButton = document.getElementById('retake');
        const fotoInput = document.getElementById('foto_input');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                video.srcObject = stream;
            })
            .catch(function (error) {
                console.error("Error accessing webcam:", error);
            });

        // Ambil foto
        captureButton.addEventListener('click', function () {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/png');
            foto.src = dataUrl;
            foto.style.display = 'block';
            captureButton.style.display = 'none';
            retakeButton.style.display = 'block';
            fotoInput.value = dataUrl; // Simpan foto ke input tersembunyi
        });

        // Pencet ulang
        retakeButton.addEventListener('click', function () {
            foto.style.display = 'none';
            captureButton.style.display = 'block';
            retakeButton.style.display = 'none';
            fotoInput.value = ''; // Clear the photo input
        });

    </script>
</body>
</html>
