<?php
// db.php
$host = 'localhost'; // Change if necessary
$db = 'ccs_db'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
 