<?php
session_start();
error_reporting(0);
include('../db.php');

if(strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
        
        <li class="nav-item dropdown" >
            <a href="#" class="nav-link dropdown-toggle text-white" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                <i class="fas fa-user-graduate"></i> Student
            </a>
            <ul class="dropdown-menu" style="background: #343a40;">
                <li><a class="dropdown-item text-white" href="addstudent.php"><i class="fas fa-user-plus"></i> Add Student</a></li>
                <li><a class="dropdown-item text-white" href="viewstudent.php"><i class="fas fa-users"></i> View Students</a></li>
            </ul>
        </li>


        <li><a href="../addcapstone.php"><i class="fas fa-book"></i> Capstone Study</a></li>
        <li><a href="viewcapstone.php"><i class="fas fa-book-open"></i> View Capstone</a></li>
        <br><br><br><br>
        <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8'); ?>! </h1>
    <p>This is the Dashboard page where you can view your information and access other sections of the system. Enjoy an enhanced experience with our clean and professional design.</p>

    <hr>

    <div class="search-bar mb-4">
        <form method="GET" action="dashboard.php" class="d-flex align-items-center">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by Title" 
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                aria-label="Search">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Search
            </button>
        </form>
    </div>

    <h2>Submitted Capstone Projects</h2>
    <div class="capstone-list">
        <?php
        include '../db.php';

        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

        $sql = "SELECT title, abstract AS description, submit_date AS submitted_at FROM tbl_capstone";
        if ($searchTerm) {
            $sql .= " WHERE title LIKE ?";
        }
        $sql .= " ORDER BY submit_date DESC";

        $stmt = $conn->prepare($sql);
        if ($searchTerm) {
            $searchTerm = "%" . $searchTerm . "%";
            $stmt->bind_param("s", $searchTerm);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
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

        $conn->close();
        ?>
    </div>
</div>

<!-- Bootstrap JS (Gamit ang bundle para gumana ang dropdown) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle sidebar
    document.getElementById('menu-toggle').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>

</body>
</html>
<?php } ?>
