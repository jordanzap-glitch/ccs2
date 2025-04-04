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
<style>
    /* Optional: You can ensure no margin collapse with the parent elements */
    body, html {
        margin: 40px;
        padding: 0;
        height: 40%;
    }

    .container {
        padding-left: 40px; /* Just in case there is some padding you want to reset */
        padding-right: 40px;
    }

    .content {
        background-color: white;
    }
    /* Adjusted container to center the content */
    .capstone-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 20px;
    }

    .capstone-item {
        background: linear-gradient(145deg, #ffffff, #f2f2f2); /* Soft gradient background */
        border-left: 6px solid rgb(255, 251, 0); /* Blue left border */
        width: 100%;
        max-width: 800px; /* Max width for uniformity */
        border-radius: 15px; /* Rounded corners */
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-bottom: 25px;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, background 0.3s ease-in-out;
    }

    .capstone-item:hover {
        transform: translateY(-8px); /* Slightly higher lift */
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
        background: linear-gradient(145deg, #ffffff, #e0e0e0); /* Slight color change on hover */
    }

    .capstone-item h3 {
        font-size: 1.75rem; /* Increased title size */
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .capstone-item p {
        font-size: 1.05rem;
        color: #555;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .capstone-item small {
        font-size: 0.95rem;
        color: #777;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #ddd;
    }

    .capstone-meta i {
        color:rgb(251, 255, 0); /* Blue color for icons */
        margin-right: 8px;
    }

    /* Adding smooth hover effect for each item */
    .capstone-item:hover {
        background: #f8f9fa; /* Subtle background shift on hover */
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    .card-body {
        transition: background-color 0.3s ease-in-out;
    }

    .card-body:hover {
        background-color: rgba(0, 0, 0, 0.05); /* Slight background color on hover */
    }

    /* Ensure responsive design for mobile */
    @media (max-width: 768px) {
        .capstone-item {
            width: 100%;
            margin-bottom: 15px;
        }
    }
</style>
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
            echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
            echo '<small><i class="bi bi-person-fill"></i> Student submitted: ' . (isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Unknown') . '</small>';
            echo '<small><i class="bi bi-calendar"></i> Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>';
            echo '</div>';
        }
    } else {
        echo '<p class="text-muted">No capstone projects found.</p>';
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