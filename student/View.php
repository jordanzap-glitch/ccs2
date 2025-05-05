<?php
include '../session.php';
include '../db.php';


// Log user access to the View Capstone Studies page
// Handle bookmark request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['capstoneId'])) {
        $studentId = $_SESSION['student_id'];
        $capstoneId = $data['capstoneId'];

        // Check if the capstone is already bookmarked
        $stmt = $conn->prepare("SELECT * FROM tblbookmark WHERE student_id = ? AND capstone_id = ?");
        $stmt->bind_param("is", $studentId, $capstoneId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Unbookmark the capstone
            $stmt = $conn->prepare("DELETE FROM tblbookmark WHERE student_id = ? AND capstone_id = ?");
            $stmt->bind_param("is", $studentId, $capstoneId);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['success' => true, 'message' => 'Unbookmarked successfully']);
        } else {
            // Bookmark the capstone
            $fullName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
            $status = 'marked';

            $stmt = $conn->prepare("INSERT INTO tblbookmark (student_id, full_name, capstone_id, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $studentId, $fullName, $capstoneId, $status);
            $stmt->execute();
            $stmt->close();
            echo json_encode(['success' => true, 'message' => 'Bookmarked successfully']);
        }
        exit;
    }
}

// Fetch citation information
if (isset($_GET['citationId'])) {
    $capstoneId = $_GET['citationId'];
    $studentId = $_SESSION['student_id'];

    // Check if the citation has already been counted for this student
    $checkCountStmt = $conn->prepare("SELECT * FROM tblcount WHERE student_id = ? AND capstone_id = ?");
    $checkCountStmt->bind_param("is", $studentId, $capstoneId);
    $checkCountStmt->execute();
    $checkCountResult = $checkCountStmt->get_result();

    if ($checkCountResult->num_rows === 0) {
        // Insert citation count into tblcount
        $stmt = $conn->prepare("INSERT INTO tblcount (student_id, capstone_id, count) VALUES (?, ?, 1)");
        $stmt->bind_param("is", $studentId, $capstoneId);
        $stmt->execute();
        $stmt->close();
    }
    $checkCountStmt->close();

    // Fetch citation data
    $stmt = $conn->prepare("SELECT a1_sname, a1_fname, a2_mname, a2_sname, a2_fname, a2_mname, a3_sname, a3_fname, a3_mname, submit_date, title FROM tbl_capstone WHERE id = ?");
    $stmt->bind_param("i", $capstoneId);
    $stmt->execute();
    $result = $stmt->get_result();
    $citationData = $result->fetch_assoc();
    $stmt->close();

    // Format citation in APA style
    $citation = formatCitation($citationData);
    echo json_encode(['citation' => $citation, 'title' => htmlspecialchars($citationData['title'])]);
    exit;
}

