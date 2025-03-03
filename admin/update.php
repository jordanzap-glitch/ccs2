<?php
// update.php
ob_start();
include '../db2.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $emp_id = $_POST['emp_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dept = $_POST['dept'];
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare("UPDATE tblteacher SET firstname = :firstname, middlename = :middlename, lastname = :lastname, dept = :dept, contactnumber = :contactnumber, email = :email, password = :password WHERE emp_id = :emp_id");

    try {
        $stmt->execute([
            'emp_id' => $emp_id,
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'dept' => $dept,
            'contactnumber' => $contactnumber,
            'email' => $email,
            'password' => $password
        ]);
        echo "Student information updated successfully!";
        header("location:../login.php");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
ob_end_flush();
?>