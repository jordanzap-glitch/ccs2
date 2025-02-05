<?php
// validate.php
include '../db2.php'; // Include the database connection file

// Get the POST data
$studentId = $_POST['studentId'] ?? '';
$accessCode = $_POST['accessCode'] ?? '';

// Prepare and execute the SQL statement
$stmt = $pdo->prepare("SELECT * FROM tblstudent WHERE student_id = :studentId AND accessCode = :accessCode");
$stmt->execute(['studentId' => $studentId, 'accessCode' => $accessCode]);

// Check if a matching record was found
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid Student ID or Access Code.']);
}
?>