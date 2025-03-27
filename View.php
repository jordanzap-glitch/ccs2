<?php
include 'session.php';
include 'db.php';
error_reporting(0);

// Log user access to the View Capstone Studies page
logUser  ($_SESSION['student_id'], "Accessed View Capstone Studies Page");

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

// Function to check if a bookmark already exists
function bookmarkExists($studentId, $capstoneId) {
    global $conn; // Use the global connection variable
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tblbookmark WHERE student_id = ? AND capstone_id = ?");
    $stmt->bind_param("si", $studentId, $capstoneId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count > 0;
}

// Function to toggle bookmark status
function toggleBookmark($studentId, $fullName, $capstoneId) {
    global $conn; // Use the global connection variable
    if (bookmarkExists($studentId, $capstoneId)) {
        // If it exists, delete the bookmark
        $stmt = $conn->prepare("DELETE FROM tblbookmark WHERE student_id = ? AND capstone_id = ?");
        $stmt->bind_param("si", $studentId, $capstoneId);
        $stmt->execute();
        $stmt->close();
        return false; // Return false to indicate it was unmarked
    } else {
        // If it doesn't exist, insert a new bookmark
        $stmt = $conn->prepare("INSERT INTO tblbookmark (student_id, full_name, capstone_id, status) VALUES (?, ?, ?, 'Marked')");
        $stmt->bind_param("ssi", $studentId, $fullName, $capstoneId);
        $stmt->execute();
        $stmt->close();
        return true; // Return true to indicate it was marked
    }
}

// Handle bookmark request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if (isset($data['studentId']) && isset($data['fullName']) && isset($data['capstoneId'])) {
        $success = toggleBookmark($data['studentId'], $data['fullName'], $data['capstoneId']);
        echo json_encode(['success' => $success]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Capstone Studies</title>
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
        .modal-content {
            padding: 20px;
        }
        .bookmark {
            cursor: pointer;
            color: #007bff;
            font-size: 24px;
        }
        .bookmarked {
            color: #ffcc00; /* Change color for marked icon */
        }
    </style>
</head>
<body>

<div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">
    <center><img src="pic/ccs-logo.png" class="logo" alt="Logo" width="90px" height="90px"></center>
    <h2>CCS</h2>
    <ul>
        <li><a href="student/dashboard2.php" class="active">
            <i class="fas fa-home"></i>  Dashboard
        </a></li>
        <li><a class="active" href="student/profile.php">
            <i class="fas fa-user" style="font-size: 24px;"></i> Profile
        </a></li>
        <li><a class="active" href="View.php">
            <i class="fas fa-eye" style="font-size: 24px;"></i> View Studies
        </a></li>
        <li><a class="active" href="View.php">
            <i class="fas fa-book" style="font-size: 24px;"></i> Bookmarked
        </a></li>
        <li><a class="active" href="dashboardstud.php">
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
    $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT id, title, abstract AS description, submit_date AS submitted_at, imrad_path FROM tbl_capstone";
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
            $capstoneId = htmlspecialchars($row['id']);
            $isBookmarked = bookmarkExists($_SESSION['student_id'], $capstoneId) ? 'bookmarked' : '';
            echo '<div class="capstone-item" data-bs-toggle="modal" data-bs-target="#capstoneModal" 
                    data-title="' . htmlspecialchars($row['title']) . '" 
                    data-description="' . htmlspecialchars($row['description']) . '" 
                    data-date="' . htmlspecialchars($row['submitted_at']) . '" 
                    data-pdf="' . htmlspecialchars($row['imrad_path']) . '" 
                    data-id="' . $capstoneId . '">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '<small>Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>';
            echo '<span class="bookmark ' . $isBookmarked . '" data-capstone-id="' . $capstoneId . '" title="Bookmark this project">
                    <i class="fas fa-bookmark ' . ($isBookmarked ? 'bookmarked' : '') . '"></i>
                  </span>';
            echo '</div> <hr>';
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
                <br>
                <a id="capstonePdfLink" href="#" download class="btn btn-primary mt-3" style="display:none;">Download PDF</a>
                <span class="bookmark" id="bookmarkIcon" title="Bookmark this project">
                    <i class="fa-regular fa-bookmark"></i>
                </span>
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
                let pdfPath = this.getAttribute("data-pdf");
                let capstoneId = this.getAttribute("data-id");

                document.getElementById("capstoneModalLabel").innerText = title;
                document.getElementById("capstoneDescription").innerText = description;
                document.getElementById("capstoneDate").innerText = "Submitted on: " + date;
                let pdfLink = document.getElementById("capstonePdfLink");
                pdfLink.href = pdfPath;
                pdfLink.style.display = pdfPath ? 'block' : 'none';

                const bookmarkIcon = document.getElementById("bookmarkIcon");
                bookmarkIcon.setAttribute("data-capstone-id", capstoneId);
                bookmarkIcon.querySelector('i').classList.toggle('bookmarked', bookmarkExists('<?php echo $_SESSION['student_id']; ?>', capstoneId));
            });
        });

        const bookmarkIcon = document.getElementById("bookmarkIcon");
        bookmarkIcon.addEventListener("click", function () {
            const studentId = "<?php echo $_SESSION['student_id']; ?>";
            const fullName = "<?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName']; ?>";
            const capstoneId = this.getAttribute("data-capstone-id");
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ studentId, fullName, capstoneId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = this.querySelector('i');
                    if (icon.classList.contains('bookmarked')) {
                        icon.classList.remove('bookmarked'); // Change icon to unmarked
                        alert("This project has been unbookmarked!");
                    } else {
                        icon.classList.add('bookmarked'); // Change icon to marked
                       
                    }
                    location.reload(); // Refresh the page to show updated bookmarks
                } else {
                    location.reload();
                }
            });
        });

        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });
    });

    function bookmarkExists(studentId, capstoneId) {
        // This function should return true or false based on the bookmark status
        // You can implement an AJAX call to check the bookmark status if needed
        return false; // Placeholder return value
    }
</script>

</body>
</html>