<?php
include '../session.php';
include '../db.php'; // Include your database connection file

// Fetch students from the database
$sql = "SELECT id, student_id, firstname, middlename, lastname, course, contactnumber, email, password, accessCode FROM tblstudent";
$result = $conn->query($sql);

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
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $accessCode = $_POST['accessCode'];

    // Prepare and bind
    $stmt = $conn->prepare("UPDATE students SET student_id=?, firstname=?, middlename=?, lastname=?, course=?, contactnumber=?, email=?, password=?, accessCode=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $student_id, $firstname, $middlename, $lastname, $course, $contactnumber, $email, $password, $accessCode, $id);

    if ($stmt->execute()) {
        $message = "Student updated successfully.";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
if(strlen($_SESSION['id']==0)) {
    header('location:index.php');
     } else{
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Students</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Students</h2>
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
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="edit_students.php">
                            <td><input type="text" name="student_id" value="<?= htmlspecialchars($row['student_id']); ?>" required></td>
                            <td><input type="text" name="firstname" value="<?= htmlspecialchars($row['firstname']); ?>" required></td>
                            <td><input type="text" name="middlename" value="<?= htmlspecialchars($row['middlename']); ?>"></td>
                            <td><input type="text" name="lastname" value="<?= htmlspecialchars($row['lastname']); ?>" required></td>
                            <td><input type="text" name="course" value="<?= htmlspecialchars($row['course']); ?>" required></td>
                            <td><input type="text" name="contactnumber" value="<?= htmlspecialchars($row['contactnumber']); ?>"></td>
                            <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required></td>
                            <td><input type="password" name="password" placeholder="Enter new password" required></td>
                            <td><input type="text" name="accessCode" value="<?= htmlspecialchars($row['accessCode']); ?>" required></td>
                            <td>
                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                <td colspan="10" class="text-center">No students found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php}?>