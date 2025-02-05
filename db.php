<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'ccs_db';
$port = 3306;
$conn = mysqli_connect($host, $user, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
