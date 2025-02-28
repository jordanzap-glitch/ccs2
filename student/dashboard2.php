<?php
session_start();
error_reporting(0);
include('../db.php');

if(strlen($_SESSION['id']==0)) {
    header('location:index.php');
} else {
    // Count total capstone projects
    $capstoneCountQuery = "SELECT COUNT(*) AS total FROM tbl_capstone";
    $capstoneCountResult = $conn->query($capstoneCountQuery);
    $capstoneCount = $capstoneCountResult->fetch_assoc()['total'];
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
<!-- Sidebar and Content Section -->
<div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <center><img src="../pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
        <li><a href="dashboard2.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a class="active" href="changepass.php">
            <i class="fa fa-key" style="font-size: 24px;"></i> Change Password
        </a></li>
        <li><a class="active" href="View.php">
            <i class="fas fa-eye" style="font-size: 24px;"></i> View Studies
        </a></li>
        <li><a class="active" href="../dashboardstud.php">
            <i class="fas fa-arrow-circle-left me-2" style="font-size: 24px;"></i> Back Home
        </a></li>
    </ul>
</div>

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
    </script>
</body>
</html>
<?php } ?>
