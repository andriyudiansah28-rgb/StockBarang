<?php
// create_admin.php
// WARNING: This script creates an admin user with username 'admin' and password 'admin'.
// Delete this file after use to avoid leaving a security hole.

include 'koneksi.php';

if (!isset($conn)) {
    die('Database connection not available. Check koneksi.php');
}

$targetUser = 'admin';
$targetPassPlain = 'admin';
$targetPassHash = md5($targetPassPlain); // matches existing login which uses MD5

// Check if table `user` exists and has username/password columns
$check = mysqli_query($conn, "SHOW TABLES LIKE 'user'");
if (!$check || mysqli_num_rows($check) == 0) {
    die('Table `user` does not exist in the database. Create the table first or adjust this script.');
}

// Check if user already exists (don't assume column name)
$stmt = mysqli_prepare($conn, "SELECT 1 FROM user WHERE username = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, 's', $targetUser);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$exists = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

if ($exists) {
    echo "User '{$targetUser}' already exists.\n";
    echo "If you want to reset the password, run the SQL: UPDATE user SET password=MD5('{$targetPassPlain}') WHERE username='{$targetUser}';\n";
    exit;
}

// Insert new user
$ins = mysqli_prepare($conn, "INSERT INTO user (username, password) VALUES (?, ?)");
mysqli_stmt_bind_param($ins, 'ss', $targetUser, $targetPassHash);
if (mysqli_stmt_execute($ins)) {
    echo "Created user '{$targetUser}' with password '{$targetPassPlain}'.\n";
    echo "Please delete this file (create_admin.php) after you confirm you can login.\n";
} else {
    echo "Failed to create user: " . mysqli_error($conn) . "\n";
}
mysqli_stmt_close($ins);

?>
