<?php
include '../db.php'; // Include your database connection file

// Fetch students from the database
$sql = "SELECT id, student_id, firstname, middlename, lastname, course, contactnumber, email, password, accessCode FROM tblstudent";
$result = $conn->query($sql);

// Check if the form is submitted to update student information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Update student information
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Hash the password
    $accessCode = $_POST['accessCode'];


    $stmt = $conn->prepare("SELECT email, password FROM tblstudent WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $current_data = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // If email is empty, use the current email
    if (empty($email)) {
        $email = $current_data['email'];
    }

    // If password is empty, use the current password
    if (!empty($_POST['password'])) {
        $password = $_POST['password']; // Hash the new password
    } else {
        $password = $current_data['password']; // Use the current password
    }

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE tblstudent SET student_id=?, firstname=?, middlename=?, lastname=?, course=?, contactnumber=?, email=?, password=?, accessCode=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $student_id, $firstname, $middlename, $lastname, $course, $contactnumber, $email, $password, $accessCode, $id);

    if ($stmt->execute()) {
        $message = "Student updated successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tblstudent WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "Student deleted successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Check if an edit request is made
$edit_id = isset($_GET['edit']) ? $_GET['edit'] : null;
$edit_row = null;

if ($edit_id) {
    $stmt = $conn->prepare("SELECT * FROM tblstudent WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_row = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h2>View Students</h2>
<p><a class="btn btn-danger" href="dashboard.php">Back to Dashboard</a></p>
   
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= $message; ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
    <thead>
        <tr>
            <th>Student ID</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Course</th>
            <th>Contact Number</th>
            <th>Email</th>
            <th>Password</th>
            <th>Access Code</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_id']); ?></td>
                    <td><?= htmlspecialchars($row['firstname']); ?></td>
                    <td><?= htmlspecialchars($row['middlename']); ?></td>
                    <td><?= htmlspecialchars($row['lastname']); ?></td>
                    <td><?= htmlspecialchars($row['course']); ?></td>
                    <td><?= htmlspecialchars($row['contactnumber']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['password']); ?></td>
                    <td><?= htmlspecialchars($row['accessCode']); ?></td>
                    <td>
                        <a href="viewstudent.php?edit=<?= $row['id']; ?>" class="fa-solid fa-user-pen"></a> 
                        
                    </td>
                    <td>
                    <a href="viewstudent.php?delete=<?= $row['id']; ?>" class="fa-solid fa-user-xmark" onclick="return confirm('Are you sure you want to delete this student?');"></a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">No students found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($edit_row): ?>
    <h3>Edit Student Information</h3>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?= htmlspecialchars($edit_row['id']); ?>">
            <div class="form-group">
                <label for="student_id">Student ID</label>
                <input type="text" class="form-control" id="student_id" name="student_id" value="<?= htmlspecialchars($edit_row['student_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?= htmlspecialchars($edit_row['firstname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="middlename">Middle Name</label>
                <input type="text" class="form-control" id="middlename" name="middlename" value="<?= htmlspecialchars($edit_row['middlename']); ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?= htmlspecialchars($edit_row['lastname']); ?>" required>
            </div>
            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" class="form-control" id="course" name="course" value="<?= htmlspecialchars($edit_row['course']); ?>" required>
            </div>
            <div class="form-group">
                <label for="contactnumber">Contact Number</label>
                <input type="text" class="form-control" id="contactnumber" name="contactnumber" value="<?= htmlspecialchars($edit_row['contactnumber']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($edit_row['email']); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Leave blank to keep current password">
            </div>
            <div class="form-group">
                <label for="accessCode">Access Code</label>
                <input type="text" class="form-control" id="accessCode" name="accessCode" value="<?= htmlspecialchars($edit_row['accessCode']); ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Student</button>
        </form>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>