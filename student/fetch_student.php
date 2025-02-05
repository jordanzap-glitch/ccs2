<?php
// fetch_student.php
include '../db2.php'; // Include the database connection file

$student_id = $_GET['student_id'] ?? '';

if ($student_id) {
    $stmt = $pdo->prepare("SELECT * FROM tblstudent WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);

    if ($stmt->rowCount() > 0) {
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $student]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Student not found.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No student ID provided.']);
}
?>