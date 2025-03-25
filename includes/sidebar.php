<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #2c3e50;
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    transition: all 0.3s ease;
}

.sidebar.active {
    left: -250px;
}

.sidebar .logo {
    border-radius: 50%;
    margin-bottom: 10px;
}

.sidebar h2 {
    color: #ecf0f1;
    text-align: center;
    margin-bottom: 20px;
}

.sidebar ul {
    list-style: none;
}

.sidebar ul li {
    padding: 15px 20px;
}

.sidebar ul li a {
    color: #ecf0f1;
    font-size: 18px;
    text-decoration: none;
    display: block;
}

.sidebar ul li a:hover, 
.sidebar ul li a.active {
    background-color: #2c3e50;
    border-radius: 5px;
}

/* Hamburger Menu */
.menu-toggle {
    position: absolute;
    top: 15px;
    left: 15px;
    font-size: 24px;
    color: #2c3e50;
    cursor: pointer;
    display: none;
}

.menu-toggle.active {
    color: #000000;
}

/* Content Styling */
.content {
    margin-left: 250px;
    padding: 15px;
    transition: all 0.3s ease;
}

.sidebar.active {
    left: -250px;
}

.content {
    transition: margin-left 0.3s ease;
}

.sidebar.active ~ .content {
    margin-left: 0;
}

/* Dropdown Menu */
.nav-item.dropdown {
    background: #2c3e50;
}

.dropdown-menu {
    background: #2c3e50 !important;
    border: none;
}

.dropdown-menu .dropdown-item {
    color: #ecf0f1 !important;
}

.dropdown-menu .dropdown-item:hover {
    background: #1f2c38 !important;
}

/* Hamburger Menu */
.menu-toggle {
    position: absolute;
    top: 15px;
    left: 15px;
    font-size: 24px;
    color: #3f4446;
    cursor: pointer;
    display: none;
}

.menu-toggle.active {
    color: #ecf0f1;
}

/* Main Content */
.content {
    margin-left: 250px;
    padding: 20px;
    transition: all 0.3s ease;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    color: white;
    cursor: pointer;
}

.hidden {
    display: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    .menu-toggle {
        display: block;
    }

    .sidebar {
        width: 250px;
        left: -250px;
    }

    .sidebar.active {
        left: 0;
    }

    .content {
        margin-left: 0;
    }
}   
    </style>
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
        <li><a href="dashboard2.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a class="active" href="profile.php" onclick="logProfileAccess()">
            <i class="fas fa-user" style="font-size: 24px;"></i> Profile
        </a></li>
        <li><a class="active" href="View.php">
            <i class="fas fa-eye" style="font-size: 24px;"></i> View Studies
        </a></li>
        <li><a class="active" href="../dashboardstud.php">
            <i class="fas fa-arrow-circle-left me-2" style="font-size: 24px;"></i> Back Home
        </a></li>
    </ul>
</div>
</body>
</html>