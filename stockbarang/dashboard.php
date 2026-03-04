<?php
session_start();
include 'koneksi.php';
if(!isset($_SESSION['login'])) header("Location: login.php");

$total_barang = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang"));
$total_masuk = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang_masuk"));
$total_keluar = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM barang_keluar"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Dashboard</h2>

    <div class="row mt-3">

        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4><?= $total_barang; ?></h4>
                    Total Barang
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h4><?= $total_masuk; ?></h4>
                    Barang Masuk
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h4><?= $total_keluar; ?></h4>
                    Barang Keluar
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>