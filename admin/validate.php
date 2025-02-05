<?php
// validate.php
include '../db2.php'; // Include the database connection file

// Get the POST data
$empId = $_POST['empId'] ?? ''; // Changed from studentId to empId
$accessCode = $_POST['accessCode'] ?? '';

// Prepare and execute the SQL statement
$stmt = $pdo->prepare("SELECT * FROM tblteacher WHERE emp_id = :empId AND accessCode = :accessCode"); // Changed table and column names
$stmt->execute(['empId' => $empId, 'accessCode' => $accessCode]);

// Check if a matching record was found
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Employee ID or Access Code.']); // Changed message
}
?>