// Function to format citation in APA style
function formatCitation($data) {
    $authors = [];
    if (!empty($data['a1_fname']) && !empty($data['a1_sname'])) {
        $authors[] = $data['a1_sname'] . ', ' . strtoupper(substr($data['a1_fname'], 0, 1)) . '.';
    }
    if (!empty($data['a2_fname']) && !empty($data['a2_sname'])) {
        $authors[] = $data['a2_sname'] . ', ' . strtoupper(substr($data['a2_fname'], 0, 1)) . '.';
    }
    if (!empty($data['a3_fname']) && !empty($data['a3_sname'])) {
        $authors[] = $data['a3_sname'] . ', ' . strtoupper(substr($data['a3_fname'], 0, 1)) . '.';
    }

    // Use "&" if there are exactly two authors
    if (count($authors) === 2) {
        $authorString = implode(' & ', $authors);
    } elseif (count($authors) > 2) {
        $authorString = implode(', ', array_slice($authors, 0, 1)) . ' et al.';
    } else {
        $authorString = implode(', ', $authors);
    }

    $date = date('Y', strtotime($data['submit_date']));
    return "$authorString. ($date). " . htmlspecialchars($data['title']) . ". Retrieved from URL"; // Replace "URL" with actual value as needed
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
        body {
            margin: 70px; /* Adds margin to all sides (top, bottom, left, right) */
        }

        .capstone-item {
            margin-bottom: 20px; /* Adds margin below each capstone item */
            transition: transform 0.3s ease-in-out;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .capstone-title {
            cursor: pointer; /* Change cursor to pointer on hover */
        }

        .button-column {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-top: 10px; /* Adds space between buttons */
        }

        .search-bar {
            margin-bottom: 30px; /* Adds margin below the search bar */
        }

        .notification {
            color: green; /* Change color as needed */
            font-weight: bold;
        }

        .pdf-viewer {
            width: 100%;
            height: 500px; /* Set height for the PDF viewer */
        }
    </style>
</head>
<body>
<?php include '../includes/sidebar.php'; ?>
<div class="content">
    <h1>Capstone Studies</h1>
    <p>List of submitted capstone projects.</p>
    <div class="notification" id="bookmarkMessage"></div>
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
            $bookmarked = false;

            // Check if the capstone is bookmarked
            $bookmarkCheck = $conn->prepare("SELECT * FROM tblbookmark WHERE student_id = ? AND capstone_id = ?");
            $bookmarkCheck->bind_param("is", $_SESSION['student_id'], $capstoneId);
            $bookmarkCheck->execute();
            $bookmarkResult = $bookmarkCheck->get_result();
            if ($bookmarkResult->num_rows > 0) {
                $bookmarked = true;
            }
            $bookmarkCheck->close();

            echo '<div class="capstone-item" data-pdf="' . htmlspecialchars($row['imrad_path']) . '">
                    <div>
                        <h3 class="capstone-title" data-capstone-id="' . $capstoneId . '">' . htmlspecialchars($row['title']) . '</h3>
                        <p>' . htmlspecialchars($row['description']) . '</p>
                        <small>Submitted on: ' . htmlspecialchars($row['submitted_at']) . '</small>
                        <span class="citation-icon" data-capstone-id="' . $capstoneId . '" title="Get Citation">
                            <i class="fas fa-quote-right"></i>
                        </span>
                    </div>
                    <div class="button-column">
                        <button class="btn btn-custom bookmark-btn" data-capstone-id="' . $capstoneId . '">
                            <i class="fas fa-bookmark"></i> ' . ($bookmarked ? 'Unbookmark' : 'Bookmark') . '
                        </button>
                        <a href ="' . htmlspecialchars($row['imrad_path']) . '" download class="btn btn-custom mt-2">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                  </div> <hr>';
        }
    } else {
        echo '<p>No capstone projects found matching your search.</p>';
    }

    $conn->close();
    ?>
    </div>
</div>

<!-- Bootstrap Modal for Citation -->
<div class="modal fade" id="citationModal" tabindex="-1" aria-labelledby="citationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="citationModalLabel">Citation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div id="citationCopiedAlert" class="alert alert-success py-2 px-3" style="display:none;">
                    Citation copied to clipboard!
                </div>
                <p id="citationText"></p>
            
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for PDF Viewing -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="pdfViewer" class="pdf-viewer" src="" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const capstoneItems = document.querySelectorAll(".capstone-item");
        capstoneItems.forEach(item => {
            const titleElement = item.querySelector(".capstone-title");
            titleElement.addEventListener("click", function () {
                const pdfPath = item.getAttribute("data-pdf");
                if (titleElement.innerText.includes("OJT Monitoring")) {
                    const pdfModal = new bootstrap.Modal(document.getElementById('pdfModal'));
                    const pdfViewer = document.getElementById("pdfViewer");
                    pdfViewer.src = pdfPath + "#page=1"; // Show only the first page
                    pdfModal.show();
                }
            });
        });

        const bookmarkButtons = document.querySelectorAll(".bookmark-btn");
        bookmarkButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                event.stopPropagation(); // Prevent modal from opening
                const capstoneId = this.getAttribute("data-capstone-id");
                fetch("View.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ capstoneId: capstoneId })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById("bookmarkMessage").innerText = data.message; // Update message in the box
                    // Update the button text based on the bookmark status
                    if (data.message.includes('Bookmarked')) {
                        this.innerHTML = '<i class="fas fa-bookmark"></i> Unbookmark';
                    } else {
                        this.innerHTML = '<i class="fas fa-bookmark"></i> Bookmark';
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        const citationIcons = document.querySelectorAll(".citation-icon");
        citationIcons.forEach(icon => {
            icon.addEventListener("click", function (event) {
                event.stopPropagation(); // Prevent modal from opening
                const capstoneId = this.getAttribute("data-capstone-id");
                fetch(`View.php?citationId=${capstoneId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById("citationText").innerText = data.citation;

                    // Auto-copy citation to clipboard
                    navigator.clipboard.writeText(data.citation).then(() => {
                        // Show a temporary alert in the modal
                        const alertDiv = document.getElementById("citationCopiedAlert");
                        alertDiv.style.display = "block";
                        setTimeout(() => {
                            alertDiv.style.display = "none";
                        },5000);
                    }).catch(err => {
                        console.error('Error copying text: ', err);
                    });

                    // Show the modal
                    const citationModal = new bootstrap.Modal(document.getElementById('citationModal'));
                    citationModal.show();
                })
                .catch(error => console.error('Error:', error));
            });
        });

       

        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
        });
    });
</script>

</body>
</html>