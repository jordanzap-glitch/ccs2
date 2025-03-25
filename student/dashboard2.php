<?php
include '../session.php';
error_reporting(0);
include('../db.php');

// Count total capstone projects
$capstoneCountQuery = "SELECT COUNT(*) AS total FROM tbl_capstone";
$capstoneCountResult = $conn->query($capstoneCountQuery);
$capstoneCount = $capstoneCountResult->fetch_assoc()['total'];

// Function to log user actions
function logUser ($conn, $user_id, $fullname, $course, $user_type, $action) {
    
    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action, timestamp) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $fullname, $course, $user_type, $action);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<style>    
    .dashboard-header {
        background-color: #0A3D62; /* Deep blue background */
        color: white;
        padding: 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        text-align: left;
        width: 85%;
        margin: 0 auto; /* Centers it horizontally */
        margin-left: 190px; /* Moves it away from the sidebar */
    }
    .dashboard-text {
        max-width: 50%;
    }
    .highlight {
        color:rgb(255, 251, 16); /* Red highlight */
        font-weight: bold;
    }
    .dashboard-image {
        max-width: 170px;
        margin-left: 40px; /* Adds space between text and image */
    }
     /* ✅ Responsive Design */
     @media screen and (max-width: 768px) {
        .dashboard-header {
            flex-direction: column; /* Stack elements on smaller screens */
            text-align: center; /* Center text */
        }
        .dashboard-text {
            max-width: 100%;
            text-align: center; /* Center-align text on small screens */
        }
        .dashboard-image {
            margin: 20px 0; /* Adds spacing */
        }
    }
</style>
<body>
<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4">
    <div class="dashboard-header">
        <div class="dashboard-text">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
            <p>You've completed <span class="highlight">70% of your goal</span> this week! Keep up the good work and improve your results.</p>
        </div>
        <img src="../pic/srclogo.png" alt="Dashboard Illustration" class="dashboard-image">
        <img src="../pic/ccs-logo.png" alt="Dashboard Illustration" class="dashboard-image">
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });
    });

    function logProfileAccess() {
        // Log the user action for accessing the profile
        <?php
        // Assuming you have user information in session
        $user_id = $_SESSION['student_id'];
        $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
        $course = $_SESSION['course']; // Assuming course is stored in session
        $user_type = $_SESSION['user_type']; // Assuming user type is stored in session
        $action = "Go to Dashboard"; // Action description
        logUser ($conn, $user_id, $fullname, $course, $user_type, $action);
        ?>
    }
</script>
</body>
</html>