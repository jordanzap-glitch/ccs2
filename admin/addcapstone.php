<?php
include '../session.php';
include '../db.php'; 
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $title = $conn->real_escape_string($_POST['title']);
    $abstract = $conn->real_escape_string($_POST['abstract']);
    $a1_sname = $conn->real_escape_string($_POST['a1_sname']);
    $a1_fname = $conn->real_escape_string($_POST['a1_fname']);
    $a1_mname = $conn->real_escape_string($_POST['a1_mname']);
    $a1_role = $conn->real_escape_string($_POST['a1_role']);
    
    $a2_sname = $conn->real_escape_string($_POST['a2_sname']);
    $a2_fname = $conn->real_escape_string($_POST['a2_fname']);
    $a2_mname = $conn->real_escape_string($_POST['a2_mname']);
    $a2_role = $conn->real_escape_string($_POST['a2_role']);
    
    $a3_sname = $conn->real_escape_string($_POST['a3_sname']);
    $a3_fname = $conn->real_escape_string($_POST['a3_fname']);
    $a3_mname = $conn->real_escape_string($_POST['a3_mname']);
    $a3_role = $conn->real_escape_string($_POST['a3_role']);
    
    $submit_date = $conn->real_escape_string($_POST['submit_date']);
    $adviser = $conn->real_escape_string($_POST['adviser']);
    $link_path = $conn->real_escape_string($_POST['link_path']);


    $poster_path = 'poster/' . basename($_FILES['poster_path']['name']);
    $imrad_path = 'imrad/' . basename($_FILES['imrad_path']['name']);


    $uploadOk = 1;

    if (!move_uploaded_file($_FILES['poster_path']['tmp_name'], $poster_path)) {
        echo "Sorry, there was an error uploading the poster file.<br>";
        $uploadOk = 0;
    }

    if (!move_uploaded_file($_FILES['imrad_path']['tmp_name'], $imrad_path)) {
        echo "Sorry, there was an error uploading the IMRAD file.<br>";
        $uploadOk = 0;
    }

    // Dito mag save ng data
    if ($uploadOk) {
        $sql = "INSERT INTO tbl_capstone (title, abstract, a1_sname, a1_fname, a1_mname, a1_role, 
                a2_sname, a2_fname, a2_mname, a2_role, 
                a3_sname, a3_fname, a3_mname, a3_role, 
                submit_date, adviser, poster_path, imrad_path, link_path) 
                VALUES ('$title', '$abstract', '$a1_sname', '$a1_fname', '$a1_mname','$a1_role', 
                '$a2_sname', '$a2_fname', '$a2_mname', '$a2_role', 
                '$a3_sname', '$a3_fname', '$a3_mname', '$a3_role', 
                '$submit_date', '$adviser', '$poster_path', '$imrad_path', '$link_path')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success'>New record created successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Capstone</title>
    <link rel="stylesheet" href="static/css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include '../includes/sidebar2.php'; ?>

<div class="container mt-4" style="max-width: 50%; margin: auto;">
    <h2>Submit Capstone Project</h2>

    <form action="addcapstone.php" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="mb -3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>

            <div class="mb-3">
                <label for="abstract" class="form-label">Abstract</label>
                <textarea class="form-control" name="abstract" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="a1_sname" class="form-label">Author 1 (Lastname)</label>
                <input type="text" class="form-control" name="a1_sname" required>
            </div>

            <div class="mb-3">
                <label for="a1_fname" class="form-label">Author 1 (Firstname)</label>
                <input type="text" class="form-control" name="a1_fname" required>
            </div>

            <div class="mb-3">
                <label for="a1_mname" class="form-label">Author 1 (Middlename)</label>
                <input type="text" class="form-control" name="a1_mname">
            </div>

            <div class="mb-3">
                <label for="a1_role" class="form-label">Author 1 Role</label>
                <select class="form-select" name="a1_role" required>
                    <option value="" disabled selected>Select Role</option>
                    <option value="Project Leader">Project Leader</option>
                    <option value="System Developer">System Developer</option>
                    <option value="System Analyst">System Analyst</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="a2_sname" class="form-label">Author 2 (Lastname)</label>
                <input type="text" class="form-control" name="a2_sname">
            </div>

            <div class="mb-3">
                <label for="a2_fname" class="form-label">Author 2 (Firstname)</label>
                <input type="text" class="form-control" name="a2_fname">
            </div>

            <div class="mb-3">
                <label for="a2_mname" class="form-label">Author 2 (Middlename)</label>
                <input type="text" class="form-control" name="a2_mname">
            </div>

            <div class="mb-3">
                <label for="a2_role" class="form-label">Author 2 Role</label>
                <select class="form-select" name="a2_role">
                    <option value="" disabled selected>Select Role</option>
                    <option value="Project Leader">Project Leader</option>
                    <option value="System Developer">System Developer</option>
                    <option value="System Analyst">System Analyst</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="a3_sname" class="form-label">Author 3 (Lastname)</label>
                <input type="text" class="form-control" name="a3_sname">
            </div>

            <div class="mb-3">
                <label for="a3_fname" class="form-label">Author 3 (Firstname)</label>
                <input type="text" class="form-control" name="a3_fname">
            </div>

            <div class="mb-3">
                <label for="a3_mname" class="form-label">Author 3 (Middlename)</label>
                <input type="text" class="form-control" name="a3_mname">
            </div>

            <div class="mb-3">
                <label for="a3_role" class="form-label">Author 3 Role</label>
                <select class="form-select" name="a3_role">
                    <option value="" disabled selected>Select Role</option>
                    <option value="Project Leader">Project Leader</option>
                    <option value="System Developer">System Developer</option>
                    <option value="System Analyst">System Analyst</option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="mb-3">
                <label for="submit_date" class="form-label">Date</label>
                <input type="date" class="form-control" name="submit_date" required>
            </div>

            <div class="mb-3">
                <label for="adviser" class="form-label">Adviser</label>
                <input type="text" class="form-control" name="adviser" required>
            </div class="mb-3">
                <label for="poster_path" class="form-label">Poster File (Image)</label>
                <input type="file" class="form-control" name="poster_path" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="imrad_path" class="form-label">IMRAD File (PDF)</label>
                <input type="file" class="form-control" name="imrad_path" accept="application/pdf">
            </div>

            <div class="mb-3">
                <label for="link_path" class="form-label">Link</label>
                <input type="text" class="form-control" name="link_path">
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");
        const rowCount = document.getElementById("row-count");
        const closeSidebar = document.getElementById("close-sidebar");

        menuToggle.addEventListener("click", function () {
            sidebar.classList.toggle("active");
            rowCount.classList.toggle("hidden");
        });

        closeSidebar.addEventListener("click", function () {
            sidebar.classList.remove("active");
            rowCount.classList.remove("hidden");
        });
    });
</script>
</body>
</html>