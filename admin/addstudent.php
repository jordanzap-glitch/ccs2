<?php
session_start();
include '../db.php'; // Include your database connection file

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    
    // Check if the Student ID already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tblstudent WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Error: Student ID already exists.";
        $toastClass = "#dc3545"; // Danger color
    } else {
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
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../admin/static/addstud.css">
</head>
<body>
    <div class="form-box">
        <center>
            <img src="../pic/srclogo.png" alt="School Logo" class="logo">
        </center>
        <div class="form-container">
            <center><h3>Add Student</h3></center>
            <form method="POST" action="addstudent.php">
                <div class="mb-3">
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="middlename">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary btn-custom"><i class="fas fa-user-plus"></i> Add Student</button>
                <button type="button" class="btn btn-back" onclick="window.location.href='../admin/dashboard.php'">Back</button>
            </form>
            <?php if (isset($message)): ?>
                <div class="toast-message" style="background-color: <?= $toastClass ?>;">
                    <?= $message; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

