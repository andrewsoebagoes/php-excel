<?php

//import.php
session_start();
ob_start();

include 'vendor/autoload.php';

$connect = new PDO("mysql:host=127.0.0.1;dbname=php-excel", "root", "");

if ($_FILES["file"]["name"] != '') {
    $allowed_extension = array('xls', 'csv', 'xlsx');
    $file_array = explode(".", $_FILES["file"]["name"]);
    $file_extension = end($file_array);

    if (in_array($file_extension, $allowed_extension)) {
        $file_name = time() . '.' . $file_extension;
        $subfolder = 'file/'; // Nama subfolder yang diinginkan

        $targetDir = realpath($subfolder) . DIRECTORY_SEPARATOR . $file_name;

        // Pindahkan file dari temporary location ke direktori tujuan
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir)) {
            $file_type = \PhpOffice\PhpSpreadsheet\IOFactory::identify($targetDir);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($file_type);

            $spreadsheet = $reader->load($targetDir);

            $data = $spreadsheet->getActiveSheet()->toArray();

            for ($i = 1; $i < count($data); $i++) {
                $row = $data[$i];
                $insert_data = array(
                    'nama'    => $row[0],
                    'alamat'  => $row[1],
                    'umur'    => $row[2],
                    'harga'   => $row[3],
                    'tanggal' => date("Y-m-d", strtotime($row[4])),
                );

                $query = "
                    INSERT INTO `tb-data`
                    (nama, alamat, umur, harga, tanggal)
                    VALUES (:nama, :alamat, :umur, :harga, :tanggal)
                ";

                $stmt = $connect->prepare($query);

                foreach ($insert_data as $key => $value) {
                    $stmt->bindValue($key, $value);
                }

                $stmt->execute();
            }

            // unlink($targetDir);

            $message = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            Data berhasil di Import
                            
                            </div>';
            $_SESSION['message'] = $message;
            ob_end_flush();
            header('Location: index.php');
            exit;
        } else {
            $message = '<div class="alert alert-danger">Gagal Upload</div>';
            $_SESSION['message'] = $message;
            ob_end_flush();
            header('Location: index.php');
        }
    } else {
        $message = '<div class="alert alert-danger">Salah tipe file</div>';
        $_SESSION['message'] = $message;
        ob_end_flush();
        header('Location: index.php');
    }
} else {
    $message = '<div class="alert alert-danger">File wajib di masukan</div>';
    $_SESSION['message'] = $message;
    ob_end_flush();
    header('Location: index.php');
}

echo $message;

?>
