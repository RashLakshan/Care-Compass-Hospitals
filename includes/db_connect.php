<?php
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'care_compass_db';

// Try standard port 3306 first; fallback to WAMP MariaDB port 3307
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $username, $password, $database, 3306);
if (!$conn) {
    $conn = @mysqli_connect($host, $username, $password, $database, 3307);
}

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

