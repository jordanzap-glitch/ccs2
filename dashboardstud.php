<?php
session_start();
// Include database connection
include 'db.php';

try {

    if (!isset($conn)) {
        throw new Exception("Database connection failed: \$conn is undefined.");
    }

    // Fetch images from the database
    $result = $conn->query("SELECT * FROM tbl_capstone LIMIT 4");

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


if(strlen($_SESSION['id']==0)) {
    header('location:student/index.php');
     } else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College of Computer Studies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/css/index.css">
</head>
<body>
<!-- Navigator Section -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">
            <img src="pic/ccs-logo.png" alt="CCS Logo" style="width: 40px; height: auto;"> College of Computer Studies
        </a>
        <div class="d-flex align-items-center">
            <span style="color: white; margin-right: 15px;">Welcome, <?php echo $login_message; ?>!</span>
            <a href="student/dashboard2.php" class="btn btn-outline-light">Research Studies</a>
            <a href="index.php" class="btn btn-outline-light">Logout</a>
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
                <div class="p-3 border grid-item" onclick="">
                    <img src="<?php echo $row['poster_path']; ?>" alt="Uploaded Image" class="img-fluid">
                    <!-- <h6 class="mt-2">Record Management</h6> -->
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    </div> 

<div class="text-end mb-4">
            <a href="seemore1.php" class="btn btn-primary">See More...</a>
</div>
    
    
    <!-- Grid Section 2 -->
    <div class="row mb-3">
        <!-- Placeholder Items -->
        <?php for ($i = 5; $i <= 8; $i++): ?>
            <div class="col-md-3">
                <div class="p-3 border grid-item" onclick="window.location.href='item<?= $i ?>.html'">
                    <img src="https://via.placeholder.com/150" alt="Item <?= $i ?>" class="img-fluid">
                    <h6 class="mt-2">jj<?= $i ?></h6>
                </div>
            </div>
        <?php endfor; ?>
      
    </div>

  

    <div class="text-end">

        <a href="#" class="btn btn-primary">See More...</a>
    </div>

</div>




    <div id="fullscreenModal" class="fullscreen-modal">
        <span class="close" onclick="closeFullscreen()">&times;</span>
        <img id="fullscreenImage" src="">
    </di>

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
            modal.style.display = 'block';
            modalImage.src = src;
        }

        function closeFullscreen() {
            document.getElementById('fullscreenModal').style.display = 'none';
        }
    </script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>