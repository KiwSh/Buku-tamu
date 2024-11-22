<?php include "header.php"; ?>

<body>

    <body style="background: rgb(43,166,191); background: linear-gradient(0deg, rgba(43,166,191,1) 0%, rgba(194,194,194,1) 100%);">

<div class="row">
    <div class="col-md-12">
        <div class="card shadow mb-4 mt-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rekapitulasi data pengunjung</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="" class="text-center">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dari tanggal</label>
                                <input class="form-control" type="date" name="tanggal1" value="<?= isset($_POST['tanggal1']) ? $_POST['tanggal1'] : '' ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sampai tanggal</label>
                                <input class="form-control" type="date" name="tanggal2" value="<?= isset($_POST['tanggal2']) ? $_POST['tanggal2'] : '' ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <button class="btn btn-primary form-control" name="btampilkan"><i class="fa fa-search"></i> Tampilkan</button>
                        </div>
                        <div class="col-md-2">
                            <a href="admin.php" class="btn btn-danger form-control"><i class="fa fa-backward"> Kembali</i></a>
                        </div>
                    </div>
                </form>

                <?php
                if (isset($_POST['btampilkan'])) :
                ?>

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
                            if (isset($_POST['btampilkan'])) {
                                $tanggal1 = $_POST['tanggal1'];
                                $tanggal2 = $_POST['tanggal2'];
                                
                                $tampil = mysqli_query($koneksi, "SELECT * FROM tamu WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY id DESC");
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
                            <?php 
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                    <center>
                    <form method="POST" action="exportexcel.php">
                        <input type="hidden" name="tanggal1" value="<?= $_POST['tanggal1'] ?>">
                        <input type="hidden" name="tanggal2" value="<?= $_POST['tanggal2'] ?>">
                        <button class="btn btn-success form-control" name="bexport"><i class="fa fa-download"> Export data Excel</i></button>
                    </form>

                    </center>

                </div>

                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
</body>

<?php include "footer.php"; ?>
