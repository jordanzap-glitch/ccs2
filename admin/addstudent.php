<?php
include '../db.php'; // Include your database connection file

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    
    // Generate a unique access code
    $accessCode = bin2hex(random_bytes(3)); // Generates a random 20-character hexadecimal string

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO tblstudent (student_id, firstname, middlename, lastname, accessCode) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $student_id, $firstname, $middlename, $lastname, $accessCode);

    if ($stmt->execute()) {
        $message = "Student added successfully. Access Code: " . $accessCode;
        $toastClass = "#28a745"; // Success color
    } else {
        $message = "Error: " . $stmt->error;
        $toastClass = "#dc3545"; // Danger color
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- HTML Form for Adding Student -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Add Student</title>
</head>
<body>
    <form method="POST" action="addstudent.php">
        <input type="text" name="student_id" placeholder="Student ID" required>
        <input type="text" name="firstname" placeholder="First Name" required>
        <input type="text" name="middlename" placeholder="Middle Name">
        <input type="text" name="lastname" placeholder="Last Name" required>
        <button type="submit" class="btn btn-primary">Add Student</button>
    </form>
        <a class="btn btn-danger" href="dashboard.php">Back</a>
    <?php if ($message): ?>
        <div style="background-color: <?= $toastClass ?>; color: white; padding: 10px;">
            <?= $message; ?>
        </div>
    <?php endif; ?>
</body>
</html>