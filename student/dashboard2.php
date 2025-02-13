<?php
session_start();
error_reporting(0);
include('../db.php');
if(strlen($_SESSION['id']==0)) {
 header('location:index.php');
  } else{


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
<body>
<!-- Sidebar and Content Section -->
<div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <center><img src="../pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
        <li><a href="dashboard2.php" class="active"><i class="fas fa-home"></i>  Dashboard</a></li>
        
        <li><a class="active" href="View.php">
            <i class="fas fa-eye" style="font: size 24px;"></i> View Studies
        </a></li>
        
        <li><a class="active" href="../dashboardstud.php">
            <i class="fas fa-arrow-circle-left me-2" style="font: size 24px;"></i> Back
        </a></li>
        
    </ul>
</div>

<div class="content">
    <h1>Welcome,  <?php echo htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8'); ?>! </h1>
    <p>This is the Dashboard page where you can view your information and access other sections of the system. Enjoy an enhanced experience with our clean and professional design.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Get the sidebar and menu toggle elements
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');

    // Add a click event listener to toggle the sidebar's active class
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });
</script>


</body>
</html>
<?php } ?>