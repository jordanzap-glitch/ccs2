<?php
include '../session.php';
include('../db.php');

// Initialize variables for search, date filter, pagination
$searchTerm = '';
$filterDate = '';
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['fullname'])) {
        $searchTerm = mysqli_real_escape_string($conn, $_POST['fullname']);
    }
    if (!empty($_POST['filter_date'])) {
        $filterDate = mysqli_real_escape_string($conn, $_POST['filter_date']);
    }
    if (isset($_POST['clear'])) {
        $deleteQuery = "DELETE FROM user_logs";
        mysqli_query($conn, $deleteQuery) or die("Delete query failed: " . mysqli_error($conn));
        header("Location: logs.php"); // Redirect to the same page to refresh the data
        exit();
    }
}

// Fetch total records count
$countQuery = "SELECT COUNT(*) as total FROM user_logs WHERE 1=1";
if (!empty($searchTerm)) {
    $countQuery .= " AND fullname LIKE '%$searchTerm%'";
}
if (!empty($filterDate)) {
    $countQuery .= " AND DATE(timestamp) = DATE('$filterDate')";
}
$countResult = mysqli_query($conn, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch user logs with optional filters and pagination
$query = "SELECT id, user_id, fullname, course, user_type, action, timestamp FROM user_logs WHERE 1=1";
if (!empty($searchTerm)) {
    $query .= " AND fullname LIKE '%$searchTerm%'";
}
if (!empty($filterDate)) {
    $query .= " AND DATE(timestamp) = DATE('$filterDate')";
}
$query .= " ORDER BY timestamp ASC LIMIT $limit OFFSET $offset";
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
        @media (max-width: 768px) {
            .sidebar {
                left: -100%; /* Hide sidebar fully */
                width: 250px;
                position: fixed;
                transition: all 0.3s ease-in-out;
                z-index: 1000;
            }
            .sidebar.active {
                left: 0; /* Show sidebar when active */
            }
            .content {
                margin-left: 0; /* Remove margin to make content full width */
                width: 100%;
            }
        }
        .menu-toggle { position: absolute; top: 15px; left: 15px; font-size: 24px; cursor: pointer; display: none; }
        @media (max-width: 768px) {
            .menu-toggle { display: block; }
            .sidebar { left: -250px; }
            .sidebar.active { left: 0; }
            .content { margin-left: 0; }
        }
        /* Optional: You can ensure no margin collapse with the parent elements */
body, html {
    margin: 70px;
}
    </style>
</head>
<body>
<?php include '../includes/sidebar2.php'; ?>

<div class="container-fluid mt-4">
    <div class="row justify-content-end">
        <div class="col-lg-9 col-md-10 offset-lg-1">
            <h2 class="mb-3">User Logs</h2>
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
                            <button type="submit" name="clear" class="btn btn-danger w-100"><i class="fas fa-times"></i> Clear</button>
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
                <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages) : ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
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

    // Close sidebar when clicking outside
    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });
});
</script>
</body>
</html>
<?php mysqli_close($conn); ?>
