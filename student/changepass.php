<?php
include '../session.php';
include '../db.php';

$message = '';

// Assuming the email is stored in the session
$email = $_SESSION['email']; // Make sure you have the email stored in the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Check if the email exists in tblstudent
    $stmt = $conn->prepare("SELECT password FROM tblstudent WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        $message = "Email not found.";
    } else {
        // Verify the current password
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        // Directly compare the current password with the stored password
        if ($currentPassword !== $storedPassword) {
            $message = "Current password is incorrect.";
        } else {
            // Update the password in tblstudent
            $stmt = $conn->prepare("UPDATE tblstudent SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $newPassword, $email);

            if ($stmt->execute()) {
                // Log the user action for changing password
                $user_id = $_SESSION['student_id'];
                $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
                $course = $_SESSION['course'];
                $user_type = $_SESSION['user_type'];
                $action = "Changed password"; // Action description

                // Log the user action
                $logStmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
                $timestamp = date('Y-m-d H:i:s');
                $logStmt->bind_param("isssss", $user_id, $fullname, $course, $user_type, $action, $timestamp);
                $logStmt->execute();
                $logStmt->close();

                // Update the password in tbluser
                $stmt = $conn->prepare("UPDATE tbluser SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $newPassword, $email);

                if ($stmt->execute()) {
                    $message = "Password changed successfully.";
                } else {
                    $message = "Error changing password in tbluser.";
                }
            } else {
                $message = "Error changing password in tblstudent.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="../static/css/change.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="form-box">
        <center>
            <img src="../pic/srclogo.png" alt="School Logo" class="logo">
        </center>
        <h2>Change Password</h2>
        <form method="POST" action="">
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" readonly required>
            <br>
            <br>
            <label for="current_password">Current Password:</label>
            <input type="password" name="current_password" required>
            <br>
            <br>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" required>
            <br>
            <input type="submit" value="Change Password">
        </form>
        <p><?php echo $message; ?></p>
        <p><a href="dashboard2.php">Back to Dashboard</a></p>
    </div>
</body>
</html>