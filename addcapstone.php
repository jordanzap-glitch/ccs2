<?php
include 'session.php';
include 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $title = $conn->real_escape_string($_POST['title']);
    $abstract = $conn->real_escape_string($_POST['abstract']);
    $a1_sname = $conn->real_escape_string($_POST['a1_sname']);
    $a1_fname = $conn->real_escape_string($_POST['a1_fname']);
    $a1_mname = $conn->real_escape_string($_POST['a1_mname']);
    $a1_role = $conn->real_escape_string($_POST['a1_role']);
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
        $sql = "INSERT INTO tbl_capstone (title, abstract, a1_sname, a1_fname, a1_mname, a1_role, submit_date, adviser, poster_path, imrad_path, link_path) 
                VALUES ('$title', '$abstract', '$a1_sname', '$a1_fname', '$a1_mname','$a1_role', '$submit_date', '$adviser', '$poster_path', '$imrad_path', '$link_path')";

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'includes/sidebar3.php'; ?>

<div class="container mt-4" style="max-width: 50%; margin: auto;">
    <h2>Submit Capstone Project</h2>

    <form action="addcapstone.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>

        <div class="mb-3">
            <label for="abstract" class="form-label">Abstract</label>
            <textarea class="form-control" name="abstract" rows="4" required></textarea>
        </div>

        <div class="mb-3">
            <label for="a1_sname" class="form-label">Lastname</label>
            <input type="text" class="form-control" name="a1_sname" required>
        </div>

        <div class="mb-3">
            <label for="a1_name" class="form-label">Firstname</label>
            <input type="text" class="form-control" name="a1_fname" required>
        </div>

        <div class="mb-3">
            <label for="a1_name" class="form-label">Middle</label>
            <input type="text" class="form-control" name="a1_mname">
        </div>

        <div class="mb-3">
            <label for="a1_role" class="form-label">Role</label>
            <input type="text" class="form-control" name="a1_role" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="submit_date" required>
        </div>

        <div class="mb-3">
            <label for="adviser" class="form-label">Adviser</label>
            <input type="text" class="form-control" name="adviser" required>
        </div>

        <div class="mb-3">
            <label for="poster_path" class="form-label">Poster File (Image)</label>
            <input type="file" class="form-control" name="poster_path" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="imrad_path" class="form-label">IMRAD File (PDF)</label>
            <input type="file" class="form-control" name="imrad_path" accept="application/pdf">
        </div>


        <div class="mb-3">
            <label for="link_path" class="form-label">link</label>
            <input type="text" class="form-control" name="link_path">
        </div>


        <button type="submit" class="btn btn-primary">Submit</button>

        <a type="button" class="btn btn-danger" href="admin/dashboard.php"> back</a>
    
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

