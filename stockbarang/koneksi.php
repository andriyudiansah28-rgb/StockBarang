<?php
// Simple MySQLi connection for local development (XAMPP).
// Update the values below to match your environment.

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'db_stock_barang'; // change to your database name

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
    // In production, avoid echoing credentials; keep this for local debugging
    die('Connection failed: ' . mysqli_connect_error());
}

// Optional: set charset
mysqli_set_charset($conn, 'utf8');
