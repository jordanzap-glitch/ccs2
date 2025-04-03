<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar and Content Section -->
<div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <button class="close-btn" id="close-sidebar">&times;</button>
    <center><img src="pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
        <li>
            <a href="admin/dashboard.php" class="active text-white">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <br>

        <li>
            <a href="admin/addteacher.php" class="text-white">
                <i class="fas fa-user-shield"></i> Add Admin
            </a>
        </li>
        <br>

        <!-- Student Dropdown -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                <i class="fas fa-user-graduate"></i> Student
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="admin/addstudent.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
                <li><a class="dropdown-item" href="admin/viewstudent.php"><i class="fas fa-users"></i> View Students</a></li>
            </ul>
        </li>
        <br>

        <!-- Capstone Dropdown -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown">
                <i class="fas fa-book"></i> Capstone
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="../addcapstone.php"><i class="fas fa-plus"></i> Add Capstone</a></li>
                <li><a class="dropdown-item" href="admin/viewcapstone.php"><i class="fas fa-eye"></i> View Capstone</a></li>
            </ul>
        </li>
        <br>

        <!-- Logs Section with updated icon -->
        <li>
            <a href="logs.php" class="text-white">
            <i class="fa-solid fa-clock"></i> Logs
            </a>
        </li>
        <br>

        <!-- Logout -->
        <li>
            <a href="login.php" class="text-white">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>
</div>
</body>
</html>