<?php
include '../db.php'; // Include your database connection file

// Set the number of results per page
$results_per_page = 5;

// Find out the number of results stored in the database
$sql = "SELECT COUNT(*) AS total FROM tbl_capstone";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_results = $row['total'];

// Determine the total number of pages available
$number_of_pages = ceil($total_results / $results_per_page);

// Determine which page number visitor is currently on
if (!isset($_GET['page']) || $_GET['page'] < 1) {
    $current_page = 1;
} else {
    $current_page = (int)$_GET['page'];
}

// Calculate the starting limit for the results on the current page
$starting_limit = ($current_page - 1) * $results_per_page;

// Fetch capstone projects from the database with pagination
$sql = "SELECT id, title, abstract, a1_sname, a1_fname, a1_mname, a1_role, adviser, submit_date, poster_path, imrad_path, link_path FROM tbl_capstone LIMIT $starting_limit, $results_per_page";
$result = $conn->query($sql);

// Check if the form is submitted to update project information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Update project information
    $id = $_POST['id'];
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $a1_sname = $_POST['a1_sname'];
    $a1_fname = $_POST['a1_fname'];
    $a1_mname = $_POST['a1_mname'];
    $a1_role = $_POST['a1_role'];
    $adviser = $_POST['adviser'];
    $submit_date = $_POST['submit_date'];
    $poster_path = $_POST['poster_path'];
    $imrad_path = $_POST['imrad_path'];
    $link_path = $_POST['link_path'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE tbl_capstone SET title=?, abstract=?, a1_sname=?, a1_fname=?, a1_mname=?, a1_role=?, adviser=?, submit_date=?, poster_path=?, imrad_path=?, link_path=? WHERE id=?");
    $stmt->bind_param("sssssssssssi", $title, $abstract, $a1_sname, $a1_fname, $a1_mname, $a1_role, $adviser, $submit_date, $poster_path, $imrad_path, $link_path, $id);

    if ($stmt->execute()) {
        $message = "Capstone project updated successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Check if an edit request is made
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_row = null;

if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM tbl_capstone WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tbl_capstone WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "Capstone project deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}




$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch students from the database with pagination and search
$sql = "SELECT id, title, abstract, a1_sname, a1_fname, a1_mname, a1_role, adviser, submit_date, poster_path, imrad_path, link_path 
        FROM tbl_capstone 
        WHERE title LIKE ? OR adviser LIKE ? 
        LIMIT $starting_limit, $results_per_page";

$stmt = $conn->prepare($sql);
$search_term = "%" . $search_query . "%";
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Capstone Projects</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt- 5">
<h2>View Capstone Projects</h2>
<p><a class="btn btn-danger" href="dashboard.php">Back to Dashboard</a></p>
<form method="GET" action="">
    <div class="form-group">
        <label for="search">Search by Student ID or Last Name:</label>
        <input type="text" class="form-control" id="search" name="search" placeholder="Enter Student ID or Last Name">
    </div>
    <button type="submit" class="btn btn-primary">Search</button>
</form>
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= $message; ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Abstract</th>
                <th>Author Surname</th>
                <th>Author First Name</th>
                <th>Author Middle Name</th>
                <th>Author Role</th>
                <th>Adviser</th>
                <th>Submit Date</th>
                <th>Poster Path</th>
                <th>IMRaD Path</th>
                <th>Link Path</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['abstract']); ?></td>
                        <td><?= htmlspecialchars($row['a1_sname']); ?></td>
                        <td><?= htmlspecialchars($row['a1_fname']); ?></td>
                        <td><?= htmlspecialchars($row['a1_mname']); ?></td>
                        <td><?= htmlspecialchars($row['a1_role']); ?></td>
                        <td><?= htmlspecialchars($row['adviser']); ?></td>
                        <td><?= htmlspecialchars($row['submit_date']); ?></td>
                        <td><a href="<?= htmlspecialchars($row['poster_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?= htmlspecialchars($row['imrad_path']); ?>" target="_blank" download>View</a></td>
                        <td><a href="<?= htmlspecialchars($row['link_path']); ?>" target="_blank">View</a></td>
                        <td>
                            <a href="viewcapstone.php?edit=<?= $row['id']; ?>" class="btn btn-warning">Edit</a>
                        </td>
                        <td>
                            <a href="viewcapstone.php?delete=<?= $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13" class="text-center">No capstone projects found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($edit_row): ?>
        <h3>Edit Capstone Project</h3>
        <form method="POST" action="viewcapstone.php">
            <input type="hidden" name="id" value="<?= $edit_row['id']; ?>">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($edit_row['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="abstract">Abstract</label>
                <textarea class="form-control" name="abstract" required><?= htmlspecialchars($edit_row['abstract']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="a1_sname">Author Surname</label>
                <input type="text" class="form-control" name="a1_sname" value="<?= htmlspecialchars($edit_row['a1_sname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="a1_fname">Author First Name</label>
                <input type="text" class="form-control" name="a1_fname" value="<?= htmlspecialchars($edit_row['a1_fname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="a1_mname">Author Middle Name</label>
                <input type="text" class="form-control" name="a1_mname" value="<?= htmlspecialchars($edit_row['a1_mname']); ?>">
            </div>
            <div class="form-group">
                <label for="a1_role">Author Role</label>
                <input type="text" class="form-control" name="a1_role" value="<?= htmlspecialchars($edit_row['a1_role']); ?>" required>
            </div>
            <div class="form-group">
                <label for="adviser">Adviser</label>
                <input type="text" class="form-control" name="adviser" value="<?= htmlspecialchars($edit_row ['adviser']); ?>" required>
            </div>
            <div class="form-group">
                <label for="submit_date">Submit Date</label>
                <input type="date" class="form-control" name="submit_date" value="<?= htmlspecialchars($edit_row['submit_date']); ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Project</button>
        </form>
    <?php endif; ?>
<br>
    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="viewcapstone.php?page=<?= $current_page - 1; ?>" class="btn btn-secondary">Previous</a>
        <?php endif; ?>

        <?php for ($page = 1; $page <= $number_of_pages; $page++): ?>
            <?php if ($page == $current_page): ?>
                <strong><?= $page; ?></strong> 
            <?php else: ?>
                <a href="viewcapstone.php?page=<?= $page; ?>" class="btn btn-light"><?= $page; ?></a> 
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($current_page < $number_of_pages): ?>
            <a href="viewcapstone.php?page=<?= $current_page + 1; ?>" class="btn btn-secondary">Next</a>
        <?php endif; ?>
    </div>
</div>
</div>
</body>
</html>
