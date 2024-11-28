<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

include "koneksi.php";

if (isset($_POST['tanggal1']) && isset($_POST['tanggal2'])) {
    $tanggal1 = $_POST['tanggal1'];
    $tanggal2 = $_POST['tanggal2'];

    // Membuat objek Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Menambah header ke spreadsheet
    $sheet->setCellValue('A1', 'Rekapitulasi Data Pengunjung');
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true);

    // Header tabel
    $sheet->setCellValue('A2', 'No.')
          ->setCellValue('B2', 'Tanggal')
          ->setCellValue('C2', 'Nama Pengunjung')
          ->setCellValue('D2', 'Alamat')
          ->setCellValue('E2', 'Tujuan')
          ->setCellValue('F2', 'No. HP')
          ->setCellValue('G2', 'Foto');

    // Mendapatkan data pengunjung
    $tampil = mysqli_query($koneksi, "SELECT * FROM tamu WHERE tanggal BETWEEN '$tanggal1' AND '$tanggal2' ORDER BY id DESC");
    $rowIndex = 3;
    $no = 1;

    while ($data = mysqli_fetch_array($tampil)) {
        $sheet->setCellValue('A' . $rowIndex, $no++)
              ->setCellValue('B' . $rowIndex, $data['tanggal'])
              ->setCellValue('C' . $rowIndex, $data['nama'])
              ->setCellValue('D' . $rowIndex, $data['alamat'])
              ->setCellValue('E' . $rowIndex, $data['tujuan'])
              ->setCellValue('F' . $rowIndex, $data['nope']);

        // Menambahkan gambar jika ada
        if (!empty($data['foto'])) {
            $imagePath = 'uploads/' . $data['foto'];
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setPath($imagePath);
            $drawing->setWidth(50);  // Atur ukuran gambar
            $drawing->setHeight(50); // Atur ukuran gambar
            $drawing->setCoordinates('G' . $rowIndex);
            $drawing->setWorksheet($spreadsheet->getActiveSheet());
        }

        $rowIndex++;
    }

    // Menyimpan file Excel
    $writer = new Xls($spreadsheet);
    $fileName = 'Export_Excel_Data_Pengunjung.xls';

    // Mengirim header untuk download file Excel
    ob_clean();  // Bersihkan output buffer
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');


    $writer->save('php://output');
    exit();
} else {
    echo "Silakan pilih rentang tanggal terlebih dahulu.";
}
?>
