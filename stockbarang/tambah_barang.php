<?php
include 'koneksi.php';

if(isset($_POST['simpan'])){
    mysqli_query($conn,"INSERT INTO barang VALUES(
        '',
        '$_POST[kode]',
        '$_POST[nama]',
        '$_POST[stok]',
        '$_POST[satuan]'
    )");

    header("Location: stock_barang.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Tambah Barang</h2>

    <form method="POST">
        <input type="text" name="kode" class="form-control mb-2" placeholder="Kode Barang" required>
        <input type="text" name="nama" class="form-control mb-2" placeholder="Nama Barang" required>
        <input type="number" name="stok" class="form-control mb-2" placeholder="Stok Awal" required>
        <input type="text" name="satuan" class="form-control mb-2" placeholder="Satuan" required>

        <button name="simpan" class="btn btn-primary">Simpan</button>
    </form>
</div>

</body>
</html>