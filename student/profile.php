<?php
include '../session.php';
include '../db.php';

$message = '';

// Assuming the email is stored in the session
$email = $_SESSION['email']; // Make sure you have the email stored in the session

// Fetch user information from the database
$userId = $_SESSION['userId'];
$userQuery = "SELECT firstName, lastName, email, course, contactnumber, bio FROM tblstudent WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle case where user is not found
    echo "User  not found.";
    exit();
}

// Log user access to the profile page
logUser ($userId, "Accessed Profile Page");

// Handle form submission to update bio
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bio = $_POST['bio'];
    $updateQuery = "UPDATE tblstudent SET bio = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("si", $bio, $userId);
    if ($updateStmt->execute()) {
        // Log the user action for saving bio
        logUser ($userId, "Bio Saved");
        
        // Redirect to the same page to avoid resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $errorMessage = "Error updating bio.";
    }
}

// Function to log user actions
function logUser ($userId, $action) {
    global $conn; // Use the global connection variable
    $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
    $course = $_SESSION['course'];
    $user_type = $_SESSION['user_type'];
    

    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $userId, $fullname, $course, $user_type, $action);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-header h1 {
            margin: 0;
        }
        .profile-header p {
            color: #6c757d;
        }
    </style>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="profile-container">
    <div class="profile-header">
        <h1>User Profile</h1>
        <p>Welcome, <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName'], ENT_QUOTES, 'UTF-8'); ?>!</p>
    </div>
    
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success"><?php echo $successMessage; ?></div>
    <?php elseif (isset($errorMessage)): ?>
        <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="profile-info">
            <h4>Profile Information</h4>
            <ul class="list-unstyled">
                <li><strong>Name:</strong> <?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Email:</strong> <?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Course:</strong> <?php echo htmlspecialchars($user['course'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contactnumber'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Bio:</strong></li>
                <li>
                    <textarea name="bio" class="form-control" rows="3"><?php echo htmlspecialchars($user['bio'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                </li>
            </ul>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success">Save Bio</button>
            <a href="changepass.php" class="btn btn-warning">Change Password</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>