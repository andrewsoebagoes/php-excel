<?php
// Mengimpor library PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Membaca file Excel
$inputFile = 'file/juni 2.xls';
$spreadsheet = IOFactory::load($inputFile);

// Array untuk menyimpan semua data dari setiap sheet
$allData = [];

// Iterasi melalui setiap sheet
foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
    // Mengambil nilai dari kolom H4
    $nilaiHArray = $worksheet->rangeToArray('H4:H' . $worksheet->getHighestRow());
    $nilaiH = array_column($nilaiHArray, 0);

    // Mengambil nilai dari kolom N4
    $nilaiNArray = $worksheet->rangeToArray('N4:N' . $worksheet->getHighestRow());
    $nilaiN = array_column($nilaiNArray, 0);
    
    // Mengambil nilai dari kolom O4
    $nilaiOArray = $worksheet->rangeToArray('O4:O' . $worksheet->getHighestRow());
    $nilaiO = array_column($nilaiOArray, 0);

    // Menggabungkan nilai dari setiap sheet ke dalam array allData
    for ($i = 0; $i < count($nilaiO); $i++) {
        if ($nilaiO[$i] === 'SUKSES') {
            $allData[] = [
                'ID' => $nilaiH[$i],
                'Tipe' => 'email',
                'Suffix Tagihan' => 'pembayaran-spp-juni-2023',
                'Nominal' => $nilaiN[$i],
                'Status'    => 'SUKSES',
                'Merchant' => 'SPP',
                'Deskripsi' => 'Pembayaran SPP Juni 2023',
            ];
        }
    }
}

// Menampilkan data yang diambil
foreach ($allData as $data) {
    echo "ID: " . $data['ID'] . "\n";
    echo "Tipe: " . $data['Tipe'] . "\n";
    echo "Suffix Tagihan: " . $data['Suffix Tagihan'] . "\n";
    echo "Nominal: " . $data['Nominal'] . "\n";
    echo "Status: " . $data['Status'] . "\n";
    echo "Merchant: " . $data['Merchant'] . "\n";
    echo "Deskripsi: " . $data['Deskripsi'] . "\n";
    echo "\n";
}

// Menyimpan data ke dalam file Excel baru
$spreadsheetBaru = new Spreadsheet();
$sheetBaru = $spreadsheetBaru->getActiveSheet();

// Menuliskan header
$sheetBaru->setCellValue('A1', 'ID');
$sheetBaru->setCellValue('B1', 'Tipe(code atau email)');
$sheetBaru->setCellValue('C1', 'Suffix Tagihan');
$sheetBaru->setCellValue('D1', 'Nominal');
$sheetBaru->setCellValue('E1', 'Status');
$sheetBaru->setCellValue('F1', 'Merchant');
$sheetBaru->setCellValue('G1', 'Deskripsi');

// Menuliskan data ke dalam file Excel baru
$baris = 1;
foreach ($allData as $data) {
    $baris++;
    $sheetBaru->setCellValue('A' . $baris, $data['ID']);
    $sheetBaru->setCellValue('B' . $baris, $data['Tipe']);
    $sheetBaru->setCellValue('C' . $baris, $data['Suffix Tagihan']);
    $sheetBaru->setCellValue('D' . $baris, $data['Nominal']);
    $sheetBaru->setCellValue('E' . $baris, $data['Status']);
    $sheetBaru->setCellValue('F' . $baris, $data['Merchant']);
    $sheetBaru->setCellValue('G' . $baris, $data['Deskripsi']);
}

// Menyimpan perubahan ke file Excel baru
$outputFile = 'transaksi-juni-2.xls';
$writer = IOFactory::createWriter($spreadsheetBaru, 'Xls');
$writer->save($outputFile);

echo "File Excel Juni 2 berhasil dibuat dengan data yang digabungkan dari setiap sheet.\n";
?>
