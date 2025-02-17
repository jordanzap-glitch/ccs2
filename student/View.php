<?php
session_start();
error_reporting(0);
include('../db.php');
if(strlen($_SESSION['id']==0)) {
    header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Capstone Studies</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .capstone-item {
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }
        .capstone-item:hover {
            transform: scale(1.01);
        }
        .modal-content {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <center><img src="../pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
        <li><a href="dashboard2.php" class="active">
            <i class="fas fa-home"></i>  Dashboard
        </a></li>
        <li><a class="active" href="changepass.php">
            <i class="fa fa-key" style="font-size: 24px;"></i> Change Password
        </a></li>
        <li><a class="active" href="View.php">
            <i class="fas fa-eye" style="font: size 24px;"></i> View Studies
        </a></li>
        <li><a class="active" href="../dashboardstud.php">
            <i class="fas fa-arrow-circle-left me-2"></i> Back
        </a></li>
    </ul>
</div>

<div class="content">
    <h1>Capstone Studies</h1>
    <p>List of submitted capstone projects.</p>
    <hr>

    <div class="search-bar mb-4">
        <form method="GET" action="View.php" class="row g-2">
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

    <div class="capstone-list">
    <?php
    include '../db.php';

    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

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

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="capstone-item" data-bs-toggle="modal" data-bs-target="#capstoneModal" 
                    data-title="' . htmlspecialchars($row['title']) . '" 
                    data-description="' . htmlspecialchars($row['description']) . '" 
                    data-date="' . htmlspecialchars($row['submitted_at']) . '">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<small>Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>';
            echo '</div><hr>';
        }
    } else {
        echo '<p>No capstone projects found matching your search.</p>';
    }

    $conn->close();
    ?>
</div>
</div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="capstoneModal" tabindex="-1" aria-labelledby="capstoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="capstoneModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="capstoneDescription"></p>
                <small id="capstoneDate"></small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".capstone-item").forEach(item => {
            item.addEventListener("click", function () {
                let title = this.getAttribute("data-title");
                let description = this.getAttribute("data-description");
                let date = this.getAttribute("data-date");

                document.getElementById("capstoneModalLabel").innerText = title;
                document.getElementById("capstoneDescription").innerText = description;
                document.getElementById("capstoneDate").innerText = "Submitted on: " + date;
            });
        });
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
