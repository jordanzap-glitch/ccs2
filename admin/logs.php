<?php
include '../session.php';
include('../db.php');

// Initialize variables for the search term and date filter
$searchTerm = '';
$filterDate = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['fullname'])) {
        $searchTerm = mysqli_real_escape_string($conn, $_POST['fullname']);
    }
    if (!empty($_POST['filter_date'])) {
        $filterDate = mysqli_real_escape_string($conn, $_POST['filter_date']);
    }
}

// Fetch user logs with optional filters
$query = "SELECT id, user_id, fullname, course, user_type, action, timestamp FROM user_logs WHERE 1=1";
if (!empty($searchTerm)) {
    $query .= " AND fullname LIKE '%$searchTerm%'";
}
if (!empty($filterDate)) {
    $query .= " AND DATE(timestamp) = DATE('$filterDate')";
}
$query .= " ORDER BY timestamp ASC";
$result = mysqli_query($conn, $query) or die("Query failed: " . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Logs</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .table-container { max-height: 400px; overflow-y: auto; border: 1px solid #ccc; width: 100%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; position: sticky; top: 0; }
        .sidebar { width: 250px; height: 100vh; background-color: #2c3e50; position: fixed; left: 0; transition: all 0.3s ease; }
        .sidebar.active { left: -250px; }
        .content { margin-left: 250px; padding: 20px; transition: all 0.3s ease; }
        .menu-toggle { position: absolute; top: 15px; left: 15px; font-size: 24px; cursor: pointer; display: none; }
        @media (max-width: 768px) {
            .menu-toggle { display: block; }
            .sidebar { left: -250px; }
            .sidebar.active { left: 0; }
            .content { margin-left: 0; }
        }
    </style>
</head>
<body>
<div class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></div>
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
        <li>
            <a href="logs.php" class="text-white">
            <i class="fa-solid fa-clock"></i> Logs
            </a>
        </li>
        <br>
        <li>
            <a href="../login.php" class="text-white">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </li>
    </ul>
</div>
<div class="container-fluid mt-4">
    <div class="row justify-content-end">
        <div class="col-lg-9 col-md-10 offset-lg-1">
            <h2 class="mb-3">📋 User Logs</h2>
            <div class="card p-3 shadow-sm">
                <form method="POST">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-5 col-md-6">
                            <label for="fullname" class="form-label fw-bold">🔍 Search by Name:</label>
                            <div class="input-group">
                                <input type="text" id="fullname" name="fullname" class="form-control" value="<?php echo htmlspecialchars($searchTerm); ?>" placeholder="Enter full name">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-5">
                            <label for="filter_date" class="form-label fw-bold">📅 Filter by Date:</label>
                            <div class="input-group">
                                <input type="date" id="filter_date" name="filter_date" class="form-control" value="<?php echo htmlspecialchars($filterDate); ?>">
                                <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i></button>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 text-md-end">
                            <a href="logs.php" class="btn btn-danger w-100"><i class="fas fa-times"></i> Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Course</th>
                            <th>User Type</th>
                            <th>Action</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row['user_id']) ?></td>
                                <td><?= htmlspecialchars($row['fullname']) ?></td>
                                <td><?= htmlspecialchars($row['course']) ?></td>
                                <td><?= htmlspecialchars($row['user_type']) ?></td>
                                <td><?= htmlspecialchars($row['action']) ?></td>
                                <td><?= htmlspecialchars($row['timestamp']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");
        const closeSidebar = document.getElementById("close-sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });

        closeSidebar.addEventListener("click", function () {
            sidebar.classList.remove("active");
        });
    });
</script>
</body>
</html>
<?php mysqli_close($conn); ?>
