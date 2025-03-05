<?php
include '../session.php';
include('../db.php');

// Initialize variables for the search term and date filter
$searchTerm = '';
$filterDate = '';

// Check if the form has been submitted
if (isset($_POST['search'])) {
    $searchTerm = mysqli_real_escape_string($conn, $_POST['fullname']);
}

// Check if the date filter has been submitted
if (isset($_POST['filter'])) {
    $filterDate = mysqli_real_escape_string($conn, $_POST['filter_date']);
}

// Fetch user logs from the database with optional search and date filtering
$query = "SELECT id, user_id, fullname, course, user_type, action, timestamp FROM user_logs WHERE 1=1"; // 1=1 for easier appending of conditions

if (!empty($searchTerm)) {
    $query .= " AND fullname LIKE '%$searchTerm%'";
}

if (!empty($filterDate)) {
    // Use DATE() to filter by the date part only
    $query .= " AND DATE(timestamp) = DATE('$filterDate')"; // Filter by the selected date
}

$query .= " ORDER BY timestamp ASC"; // Default sorting by timestamp
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Logs</title>
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
            padding: 20px;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        /* Align form elements */
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .form-group label {
            margin-right: 10px;
        }
        .form-group input[type="text"],
        .form-group input[type="date"] {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- Sidebar and Content Section -->
<div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <button class="close-btn" id="close-sidebar">&times;</button>
    <center><img src="../pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
        <li>
            <a href="dashboard.php" class="active text-white">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <br>

        <li>
            <a href="addteacher.php" class="text-white">
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
                <li><a class="dropdown-item" href="addstudent.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
                <li><a class="dropdown-item" href="viewstudent.php"><i class="fas fa-users"></i> View Students</a></li>
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
                <li><a class="dropdown-item" href="viewcapstone.php"><i class="fas fa-eye"></i> View Capstone</a></li>
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
            <a href="../login.php" class="text-white">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>
</div>

<h1>User Logs</h1>

<!-- Search and Filter Form -->
<form method="POST" action="">
    <div class="form-group">
        <label for="fullname">Search by Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <input type="submit" name="search" value="Search">
    </div>
    
    <div class="form-group">
        <label for="filter_date">Filter by Date:</label>
        <input type="date" id="filter_date" name="filter_date" value="<?php echo htmlspecialchars($filterDate); ?>">
        <input type="submit" name="filter" value="Filter">
    </div>
</form>

<table>
    <tr>
        <th>User ID</th>
        <th>Full Name</th>
        <th>Course</th>
        <th>User Type</th>
        <th>Action</th>
        <th>Timestamp</th>
    </tr>
    <?php
    // Fetch and display each row of the result
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
        echo "<td>" . htmlspecialchars($row['course']) . "</td>";
        echo "<td>" . htmlspecialchars($row['user_type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['action']) . "</td>";
        echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
        echo "</tr>";
    }
    ?>
</table>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>