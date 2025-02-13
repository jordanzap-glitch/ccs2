<?php
session_start();
error_reporting(0);
include('../db.php');
if(strlen($_SESSION['id']==0)) {
    header('location:index.php');
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
        <li><a href="dashboard2.php" class="active"><i class="fas fa-home"></i>  Dashboard</a></li>
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
        <form method="GET" action="dashboard2.php" class="d-flex align-items-center">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Title" 
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                aria-label="Search">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <div class="capstone-list">
        <?php
        $sql = "SELECT title, abstract AS description, submit_date AS submitted_at FROM tbl_capstone ORDER BY submit_date DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="capstone-item" data-bs-toggle="modal" data-bs-target="#capstoneModal" data-title="' . htmlspecialchars($row['title']) . '" data-description="' . htmlspecialchars($row['description']) . '" data-date="' . htmlspecialchars($row['submitted_at']) . '">';
                echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
                echo '<p>' . htmlspecialchars($row['description']) . '</p>';
                echo '<small>Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>';
                echo '</div><hr>';
            }
        } else {
            echo '<p>No capstone studies found.</p>';
        }
        $conn->close();
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="capstoneModal" tabindex="-1" aria-labelledby="capstoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="capstoneModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="capstoneModalBody"></div>
            <div class="modal-footer">
                <small id="capstoneModalDate"></small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    document.addEventListener("DOMContentLoaded", function () {
        const capstoneItems = document.querySelectorAll(".capstone-item");
        capstoneItems.forEach(item => {
            item.addEventListener("click", function () {
                document.getElementById("capstoneModalLabel").innerText = this.getAttribute("data-title");
                document.getElementById("capstoneModalBody").innerText = this.getAttribute("data-description");
                document.getElementById("capstoneModalDate").innerText = "Submitted on: " + this.getAttribute("data-date");
            });
        });
    });
</script>

</body>
</html>
<?php } ?>
