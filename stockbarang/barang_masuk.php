<?php
include 'koneksi.php';

// Basic checks: ensure $conn exists
if (!isset($conn)) {
    die('Database connection not found. Check koneksi.php');
}

$error = '';
$success = '';

if (isset($_POST['simpan'])) {
    // Sanitize and validate input
    $id_barang = isset($_POST['id_barang']) ? mysqli_real_escape_string($conn, $_POST['id_barang']) : '';
    $tanggal = isset($_POST['tanggal']) ? mysqli_real_escape_string($conn, $_POST['tanggal']) : '';
    $jumlah = isset($_POST['jumlah']) ? (int) $_POST['jumlah'] : 0;

    if ($id_barang === '' || $tanggal === '' || $jumlah <= 0) {
        $error = 'Mohon isi semua field dengan benar (jumlah harus lebih dari 0).';
    } else {
        // Use transaction to ensure both insert and update succeed together
        if (function_exists('mysqli_begin_transaction')) {
            mysqli_begin_transaction($conn);
        } else {
            mysqli_autocommit($conn, false);
        }

        $ok = true;

        // Insert into barang_masuk using prepared statement (specify columns)
        $insertSql = "INSERT INTO barang_masuk (id_barang, tanggal, jumlah) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertSql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ssi', $id_barang, $tanggal, $jumlah);
            if (!mysqli_stmt_execute($stmt)) {
                $error = 'Gagal menyimpan barang masuk: ' . mysqli_stmt_error($stmt);
                $ok = false;
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Gagal menyiapkan query insert: ' . mysqli_error($conn);
            $ok = false;
        }

        // Update stok safely with prepared statement
        if ($ok) {
            $updateSql = "UPDATE barang SET stok = stok + ? WHERE id_barang = ?";
            $ustmt = mysqli_prepare($conn, $updateSql);
            if ($ustmt) {
                mysqli_stmt_bind_param($ustmt, 'is', $jumlah, $id_barang);
                if (!mysqli_stmt_execute($ustmt)) {
                    $error = 'Gagal memperbarui stok: ' . mysqli_stmt_error($ustmt);
                    $ok = false;
                }
                mysqli_stmt_close($ustmt);
            } else {
                $error = 'Gagal menyiapkan query update: ' . mysqli_error($conn);
                $ok = false;
            }
        }

        // Commit or rollback
        if ($ok) {
            if (function_exists('mysqli_commit')) mysqli_commit($conn);
            $success = 'Data barang masuk berhasil disimpan.';
            // keep user on page and show success; provide link to stock
        } else {
            if (function_exists('mysqli_rollback')) mysqli_rollback($conn);
            $error = $error ?: 'Terjadi kesalahan saat menyimpan data.';
        }

        // restore autocommit
        mysqli_autocommit($conn, true);
    }
}

$data = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Barang Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Barang Masuk</h2>

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

        <button name="simpan" class="btn btn-success">Simpan</button>
    </form>
</div>

</body>
</html>