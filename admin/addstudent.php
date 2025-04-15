<?php
include '../session.php';
include '../db.php'; // Include your database connection file

$message = "";
$toastClass = "";

// Log user action when the page is opened
$userId = $_SESSION['emp_id']; // Assuming userId is stored in session
$firstName = $_SESSION['firstName']; 
$lastName = $_SESSION['lastName'];
$fullname = $firstName . ' ' . $lastName; // Correctly concatenate first name and last name
$user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
$action = "Opened Add Student Page";
$timestamp = date("Y-m-d H:i:s");

// Insert user log for page open
$log_stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
$log_stmt->bind_param("ssssss", $userId, $fullname, $course, $user_type, $action, $timestamp);
$log_stmt->execute();
$log_stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    
    // Check if the Student ID already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tblstudent WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Error: Student ID already exists.";
        $toastClass = "#dc3545"; // Danger color
    } else {
        // Generate a unique access code
        $accessCode = bin2hex(random_bytes(3)); // Generates a random 20-character hexadecimal string

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO tblstudent (student_id, firstname, middlename, lastname, accessCode) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $student_id, $firstname, $middlename, $lastname, $accessCode);

        if ($stmt->execute()) {
            // Log user action for adding a student
            $action = "Added Student: " . $firstname . " " . $lastname;
            $log_stmt = $conn->prepare("INSERT INTO user_logs (user_id, fullname, course, user_type, action, timestamp) VALUES (?, ?, ?, ?, ?, ?)");
            $log_stmt->bind_param("ssssss", $userId, $fullname, $course, $user_type, $action, $timestamp);
            $log_stmt->execute();
            $log_stmt->close();

            $message = "Student added successfully. Access Code: " . $accessCode;
            $toastClass = "#28a745"; // Success color
        } else {
            $message = "Error: " . $stmt->error;
            $toastClass = "#dc3545"; // Danger color
        }

        $stmt->close();
    }

    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../admin/static/addstud.css">
</head>
<style>
        :root {
            --primary: rgb(216, 213, 30);
            --secondary: rgb(236, 239, 56);
            --white: #fff;
            --gray: rgb(37, 37, 37);
        }

        body, html {
            height: 100%;
            margin: 0;
        }

        .container-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            background: #f0f0f0;
        }

        .form-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        .form__group {
            position: relative;
            margin-bottom: 15px;
            width: 100%;
        }

        .form__field, .form__group select {
            width: 100%;
            border: none;
            border-bottom: 2px solid var(--gray);
            outline: 0;
            font-size: 1.1rem;
            color: black;
            padding: 10px 5px;
            background: transparent;
            transition: border-color 0.3s, color 0.3s;
            appearance: none;
        }

        .form__label {
            position: absolute;
            top: 10px;
            left: 5px;
            font-size: 1rem;
            color: var(--gray);
            transition: 0.3s ease-in-out;
        }

        .form__field:focus, .form__group select:focus {
            border-bottom: 3px solid var(--primary);
        }

        .form__field:focus ~ .form__label,
        .form__field:not(:placeholder-shown) ~ .form__label,
        .form__group select:focus ~ .form__label,
        .form__group select:not([value=""]) ~ .form__label {
            top: -10px;
            font-size: 1.3rem;
            color: var(--primary);
        }

        .form__group select option {
            background: #222;
            color: white;
        }

        .btn-custom {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            border: none;
            border-radius: 5px;
            margin-top: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: linear-gradient(to right, var(--secondary), var(--primary));
            transform: scale(1.05);
        }

        .btn-back {
            width: 100%;
            padding: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            background: #666;
            color: white;
            border: none;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #444;
        }
    </style>
<body>
<?php include '../includes/sidebar2.php'; ?>
<div class="container-wrapper">   
    <div class="form-box">
        <h3>Add Student</h3>
        <form method="POST" action="addstudent.php">
            <div class="form__group">
                <input type="text" id="student_id" name="student_id" class="form__field" placeholder="" required>
                <label for="student_id" class="form__label">Student ID</label>
            </div>
            <div class="form__group">
                <input type="text" id="firstname" name="firstname" class="form__field" placeholder="" required>
                <label for="firstname" class="form__label">First Name</label>
            </div>
            <div class="form__group">
                <input type="text" id="middlename" name="middlename" class="form__field" placeholder="">
                <label for="middlename" class="form__label">Middle Name</label>
            </div>
            <div class="form__group">
                <input type="text" id="lastname" name="lastname" class="form__field" placeholder="" required>
                <label for="lastname" class="form__label">Last Name</label>
            </div>
            <button type="submit" class="btn btn-custom"><i class="fas fa-user-plus"></i> Add Student</button>
        </form> 

        <?php if (isset($message)): ?>
            <div class="toast-message" style="background-color: <?= $toastClass ?>;">
                <?= $message; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
    <!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.getElementById("menu-toggle");  
        const closeSidebar = document.getElementById("close-sidebar"); 
        const sidebar = document.getElementById("sidebar");
        const formBox = document.querySelector(".form-box"); // Form box

        if (menuToggle) {
            menuToggle.addEventListener("click", function () {
                sidebar.classList.toggle("active");

                // Hide the form box when sidebar is active
                if (sidebar.classList.contains("active")) {
                    formBox.style.display = "none";
                } else {
                    formBox.style.display = "block";
                }
            });
        }

        if (closeSidebar) {
            closeSidebar.addEventListener("click", function () {
                sidebar.classList.remove("active");
                formBox.style.display = "block"; // Show the form box
            });
        }
    });
</script>

</body>
</html>


