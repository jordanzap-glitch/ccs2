<?php
include 'db.php';

try {
    // Fetch images from the database
    $result = $conn->query("SELECT poster_path, link_path FROM tbl_capstone LIMIT 4");

    // Check if query was successful
    if (!$result) {
        throw new Exception("Error fetching images: " . $conn->error);
    }

    // Check if any images exist
    $images_exist = ($result->num_rows > 0);
} catch (Exception $e) {
    $error_message = $e->getMessage();
}

// Function to extract video ID from YouTube link
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
    <title>College of Computer Studies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/css/index.css">
    <style>
        .container {
            max-width: 900px;
            margin: auto;
        }
        .picture-box {
            position: relative;
            cursor: pointer;
            overflow: hidden;
        }
        .picture-box img {
            width: 100%;
            transition: transform 0.3s ease-in-out;
        }
        .picture-box img:hover {
            transform: scale(1.1);
        }
        .modal img {
            width: 100%;
        }
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
        }
        .fullscreen-modal .close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: white;
            font-size: 30px;
            cursor: pointer;
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
                    <a class="nav-link active" href="#">Home</a>
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

<!-- Banner Section -->
<div id="bannerCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="banner text-center">
                <h1 class="display-4">Welcome to CCS</h1>
                <p class="lead">Your gateway to streamlined solutions</p>
            </div>
        </div>
        <div class="carousel-item">
            <div class="banner text-center">
                <h1 class="display-4">Innovate with Us</h1>
                <p class="lead">Explore endless possibilities in technology</p>
            </div>
        </div>
        <div class="carousel-item">
            <div class="banner text-center">
                <h1 class="display-4">Join the Community</h1>
                <p class="lead">Empowering the next generation of tech leaders</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Content Sections -->
<div class="container">

    <!-- First Row -->
    <div class="row text-center mt-5 mb-4">
        <div class="col-md-4">
            <div class="grid-item ssite" onclick="window.location.href='ssite.html'">
                <div class="grid-icon">📘</div>
                <h4>SSITE</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="grid-item dns" onclick="window.location.href='dns.html'">
                <div class="grid-icon">🌐</div>
                <h4>DiNs Circle</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="grid-item class-offers" onclick="window.location.href='class-offers.html'">
                <div class="grid-icon">📚</div>
                <h4>Class Officers</h4>
            </div>
        </div>
    </div>

    <!-- Grid Section 1 -->
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

<div class="text-end mb-4">
    <a href="seemore1.php" class="btn btn-primary">See More...</a>
</div>
<br>
    <center><h2>Manual for Student</h2></center>

    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-md-6 text-center">
                <h3>Step 1</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/1.png')">
                    <img src="pic/1.png" alt="Step 1" onclick="openFullscreen('pic/1.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 2</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/2.png')">
                    <img src="pic/2.png" alt="Step 2" onclick="openFullscreen('pic/2.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 3</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/3.png')">
                    <img src="pic/3.png" alt="Step 3" onclick="openFullscreen('pic/3.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 4</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/4.png')">
                    <img src="pic/4.png" alt="Step 4" onclick="openFullscreen('pic/4.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 5</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/5.png')">
                    <img src="pic/5.png" alt="Step 5" onclick="openFullscreen('pic/5.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 6</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/6.png')">
                    <img src="pic/6.png" alt="Step 6" onclick="openFullscreen('pic/6.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 7</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/7.png')">
                    <img src="pic/7.png" alt="Step 7" onclick="openFullscreen('pic/7.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 8</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/8.png')">
                    <img src="pic/8.png" alt="Step 8" onclick="openFullscreen('pic/8.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 9</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/9.png')">
                    <img src="pic/9.png" alt="Step 9" onclick="openFullscreen('pic/9.png')">
                </div>
            </div>
            <div class="col-md-6 text-center">
                <h3>Step 10</h3>
                <div class="picture-box" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="zoomImage('pic/10.png')">
                    <img src="pic/10.png" alt="Step 10" onclick="openFullscreen('pic/10.png')">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal for zoomed image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Zoomed Image">
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function zoomImage(src) {
            document.getElementById("modalImage").src = src;
        }
    </script>
    <br>
    <br>
    <br>

</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>