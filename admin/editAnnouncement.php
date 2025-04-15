<?php
include '../db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: announcements.php");
    exit();
}

// Fetch existing announcement
$stmt = $conn->prepare("SELECT * FROM tbl_announcements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$announcement = $result->fetch_assoc();

if (!$announcement) {
    echo "Announcement not found.";
    exit();
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $imageName = $announcement['image'];

    // Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = 'uploads/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $stmt = $conn->prepare("UPDATE tbl_announcements SET title = ?, content = ?, image = ?, status = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $title, $content, $imageName, $status, $id);
    $stmt->execute();

    header("Location: announcement.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 40px auto;
        }
        .form-control, .form-select {
            font-size: 14px;
        }
        label {
            font-weight: 500;
        }
        img.preview {
            width: 100px;
            height: auto;
            margin-top: 5px;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<?php include '../includes/sidebar2.php'; ?>

<div class="form-container card shadow-sm p-4 bg-white">
    <h4 class="mb-3">✏️ Edit Announcement</h4>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" required class="form-control" value="<?= htmlspecialchars($announcement['title']) ?>">
        </div>

        <div class="mb-3">
            <label for="content">Content</label>
            <textarea name="content" rows="5" required class="form-control"><?= htmlspecialchars($announcement['content']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image">Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
            <?php if ($announcement['image']): ?>
                <img src="uploads/<?= $announcement['image'] ?>" class="preview" alt="Current Image">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-select" required>
                <option value="Active" <?= $announcement['status'] === 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Inactive" <?= $announcement['status'] === 'Inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="announcement.php" class="btn btn-secondary btn-sm">← Back</a>
            <button type="submit" class="btn btn-primary btn-sm">Update Announcement</button>
        </div>
    </form>
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
