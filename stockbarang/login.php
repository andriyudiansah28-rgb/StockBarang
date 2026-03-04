<?php
session_start();
include 'koneksi.php';

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $cek = mysqli_query($conn,"SELECT * FROM user 
        WHERE username='$username' AND password='$password'");

    if(mysqli_num_rows($cek)>0){
        $_SESSION['login']=true;
        header("Location: dashboard.php");
    }else{
        echo "<script>alert('Login gagal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card col-md-4 mx-auto">
        <div class="card-header bg-primary text-white">Login Admin</div>
        <div class="card-body">
            <form method="POST">
                <input type="text" name="username" class="form-control mb-2" placeholder="Username">
                <input type="password" name="password" class="form-control mb-2" placeholder="Password">
                <button name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>