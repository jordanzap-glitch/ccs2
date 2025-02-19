<?php
session_start();
include 'db.php';

try {

    $result = $conn->query("SELECT poster_path, link_path FROM tbl_capstone");


    if (!$result) {
        throw new Exception("Error fetching images: " . $conn->error);
    }

    $images_exist = ($result->num_rows > 0);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
function getYouTubeVideoId($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $query);
    return $query['v'] ?? null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Images - College of Computer Studies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: #343a40;
        }
        .navbar .nav-link {
            color: #fff;
        }
        .navbar .nav-link.active {
            font-weight: bold;
            color: #ffc107;
        }
        .banner {
            background: linear-gradient(45deg, #6c757d, #343a40);
            color: #fff;
            padding: 3rem;
            border-radius: 0.5rem;
        }
        .grid-item {
            cursor: pointer;
            padding: 2rem;
            color: #fff;
            border-radius: 0.5rem;
            transition: transform 0.3s, background-color 0.3s;
        }
        .grid-item:hover {
            transform: scale(1.1);
            background-color: #ffc107;
        }
        .ssite {
            background-color: #007bff;
        }
        .dns {
            background-color: #28a745;
        }
        .class-offers {
            background-color: #17a2b8;
        }
        footer {
            background-color: #343a40;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        .grid-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

<!-- Navigator Section -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">
            <img src="pic/ccs-logo.png" alt="CCS Logo" style="width: 40px; height: auto;"> College of Computer Studies
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="dashboardstud.php">Home</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content Section -->
<div class="container mt-5">
        <h1 class="mb-4 text-center">All Images</h1>
        
        <div class="row mb-3">

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error_message) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($images_exist) && !$images_exist): ?>
                <div class="alert alert-info" role="alert">
                    No images uploaded yet. Be the first to upload an image!
                </div>
            <?php endif; ?>

            <?php if (isset($images_exist) && $images_exist): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 border grid-item">
                            <img src="<?php echo $row['poster_path']; ?>" alt="Uploaded Image" class="img-fluid">
                            <?php
                    $video_id = getYouTubeVideoId($row['link_path']);
                    if ($video_id): 
                        $thumbnail_url = "https://img.youtube.com/vi/$video_id/0.jpg";
                    ?>
                        <a href="<?php echo $row['link_path']; ?>" target="_blank">
                            <img src="<?php echo $thumbnail_url; ?>" alt="YouTube Thumbnail" class="img-fluid mt-2">
                        </a>
                    <?php endif; ?>
                    <br><br>
                    <a href="<?php echo $row['link_path']; ?>" target="_blank" class="btn btn-primary">Watch Commercial Video</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>

        </div> 
    </div>

<!-- Footer Section -->
<footer class="bg-dark text-white text-center py-3">
    <div class="container">
        <p>&copy; 2024 College of Computer Studies. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
