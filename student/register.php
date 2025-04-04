<?php
ob_start();
session_start();
error_reporting(0);
include '../db2.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $student_id = $_POST['student_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $course = $_POST['course'];
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the student ID already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tblstudent WHERE student_id = :student_id");
    $stmt->execute(['student_id' => $student_id]);
    $studentIdExists = $stmt->fetchColumn();

    if (!$studentIdExists) {
        echo "Error: The Student ID does not exist.";
    } else {
        // Check if the email already exists for another student
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tblstudent WHERE email = :email AND student_id != :student_id");
        $stmt->execute(['email' => $email, 'student_id' => $student_id]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            echo "Error: The email address is already in use by another student.";
        } else {
            // Prepare and execute the SQL statement to update student information
            $stmt = $pdo->prepare("UPDATE tblstudent SET firstname = :firstname, middlename = :middlename, lastname = :lastname, course = :course, contactnumber = :contactnumber, email = :email, password = :password WHERE student_id = :student_id");

            try {
                $stmt->execute([
                    'student_id' => $student_id,
                    'firstname' => $firstname,
                    'middlename' => $middlename,
                    'lastname' => $lastname,
                    'course' => $course,
                    'contactnumber' => $contactnumber,
                    'email' => $email,
                    'password' => $password // Consider hashing the password before storing it
                ]);

                // Insert email and password into tbluser with student_id as user_id
                $stmtUser  = $pdo->prepare("INSERT INTO tbluser (user_id, email, password, user_type) VALUES (:user_id, :email, :password, 'Student')");
                $stmtUser ->execute([
                    'user_id' => $student_id, // Use student_id as user_id
                    'email' => $email,
                    'password' => $password // Consider hashing the password before storing it
                ]);

                echo "Student information updated successfully!";
                header("location:index.php");
                exit(); // Ensure no further code is executed after the redirect
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Information</title>
    <link rel="stylesheet" href="static/ccs/register2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function fetchStudentInfo() {
            const studentId = document.getElementById('student_id').value;
            if (studentId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `fetch_student.php?student_id=${studentId}`, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('firstname').value = response.data.firstname;
                            document.getElementById('middlename').value = response.data.middlename;
                            document.getElementById('lastname').value = response.data.lastname;
                            document.getElementById('course').value = response.data.course;
                            document.getElementById('contactnumber').value = response.data.contactnumber;
                            document.getElementById('email').value = response.data.email;
                            document.getElementById('password').value = response.data.password;
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send();
            }
        }

        function loadInput() {
            const mirroredInput = localStorage.getItem('mirroredInput');
            document.getElementById('student_id').value = mirroredInput ? mirroredInput : '';
            document.getElementById('showInfoButton').click();
        }
    </script>

    
</head>
<body onload="loadInput()">
    <form action="register.php" method="POST">
        <div class="form-box">
            <center>
                <img src="../pic/srclogo.png" alt="School Logo" class="logo">
            </center>

            <h2>Student Registration</h2>
            <div class="text-center">
                <i id="showInfoButton" class="fas fa-info-circle fa-2x text-primary" style=" display: none;" onclick="fetchStudentInfo()"></i>
            </div>

            <div class="form-container">

                <div class="form-group">
                <i class="fas fa-id-card mr-2"></i>
                    <label for="student_id">Student ID:</label>
                    <input type="text" id="student_id" name="student_id" readonly onblur="fetchStudentInfo()">
                </div>

                <div class="form-group">
                <i class="fas fa-user mr-2"></i>
                    <label for="firstname" >First Name:</label>
                    <input type="text" id="firstname" name="firstname" readonly>
                </div>

                <div class="form-group" >
                <i class="fas fa-user mr-2"></i>
                    <label for="middlename" >Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" placeholder="Optional" readonly>
                </div>

                <div class="form-group">
                <i class="fas fa-user mr-2"></i>
                    <label for="lastname" >Last Name:</label>
                    <input type="text" id="lastname" name="lastname" readonly>
                </div>

                <div class="form-group">
                <i class="fas fa-book mr-2"></i>
                    <label for="course" >Course:</label>
                    <select id="course" name="course" required>
                        <option value="">--Select Course--</option>
                        <option value="BSIS">Bachelor of Science in Information Systems</option>
                        <option value="BSAIS">Bachelor of Science in Accounting Information Systems</option>
                        <option value="BEED">Bachelor of Elementary Education</option>
                        <option value="BSET">Bachelor of Science in Entrepreneurship</option>
                    </select>
                </div>

                <div class="form-group">
                <i class="fas fa-phone mr-2"></i>
                    <label for="contactnumber" >Contact Number:</label>
                    <input type="text" id="contactnumber" name="contactnumber" required>
                </div>

                <div class="form-group">
                <i class="fas fa-envelope mr-2"></i>
                    <label for="email" >Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <i class="fas fa-lock mr-2"></i>
                    <label for="password">Password:</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-secondary" onclick="togglePassword()">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="center-btn">
                    <input type="submit" value="REGISTER">
                </div>

                <div class="text-center">
                    <a class="bg-gray-200 text-blue-600 px-4 py-2 rounded-lg flex items-center justify-center" href="../index.php">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Home Page
                    </a>
                </div>
            </div>
        </div>
    </form>  

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.querySelector(".input-group-append i");
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
