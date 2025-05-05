<?php
error_reporting(E_ALL);
include '../session.php';
include '../db.php';

$firstname = $_SESSION['firstName'];
$lastname = $_SESSION['lastName'];
$userId = $_SESSION['userId'];


$userId = $_SESSION['userId'];
$userQuery = "SELECT firstName, lastName, email, course, contactnumber, bio FROM tblstudent WHERE id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();


$capstoneCountQuery = "SELECT COUNT(*) AS total FROM tbl_capstone";
$capstoneCountResult = $conn->query($capstoneCountQuery);
$capstoneCount = $capstoneCountResult->fetch_assoc()['total'];

// Fetch latest active announcement
$announcementQuery = "SELECT title, content FROM tbl_announcements WHERE status = 'Active' ORDER BY created_at DESC LIMIT 1";
$announcementResult = $conn->query($announcementQuery);
$announcement = $announcementResult->fetch_assoc();

// Function to log user actions
function logUser ($conn, $user_id, $fullname, $course, $user_type, $action) {
    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action, timestamp) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issss", $user_id, $fullname, $course, $user_type, $action);
    $stmt->execute();
    $stmt->close();
}
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
    <style>    
        .dashboard-header {
            background-color: #0A3D62;
            color: white;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            text-align: left;
            width: 85%;
            margin: 0 auto;
            margin-left: 190px;
        }
        .dashboard-text {
            max-width: 50%;
        }
        .highlight {
            color: rgb(255, 251, 16);
            font-weight: bold;
        }
        .dashboard-image {
            max-width: 170px;
            margin-left: 40px;
        }
        .announcement-container {
            width: 73%;
            margin: 0 auto;
            margin-left: 295px;
        }

        .announcement-card {
            border-left: 5px solid #0A3D62;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 20px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .announcement-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .announcement-card h5 {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }

        .announcement-card p {
            color: #555;
            margin-bottom: 0;
        }

        @media screen and (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                text-align: center;
            }
            .dashboard-text {
                max-width: 100%;
                text-align: center;
            }
            .dashboard-image {
                margin: 20px 0;
            }
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="container mt-4">
    <div class="dashboard-header">
        <div class="dashboard-text">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['firstName'], ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($_SESSION['lastName'], ENT_QUOTES, 'UTF-8'); ?>!</h1>
            <p>You've completed <span class="highlight">70% of your goal</span> this week! Keep up the good work and improve your results.</p>
        </div>
        <img src="../pic/srclogo.png" alt="Dashboard Illustration" class="dashboard-image">
        <img src="../pic/ccs-logo.png" alt="Dashboard Illustration" class="dashboard-image">
    </div>
</div>

<?php if ($announcement): ?>
    <div class="announcement-container mt-4">
        <div class="card announcement-card">
            <h4 class="text-primary"><i class="fas fa-bullhorn"></i> Announcement</h4>
            <h5>from admin</h5>
            <p><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });
    });

    function logProfileAccess() {
        <?php
        $user_id = $_SESSION['student_id'];
        $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
        $course = $_SESSION['course'];
        $user_type = $_SESSION['user_type'];
        $action = "Go to Dashboard";
        logUser ($conn, $user_id, $fullname, $course, $user_type, $action);
        ?>
    }
</script>

</body>
</html>
