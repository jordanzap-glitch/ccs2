<!DOCTYPE html>
<html>
<head>
<title>NAVBAR</title>
</head>
<body>

<!-- Navigator Section -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="#">
            <img src="pic/ccs-logo.png" alt="CCS Logo" style="width: 40px; height: auto;"> College of Computer Studies
        </a>
        <div class="d-flex align-items-center">
            <span style="color: white; margin-right: 15px;">Welcome, <?php echo htmlspecialchars($_SESSION['firstName'], ENT_QUOTES, 'UTF-8'); ?> <?php echo htmlspecialchars($_SESSION['lastName'], ENT_QUOTES, 'UTF-8'); ?>!</span>
            <a href="dashboard2.php" class="btn btn-outline-light">Research Studies</a>
            <a href="index.php" class="btn btn-outline-light" onclick="logLogout(); return false;">Logout</a>
        </div>
    </div>
</nav>

</body>
</html>
