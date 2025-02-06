<?php
// fetch_student.php
include '../db.php'; // Include the database connection file

// Get the student ID from the request
if (isset($_GET['student_id'])) {
    $student_id = $conn->real_escape_string($_GET['student_id']);
    
    // Query to fetch student names
    $sql = "SELECT firstname, middlename, lastname FROM tblstudent WHERE id = '$student_id'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Output the student names
        $row = $result->fetch_assoc();
        echo json_encode($row);
    } else {
        echo json_encode(['firstname' => '', 'middlename' => '', 'lastname' => '']); // No student found
    }
}

$conn->close();
?>