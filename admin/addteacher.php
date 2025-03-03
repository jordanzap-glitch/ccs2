<?php
include '../session.php';
include '../db.php'; // Include your database connection file

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST['emp_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dept = $_POST['dept']; // Get department from form
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email']; // Get email from form
    $password = $_POST['password']; // Get password from form

    // Check if the Employee ID already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tblteacher WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Error: Employee ID already exists.";
        $toastClass = "#dc3545"; // Danger color
    } else {
        // Prepare and bind for inserting into tblteacher
        $stmt = $conn->prepare("INSERT INTO tblteacher (emp_id, firstname, middlename, lastname, dept, contactnumber, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $emp_id, $firstname, $middlename, $lastname, $dept, $contactnumber, $email, $password);

        if ($stmt->execute()) {
            // Insert emp_id, email, and password into tbluser
            $stmtUser  = $conn->prepare("INSERT INTO tbluser (user_id, email, password, user_type) VALUES (?, ?, ?, 'Admin')");
            $stmtUser ->bind_param("sss", $emp_id, $email, $password); // Assuming password is stored as plain text, consider hashing it

            if ($stmtUser ->execute()) {
                $message = "Admin/Teacher added successfully.";
                $toastClass = "#28a745"; // Success color
            } else {
                $message = "Error: " . $stmtUser ->error;
                $toastClass = "#dc3545"; // Danger color
            }

            $stmtUser ->close();
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
    <title>Add Teacher/Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../admin/static/addstud.css">
</head>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(to bottom, #00218b, #fffc58);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0;
    padding: 0;
    background-attachment: fixed; /* Ensure the background remains fixed */
    background-repeat: no-repeat;
    background-size: cover; /* Cover the entire viewport */
}
</style>
<body>
    <center>
        <div class="form-box">
            <center>
                <img src="../pic/srclogo.png" alt="School Logo" class="logo">
            </center>
            <div class="form-container">
                <center><h3>Add Teacher/Admin</h3></center>
                <form method="POST" action="addteacher.php">
                    <div class="mb-3">
                        <label for="emp_id">Employee ID:</label>
                        <input type="text" id="emp_id" name="emp_id" class="form-control" required>
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
                    <div class="mb-3">
                        <label for="dept">Department:</label>
                        <select id="dept" name="dept" class="form-control" required>
                            <option value="" disabled selected>Select Department</option>
                            <option value="BSIS">Bachelor of Science in Information System</option>
                            <option value="BEED">Bachelor of Elementary Education</option>
                            <option value="BSAIS">Bachelor of Science in Accounting Information Systems</option>
                            <option value="BSEntrep">Bachelor of Science in Entrepreneurship</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="contactnumber">Contact Number:</label>
                        <input type="text" id="contactnumber" name="contactnumber" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-custom"><i class="fas fa-user-plus"></i> Add Teacher/Admin</button>
                    <button type="button" class="btn btn-back" onclick="window.location.href='../admin/dashboard.php'">Back</button>
                </form>
                <?php if (isset($message)): ?>
                    <div class="toast-message" style="background-color: <?= $toastClass ?>;">
                        <?= $message; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </center>
</body>
</html>