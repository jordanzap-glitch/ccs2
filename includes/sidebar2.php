<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    <!-- Hamburger Toggle -->
  <div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <center><img src="../pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
      <li><a href="dashboard.php" class="active text-white"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="addteacher.php" class="text-white"><i class="fas fa-user-shield"></i> Add Admin</a></li>

      <!-- Student Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
          <i class="fas fa-user-graduate"></i> Student
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="addstudent.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
          <li><a class="dropdown-item" href="viewstudent.php"><i class="fas fa-users"></i> View Students</a></li>
        </ul>
      </li>

      <!-- Capstone Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
          <i class="fas fa-book"></i> Capstone
        </a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="addcapstone.php"><i class="fas fa-plus"></i> Add Capstone</a></li>
          <li><a class="dropdown-item" href="viewcapstone.php"><i class="fas fa-eye"></i> View Capstone</a></li>
        </ul>
      </li>

      <!-- Logs -->
      <li><a href="logs.php" class="text-white"><i class="fa-solid fa-clock"></i> Logs</a></li>

      <!-- Logout -->
      <li><a href="../login.php" class="text-white"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </div>

  <!-- Bootstrap JS with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar Toggle Script -->
  <script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
      document.getElementById('sidebar').classList.toggle('active');
      this.classList.toggle('active');
    });
  </script>
</body>
</html>