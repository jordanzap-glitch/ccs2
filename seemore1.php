<?php
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
    <link rel="stylesheet" href="static/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         /* Navbar Styles */
         .navbar {
            background-color: rgb(28, 74, 153);
            transition: background-color 0.3s ease;
        }

        .navbar:hover {
            background-color: rgb(10, 45, 105);
        }

        .navbar-brand img {
            width: 40px;
            height: auto;
        }

        /* Carousel Section */
        .carousel-inner {
            border-radius: 10px;
            overflow: hidden;
        }

        .carousel-item {
            height: 100%;
        }

        .carousel-item img {
            width: 100%;
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }

        .carousel-item img:hover {
            transform: scale(1.1);
        }

        /* Grid Section */
        .grid-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            background-color: rgb(28, 74, 153);;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .grid-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .grid-item .grid-icon {
            font-size: 3rem;
            color: #007bff;
        }

        .grid-item h4 {
            margin-top: 10px;
        }

        /* Image Zoom Effects */
        .picture-box {
            position: relative;
            cursor: pointer;
            overflow: hidden;
            border-radius: 10px;
        }

        .picture-box img {
            width: 100%;
            transition: transform 0.3s ease-in-out;
            border-radius: 5px;
        }

        .picture-box img:hover {
            transform: scale(1.1);
        }

        /* Fullscreen Modal */
        .fullscreen-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
        }

        .fullscreen-modal img {
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
        }

        .fullscreen-modal .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 30px;
            cursor: pointer;
        }

        /* Footer */
        footer {
            background-color: rgb(28, 74, 153);;
            padding: 20px 0;
            text-align: center;
            margin-top: 50px;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
            color: white;
        }

        /* Modal Image Preview */
        .modal-content {
            border-radius: 15px;
        }

        .modal-body img {
            width: 100%;
            border-radius: 10px;
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
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="student/validate.html">Register as Student</a>
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
                        <img src="<?php echo $row['poster_path']; ?>" alt="Uploaded Image" class="img-fluid" onclick="openFullscreen('<?php echo $row['poster_path']; ?>')">
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

<!-- Fullscreen Modal -->
<div id="fullscreenModal" class="fullscreen-modal">
    <span class="close" onclick="closeFullscreen()">&times;</span>
    <img id="fullscreenImage" src="">
</div>

<!-- Footer Section -->
<footer>
    <div class="container">
        <p>&copy; 2024 College of Computer Studies. All Rights Reserved.</p>
    </div>
</footer>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openFullscreen(src) {
        const modal = document.getElementById('fullscreenModal');
        const modalImage = document.getElementById('fullscreenImage');
        modal.style.display = 'flex';
        modalImage.src = src;
    }

    function closeFullscreen() {
        document.getElementById('fullscreenModal').style.display = 'none';
    }
</script>
</body>
</html>