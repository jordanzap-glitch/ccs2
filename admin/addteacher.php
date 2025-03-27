<?php
include '../session.php';
include '../db.php'; // Include your database connection file

$message = "";
$toastClass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_POST['emp_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dept = $_POST['dept']; // Get department from form
    $contactnumber = $_POST['contactnumber'];
    $email = $_POST['email']; // Get email from form
    $password = $_POST['password']; // Get password from form

    // Check if the Employee ID already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tblteacher WHERE emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $message = "Error: Employee ID already exists.";
        $toastClass = "#dc3545"; // Danger color
    } else {
        // Prepare and bind for inserting into tblteacher
        $stmt = $conn->prepare("INSERT INTO tblteacher (emp_id, firstname, middlename, lastname, dept, contactnumber, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $emp_id, $firstname, $middlename, $lastname, $dept, $contactnumber, $email, $password);

        if ($stmt->execute()) {
            // Insert emp_id, email, and password into tbluser
            $stmtUser  = $conn->prepare("INSERT INTO tbluser (user_id, email, password, user_type) VALUES (?, ?, ?, 'Admin')");
            $stmtUser ->bind_param("sss", $emp_id, $email, $password); // Assuming password is stored as plain text, consider hashing it

            if ($stmtUser ->execute()) {
                $message = "Admin/Teacher added successfully.";
                $toastClass = "#28a745"; // Success color
            } else {
                $message = "Error: " . $stmtUser ->error;
                $toastClass = "#dc3545"; // Danger color
            }

            $stmtUser ->close();
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
    <title>Add Teacher/Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
            background-color: #222;
        }

        :root {
            --primary: rgb(216, 213, 30);
            --secondary: rgb(236, 239, 56);
            --white: #fff;
            --gray: rgb(37, 37, 37);
        }

        /* Form Container */
        .form-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 30px;
            width: 650px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Logo */
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }

        /* Form Fields */
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
            font-size: 1.3rem;
            color: var(--white);
            padding: 10px 5px;
            background: transparent;
            transition: border-color 0.3s, color 0.3s;
            appearance: none;
            cursor: pointer;
        }

        /* Label Styling */
        .form__label {
            position: absolute;
            top: 10px;
            left: 5px;
            font-size: 1rem;
            color: var(--gray);
            transition: 0.3s ease-in-out;
        }

        /* On Focus or Filled */
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

        /* Dropdown Options */
        .form__group select option {
            background: #222; /* Dark background */
            color: white;
        }

        /* Submit Button */
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

        /* Back Button */
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
</head>
<body>
    <?php include '../includes/sidebar2.php'; ?>
    <div class="form-box">
        <center>
            <img src="../pic/srclogo.png" alt="School Logo" class="logo">
        </center>
        <h3 class="text-white text-center">Add Teacher/Admin</h3>
        <form method="POST" action="addteacher.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="form__group">
                        <input type="text" id="emp_id" name="emp_id" class="form__field" placeholder=" " required>
                        <label for="emp_id" class="form__label">Employee ID</label>
                    </div>
                    <div class="form__group">
                        <input type="text" id="firstname" name="firstname" class="form__field" placeholder=" " required>
                        <label for="firstname" class="form__label">First Name</label>
                    </div>
                    <div class="form__group">
                        <input type="text" id="middlename" name="middlename" class="form__field" placeholder=" ">
                        <label for="middlename" class="form__label">Middle Name</label>
                    </div>
                    <div class="form__group">
                        <input type="text" id="lastname" name="lastname" class="form__field" placeholder=" " required>
                        <label for="lastname" class="form__label">Last Name</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form__group">
                        <select id="dept" name="dept" class="form__field" required>
                            <option value="" disabled selected></option>
                            <option value="BSIS">BS Information System</option>
                            <option value="BEED">BE Education</option>
                            <option value="BSAIS">BS Accounting IS</option>
                            <option value="BSEntrep">BS Entrepreneurship</option>
                        </select>
                        <label for="dept" class="form__label">Select Department</label>
                    </div>
                    <div class="form__group">
                        <input type="text" id="contactnumber" name="contactnumber" class="form__field" placeholder=" " required>
                        <label for="contactnumber" class="form__label">Contact Number</label>
                    </div>
                    <div class="form__group">
                        <input type="email" id="email" name="email" class="form__field" placeholder=" " required>
                        <label for="email" class="form__label">Email</label>
                    </div>
                    <div class="form__group">
                        <input type="password" id="password" name="password" class="form__field" placeholder=" " required>
                        <label for="password" class="form__label">Password</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-custom"><i class="fas fa-user-plus"></i> Add Teacher/Admin</button>
        </form>
    </div>
</body>
</html>

