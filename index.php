<?php

session_start();
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

try {
    $connect = new PDO("mysql:host=127.0.0.1;dbname=php-excel", "root", "");
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query untuk mendapatkan data dari tabel "tb-data"
    $query = "SELECT * FROM `tb-data`";
    $stmt = $connect->prepare($query);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Belajar PHP Import Excel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <div class="container">

        <div class="card m-3">
            <div class="card-header">
                <h2>Belajar PHP Import Excel</h2>
            </div>
            <div class="card-body">

                <div class="col-md-6">

                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <label for="" class="mb-2">Masukan file excel</label>
                        <input type="file" class="form-control mb-2" name="file" id="file">
                        <input type="submit" value="Simpan" class="btn btn-sm btn-primary mt-2 mb-2">
                    </form>
                </div>
                <hr>
                
                <?php if (isset($message)) : ?>
                    <?php echo $message; ?>
                <?php endif; ?>
                <table id="table" class="table table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                          
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Umur</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach($data as $d){?>
                        <tr>
                            <td><?=$no?></td>
                            <td><?=$d['nama']?></td>
                            <td><?=$d['alamat']?></td>  
                            <td><?=$d['umur']?></td>
                            <td><?="Rp.". $d['harga']?></td>
                            <td><?=date("d-m-Y", strtotime($d['tanggal']))?></td>
                        
                        </tr>
                        <?php $no++?>
                        <?php }?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $('#table').DataTable();
      
    </script>
</body>

</html>