<?php
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];

    // Handle image upload
    $imageName = '';
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $targetPath = 'uploads/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    $stmt = $conn->prepare("INSERT INTO tbl_announcements (title, content, image, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $content, $imageName, $status);
    $stmt->execute();

    header("Location: announcement.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Announcement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 800px;
            margin: 60px auto;
        }
        .form-control, .form-select {
            font-size: 14px;
        }
        label {
            font-weight: 500;
        }
    </style>
</head>
<body>
<?php include '../includes/sidebar2.php'; ?>

<div class="form-container card shadow-sm p-4 bg-white">
    <h4 class="mb-3">➕ Add Announcement</h4>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title">Title</label>
            <input type="text" name="title" required class="form-control">
        </div>

        <div class="mb-3">
            <label for="content">Content</label>
            <textarea name="content" rows="5" required class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="image">Image (optional)</label>
            <input type="file" name="image" accept="image/*" class="form-control">
        </div>

        <div class="mb-3">
            <label for="status">Status</label>
            <select name="status" class="form-select" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="announcement.php" class="btn btn-secondary btn-sm">← Back</a>
            <button type="submit" class="btn btn-primary btn-sm">Save Announcement</button>
        </div>
    </form>
</div>
</body>
</html>
