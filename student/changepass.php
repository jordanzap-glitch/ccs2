<?php
include '../session.php';
include '../db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email']; // Assuming the email is stored in session
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];

    // Check if the email exists
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
            // Update the password without hashing
            $stmt = $conn->prepare("UPDATE tblstudent SET password = ? WHERE email = ?");
            $stmt->bind_param("ss", $newPassword, $email);

            if ($stmt->execute()) {
                $message = "Password changed successfully.";
            } else {
                $message = "Error changing password.";
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
            <input type="email" name="email" required>
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