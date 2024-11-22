<?php
include "koneksi.php";

if (isset($_POST['tanggal1']) && isset($_POST['tanggal2'])) {
    $tanggal1 = $_POST['tanggal1'];
    $tanggal2 = $_POST['tanggal2'];

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Export_Excel_Data_Pengunjung.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    ?>

    <table border="1">
        <thead>
            <tr>
                <th colspan="6">Rekapitulasi Data Pengunjung</th>
            </tr>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Nama Pengunjung</th>
                <th>Alamat</th>
                <th>Tujuan</th>
                <th>No. HP</th>
            </tr>
        </thead>
        <tbody>
            <?php
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
            ?>
        </tbody>
    </table>

<?php
} else {
    echo "Silakan pilih rentang tanggal terlebih dahulu.";
}
?>
