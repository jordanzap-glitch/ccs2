<?php
include '../session.php'; // Include session management
include '../db.php'; // Include database connection
error_reporting(0);

// Log user access to the Bookmarked Capstone Studies page
logUser  ($_SESSION['student_id'], "Accessed Bookmarked Capstone Studies Page");

// Function to log user actions
function logUser  ($userId, $action) {
    global $conn; // Use the global connection variable
    $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
    $course = $_SESSION['course'];
    $user_type = $_SESSION['user_type'];
    $timestamp = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $fullname, $course, $user_type, $action, $timestamp);
    $stmt->execute();
    $stmt->close();
}

// Function to get bookmarked capstone projects
function getBookmarkedCapstones($studentId) {
    global $conn; // Use the global connection variable
    $stmt = $conn->prepare("SELECT cb.id, cb.title, cb.abstract AS description, cb.submit_date AS submitted_at, cb.imrad_path 
                             FROM tblbookmark b 
                             JOIN tbl_capstone cb ON b.capstone_id = cb.id 
                             WHERE b.student_id = ?");
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    return $stmt->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarked Capstone Studies</title>
    <link rel="stylesheet" href="static/css/dashboard.css">
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
    </style>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="content">
    <h1>Bookmarked Capstone Studies</h1>
    <p>List of your bookmarked capstone projects.</p>
    <hr>

    <div class="capstone-list">
    <?php 
    $studentId = $_SESSION['student_id'];
    $result = getBookmarkedCapstones($studentId);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="capstone-item">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<small>Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>';
            echo '<a href="' . htmlspecialchars($row['imrad_path']) . '" class="btn btn-primary mt-2" download>Download PDF</a>';
            echo '</div><hr>';
        }
    } else {
        echo '<p>No bookmarked capstone projects found.</p>';
    }

    $conn->close();
    ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>