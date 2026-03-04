<?php
include 'koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirm  = isset($_POST['confirm']) ? $_POST['confirm'] : '';

    if ($username === '' || $password === '' || $confirm === '') {
        $error = 'Semua field wajib diisi.';
    } elseif ($password !== $confirm) {
        $error = 'Password dan konfirmasi tidak cocok.';
    } else {
        // check username uniqueness (don't assume the user table has an `id` column)
        $stmt = mysqli_prepare($conn, "SELECT 1 FROM user WHERE username = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = 'Username sudah dipakai. Pilih username lain.';
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);
            $hash = md5($password); // kept as MD5 for compatibility with existing login.php
            $ins = mysqli_prepare($conn, "INSERT INTO user (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($ins, 'ss', $username, $hash);
            if (mysqli_stmt_execute($ins)) {
                $success = 'Pendaftaran berhasil. Silakan <a href="login.php">login</a>.';
            } else {
                $error = 'Gagal menyimpan user: ' . mysqli_error($conn);
            }
            mysqli_stmt_close($ins);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card col-md-5 mx-auto">
        <div class="card-header bg-success text-white">Register</div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="username" class="form-control mb-2" placeholder="Username" required>
                <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
                <input type="password" name="confirm" class="form-control mb-2" placeholder="Konfirmasi Password" required>
                <button type="submit" class="btn btn-success w-100">Daftar</button>
            </form>

            <hr>
            <small>Sudah punya akun? <a href="login.php">Login di sini</a>.</small>
        </div>
    </div>
</div>

</body>
</html>
