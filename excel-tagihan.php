<?php
// Mengimpor library PhpSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Membaca file Excel
$inputFile = 'juni 1.xls';
$spreadsheet = IOFactory::load($inputFile);

// Array untuk menyimpan semua data dari setiap sheet
$allData = [];

// Iterasi melalui setiap sheet
foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
    // Mengambil nilai dari kolom G4 sampai baris terakhir
    $nilaiGArray = $worksheet->rangeToArray('G4:G' . $worksheet->getHighestRow());
    $nilaiG = array_column($nilaiGArray, 0);

    // Mengambil nilai dari kolom H4 sampai baris terakhir
    $nilaiHArray = $worksheet->rangeToArray('H4:H' . $worksheet->getHighestRow());
    $nilaiH = array_column($nilaiHArray, 0);

    // Mengambil nilai dari kolom N4 sampai baris terakhir
    $nilaiNArray = $worksheet->rangeToArray('N4:N' . $worksheet->getHighestRow());
    $nilaiN = array_column($nilaiNArray, 0);

    // Menggabungkan nilai dari setiap sheet ke dalam array allData
    for ($i = 0; $i < count($nilaiG); $i++) {
        if (!empty($nilaiG[$i]) && !empty($nilaiN[$i])) {
            $allData[] = [
                'ID' => $nilaiH[$i],
                'Tipe' => 'email',
                'Suffix Tagihan' => 'tagihan-spp-juni-2023',
                'Nominal' => $nilaiN[$i],
                'Merchant' => 'SPP',
                'Deskripsi' => 'Tagihan SPP Juni 2023',
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
$sheetBaru->setCellValue('E1', 'Merchant');
$sheetBaru->setCellValue('F1', 'Deskripsi');

// Menuliskan data ke dalam file Excel baru
$baris = 1;
foreach ($allData as $data) {
    $baris++;
    $sheetBaru->setCellValue('A' . $baris, $data['ID']);
    $sheetBaru->setCellValue('B' . $baris, $data['Tipe']);
    $sheetBaru->setCellValue('C' . $baris, $data['Suffix Tagihan']);
    $sheetBaru->setCellValue('D' . $baris, $data['Nominal']);
    $sheetBaru->setCellValue('E' . $baris, $data['Merchant']);
    $sheetBaru->setCellValue('F' . $baris, $data['Deskripsi']);
}

// Menyimpan perubahan ke file Excel baru
$outputFile = '1.tagihan-juni-1.xls';
$writer = IOFactory::createWriter($spreadsheetBaru, 'Xls');
$writer->save($outputFile);

echo "File Excel Tagihan Juni 1 berhasil dibuat dengan data yang digabungkan dari setiap sheet.\n";
?>
