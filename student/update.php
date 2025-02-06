<?php
ob_start();
session_start();
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
    

    // Prepare and execute the SQL statement
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
            'password' => $password
        ]);
        echo "Student information updated successfully!";
        header("location:index.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
ob_end_flush();
?>