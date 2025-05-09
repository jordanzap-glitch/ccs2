<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College of Computer Studies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color:rgb(45, 73, 122);
        }
        .navbar .nav-link {
            color: #fff;
        }
        .navbar .nav-link.active {
            font-weight: bold;
            color: #ffc107;
        }
        .banner {
            background: linear-gradient(45deg, #6c757d,rgb(45, 73, 122));
            color: #fff;
            padding: 3rem;
            border-radius: 0.5rem;
        }
        footer {
            background-color:rgb(45, 73, 122);
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }

        /* Photo Gallery Styles */
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            padding: 2rem;
        }
        .gallery img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .gallery img:hover {
            transform: scale(1.05);
        }

        /* Fullscreen Image Modal */
        .fullscreen-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.9);
        }
        .fullscreen-modal img {
            margin: auto;
            display: block;
            width: 70%;
            max-width: 800px;
            border-radius: 10px;
        }
        .fullscreen-modal .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }
        .fullscreen-modal .close:hover,
        .fullscreen-modal .close:focus {
            color: #bbb;
            text-decoration: none;
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
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registerandlogin.php">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="registerandlogin.php">Register</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<center><h2>Ssite</h2></center>


<!-- Photo Gallery Section -->
<section class="gallery container">
    <img src="pic/" alt="Photo 1">
    <img src="pic/" alt="Photo 2">
    <img src="pic/" alt="Photo 3">
    <img src="pic/" alt="Photo 4">
    <img src="pic/" alt="Photo 5">
    <img src="pic/" alt="Photo 6">
    <img src="pic/" alt="Photo 7">
    <img src="pic/" alt="Photo 8">
    <img src="pic/" alt="Photo 9">
    <img src="pic/" alt="Photo 10">
    <img src="pic/" alt="Photo 11">
</section>

<!-- Fullscreen Image Modal -->
<div class="fullscreen-modal" id="imageModal">
    <span class="close" id="closeModal">&times;</span>
    <img id="modalImage" src="">
</div>

<!-- Footer Section -->
<footer>
    <div class="container">
        <p>&copy; 2024 College of Computer Studies. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Image Modal Script
    const images = document.querySelectorAll('.gallery img');
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.getElementById('closeModal');

    images.forEach(image => {
        image.addEventListener('click', () => {
            modal.style.display = 'block';
            modalImage.src = image.src;
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>

</body>
</html>
