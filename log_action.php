<?php
include 'session.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user information from the session
    $user_id = $_SESSION['student_id'];
    $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
    $course = $_SESSION['course'];
    $user_type = $_SESSION['user_type'];

    // Determine the action type
    $action = $_POST['action'];
    $link = isset($_POST['link']) ? $_POST['link'] : null; // Get the link if provided

    // Prepare the action description and timestamp
   // Get the current timestamp
    if ($action === "viewed_image") {
        $action_description = "Viewed image";
    } elseif ($action === "watched_video") {
        // Fetch the link from the database
        if ($link) {
            $linkQuery = "SELECT link_path FROM tbl_capstone WHERE link_path = ?";
            $stmt = $conn->prepare($linkQuery);
            $stmt->bind_param("s", $link);
            $stmt->execute();
            $stmt->bind_result($fetched_link);
            $stmt->fetch();
            $stmt->close();

            if ($fetched_link) {
                $action_description = "Watched commercial video: " . $fetched_link; // Include the link in the log
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Link not found']);
                exit();
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Link is required for this action']);
            exit();
        }
    } elseif ($action === "logged_out") {
        $action_description = "User  logged out"; // Action description for logging out
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        exit();
    }

    // Log the user action
    $stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action) VALUES (?, ?, ?, ?, ? )");
    $stmt->bind_param("issss", $user_id, $fullname, $course, $user_type, $action_description);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>