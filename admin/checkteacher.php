<?php
// Assume a simple database connection (you would normally use prepared statements to prevent SQL injection)
include '../db.php';
// Create connection


// Check connection

// Get the student ID from the request
$empId = $_POST['empId'];

// Check if the student exists in the database
$sql = "SELECT * FROM tblteacher WHERE emp_id = '$empId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // If the student is found, return 'found'
    echo "found";
   
} else {
    // If the student is not found, return 'not found'
    echo "not found";
}



// Close connection
$conn->close();
?>
