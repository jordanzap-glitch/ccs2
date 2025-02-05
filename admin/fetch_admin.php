<?php
// fetch_student.php
include '../db2.php'; // Include the database connection file

$emp_id = $_GET['emp_id'] ?? '';

if ($emp_id) {
    $stmt = $pdo->prepare("SELECT * FROM tblteacher WHERE emp_id = :emp_id");
    $stmt->execute(['emp_id' => $emp_id]);

    if ($stmt->rowCount() > 0) {
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $employee]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Student not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No student ID provided.']);
}
?>