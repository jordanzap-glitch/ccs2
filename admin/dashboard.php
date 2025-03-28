<?php
include '../session.php';
include('../db.php');

if (strlen($_SESSION['userId']) == 0) {
 
} else {
    // Count total capstone projects
    $capstoneCountQuery = "SELECT COUNT(*) AS total FROM tbl_capstone";
    $capstoneCountResult = $conn->query($capstoneCountQuery);
    $capstoneCount = $capstoneCountResult->fetch_assoc()['total'];

    // Count total students
    $studentCountQuery = "SELECT COUNT(*) AS total FROM tblstudent";
    $studentCountResult = $conn->query($studentCountQuery);
    $studentCount = $studentCountResult->fetch_assoc()['total'];

    // Count total teachers
    $teacherCountQuery = "SELECT COUNT(*) AS total FROM tblteacher";
    $teacherCountResult = $conn->query($teacherCountQuery);
    $teacherCount = $teacherCountResult->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/sidebar2.php'; ?>

<div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstName'], ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($_SESSION['lastName'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
    <p>This is the Dashboard page where you can view your information and access other sections of the system.</p>

    <hr>

    <!-- Count Boxes -->
    <div class="container mt-4" id="row-count">
        <div class="row">
            <!-- Capstone Projects Count -->
            <div class="col-12 col-md-4 mb-3">
                <div class="card text-white bg-primary shadow rounded">
                    <div class="card-body text-center">
                        <h4 class="card-title"><i class="fas fa-book"></i> Total Capstone</h4>
                        <h2 class="card-text"><?php echo $capstoneCount; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Total Students Count -->
            <div class="col-12 col-md-4 mb-3">
                <div class="card text-white bg-success shadow rounded">
                    <div class="card-body text-center">
                        <h4 class="card-title"><i class="fas fa-users"></i> Total Students</h4>
                        <h2 class="card-text"><?php echo $studentCount; ?></h2>
                    </div>
                </div>
            </div>

            <!-- Total Teachers Count -->
            <div class="col-12 col-md-4 mb-3">
                <div class="card text-white bg-warning shadow rounded">
                    <div class="card-body text-center">
                        <h4 class="card-title"><i class="fas fa-chalkboard-teacher"></i> Total Teachers/Admin</h4>
                        <h2 class="card-text"><?php echo $teacherCount; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>
    <!-- Search Bar -->
    <div class="search-bar mb-4">
        <form method="GET" action="dashboard.php" class="row g-2">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by Title" 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    aria-label="Search">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </form>
    </div>

    <!-- Submitted Capstone Projects -->
    <h2>Submitted Capstone Projects</h2>
    <div class="capstone-list">
        <?php
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        $sql = "SELECT title, abstract AS description, submit_date AS submitted_at FROM tbl_capstone";
        if (!empty($searchTerm)) {
            $sql .= " WHERE title LIKE ?";
        }
        $sql .= " ORDER BY submit_date DESC";

        $stmt = $conn->prepare($sql);
        if (!empty($searchTerm)) {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt->bind_param("s", $searchTerm);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="capstone-item">';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<small>Student submitted: ' . (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Unknown User') . '</small>';
                echo '<br>';
                echo '<small>Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>';
                echo '</div><hr>';
            }
        } else {
            echo '<p>No capstone projects found matching your search.</p>';
        }

        $stmt->close();
        $conn->close();
        ?>
    </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/bnpm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");
        const rowCount = document.getElementById("row-count");
        const closeSidebar = document.getElementById("close-sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
            rowCount.classList.toggle("hidden");
        });

        closeSidebar.addEventListener("click", function () {
            sidebar.classList.remove("active");
            rowCount.classList.remove("hidden");
        });
    });
</script>
</body>
</html>
<?php } ?>