<?php
include '../db.php';

$search = $_GET['search'] ?? '';
$query = "SELECT * FROM tbl_announcements WHERE title LIKE ? ORDER BY id DESC";
$stmt = $conn->prepare($query);
$searchTerm = "%$search%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef1f5;
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            border: none;
        }
        .table th, .table td {
            vertical-align: middle;
            font-size: 14px;
        }
        img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        .header h4 {
            font-weight: bold;
            margin-bottom: 0;
        }
        .search-box input {
            max-width: 220px;
        }
        .btn-sm {
            font-size: 13px;
            padding: 5px 10px;
        }
        .badge {
            font-size: 12px;
        }
        .container-main {
            max-width: 1000px;
            margin-left: 400px;
            padding: 2rem;
        }
        @media (max-width: 768px) {
            .container-main {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
<?php include '../includes/sidebar2.php'; ?>

<div class="container-fluid container-main">
    <div class="card shadow-sm rounded">
        <div class="card-body">
            <div class="header mb-3">
                <h4>📢 Announcements</h4>
                <div class="d-flex flex-wrap gap-2">
                    <form method="GET" class="d-flex search-box">
                        <input class="form-control form-control-sm" type="search" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search...">
                    </form>
                    <a href="add_announcement.php" class="btn btn-success btn-sm">+ Add Announcement</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Content</th>
                            <th scope="col">Image</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars(mb_strimwidth($row['content'], 0, 100, '...')) ?></td>
                            <td>
                                <?php if ($row['image']): ?>
                                    <img src="uploads/<?= $row['image'] ?>" alt="Jpg">
                                <?php else: ?>
                                    <span class="text-muted small">No image</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $row['status'] === 'Active' ? 'success' : 'secondary' ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                            <td>
                                <a href="editAnnouncement.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">‹</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">›</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");
    const closeSidebar = document.getElementById("close-sidebar");

    menuToggle.addEventListener("click", function () {
        sidebar.classList.toggle("active");
    });

    closeSidebar.addEventListener("click", function () {
        sidebar.classList.remove("active");
    });

    // Close sidebar when clicking outside
    document.addEventListener("click", function (event) {
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove("active");
        }
    });
});
</script>
</body>
</html>
