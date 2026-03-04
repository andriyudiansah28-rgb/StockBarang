<?php
include 'koneksi.php';

if(isset($_POST['simpan']))$cekstok = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT stok FROM barang WHERE id_barang='$_POST[id_barang]'"));

if($cekstok['stok'] < $_POST['jumlah']){
    echo "<script>alert('Stok tidak cukup!');</script>";
}else{
    mysqli_query($conn,"INSERT INTO barang_keluar VALUES(
        '',
        '$_POST[id_barang]',
        '$_POST[tanggal]',
        '$_POST[jumlah]'
    )");

    mysqli_query($conn,"UPDATE barang 
        SET stok = stok - $_POST[jumlah]
        WHERE id_barang='$_POST[id_barang]'");

    header("Location: stock_barang.php");
}
$data = mysqli_query($conn,"SELECT * FROM barang");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barang Keluar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Barang Keluar</h2>

    <form method="POST">
        <select name="id_barang" class="form-control mb-2">
            <?php while($row=mysqli_fetch_assoc($data)) { ?>
            <option value="<?= $row['id_barang']; ?>">
                <?= $row['nama_barang']; ?>
            </option>
            <?php } ?>
        </select>

        <input type="date" name="tanggal" class="form-control mb-2" required>
        <input type="number" name="jumlah" class="form-control mb-2" placeholder="Jumlah" required>

        <button name="simpan" class="btn btn-danger">Simpan</button>
    </form>
</div>
</body>
</html>