<?php
include '../session.php';
include '../db.php';

try {
    if (!isset($conn)) {
        throw new Exception("Database connection failed: \$conn is undefined.");
    }

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

$login_message = isset($_SESSION['login']) ? htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8') : 'Guest';

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
    <link rel="stylesheet" href="../static/css/index.css">
    <style>
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

<?php include '../includes/navbar.php'; ?>

<!-- Banner Section -->
<div id="bannerCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="banner text-center">
                <img src="pic/srclogo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <img src="pic/ccs-logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <img src="pic/ssite-removebg-preview.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <h1 class="display-4">Welcome to CCS</h1>
                <p class="lead">Your gateway to streamlined solutions</p>
            </div>
        </div>
        <div class="carousel-item">
            <div class="banner text-center">
                <img src="pic/srclogo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <img src="pic/ccs-logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <img src="pic/ssite-removebg-preview.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <h1 class="display-4">Innovate with Us</h1>
                <p class="lead">Explore endless possibilities in technology</p>
            </div>
        </div>
        <div class="carousel-item">
            <div class="banner text-center">
                <img src="pic/srclogo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <img src="pic/ccs-logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
                <img src="pic/ssite-removebg-preview.png" alt="Logo" class="img-fluid mb-3" style="max-width: 100px;">
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
        <a href="seemorestud.php" class="btn btn-primary">See More...</a>
    </div>
    
</div>

<div id="fullscreenModal" class="fullscreen-modal">
    <span class="close" onclick="closeFullscreen()">&times;</span>
    <img id="fullscreenImage" src="">
</div>

<?php include '../includes/footer.php'; ?>

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