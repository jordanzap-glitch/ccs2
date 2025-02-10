<?php
include '../db.php'; // Include your database connection file

// Fetch capstone projects from the database
$sql = "SELECT id, title, abstract, a1_sname, a1_fname, a1_mname, a1_role, adviser, submit_date, poster_path, imrad_path, link_path FROM tbl_capstone";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capstone Projects List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Capstone Projects</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Abstract</th>
                <th>Author Name</th>
                <th>Author First Name</th>
                <th>Author Middle Name</th>
                <th>Author Role</th>
                <th>Adviser</th>
                <th>Submit Date</th>
                <th>Poster</th>
                <th>IMRaD</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= htmlspecialchars($row['title']); ?></td>
                        <td><?= htmlspecialchars($row['abstract']); ?></td>
                        <td><?= htmlspecialchars($row['a1_name']); ?></td>
                        <td><?= htmlspecialchars($row['a1_fname']); ?></td>
                        <td><?= htmlspecialchars($row['a1_mname']); ?></td>
                        <td><?= htmlspecialchars($row['a1_role']); ?></td>
                        <td><?= htmlspecialchars($row['adviser']); ?></td>
                        <td><?= htmlspecialchars($row['submit_date']); ?></td>
                        <td><a href="<?= htmlspecialchars($row['poster_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?= htmlspecialchars($row['imrad_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?= htmlspecialchars($row['link_path']); ?>" target="_blank">View</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12" class="text-center">No projects found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
$conn->close(); // Close the database connection
?>