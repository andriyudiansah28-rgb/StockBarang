<?php
include 'koneksi.php';
$data = mysqli_query($conn, "SELECT * FROM barang");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Data Stock Barang</h2>

    <a href="tambah_barang.php" class="btn btn-success mb-3">+ Tambah Barang</a>

    <table class="table table-bordered">
        <tr class="table-dark">
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Stok</th>
            <th>Satuan</th>
            <th>Aksi</th>
        </tr>

        <?php $no=1; while($row=mysqli_fetch_assoc($data)) { ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['kode_barang']; ?></td>
            <td><?= $row['nama_barang']; ?></td>
            <td><?= $row['stok']; ?></td>
            <td><?= $row['satuan']; ?></td>
            <td>
                <a href="hapus_barang.php?id=<?= $row['id_barang']; ?>" class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>