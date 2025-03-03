<?php
include '../session.php';
include('../db.php');



if(isset($_POST['submit']))
{
    $emp_id = $_POST['emp_id'];
    $firstname = $_POST['firstnameInfo'];
    $middlename = $_POST['middlenameInfo'];
    $lastname = $_POST['lastnameInfo'];
    $dept = $_POST['deptInfo'];
    $contactnumber = $_POST['contactnumberInfo'];
    $email = $_POST['emailInfo'];
    $password = $_POST['passwordInfo'];
   
$sql=mysqli_query($conn,"Update tblteacher set firstname='$firstname', middlename='$middlename', lastname='$lastname', dept='$dept', contactnumber='$contactnumber', email='$email', password='$password' where emp_id='$emp_id'");
    if($sql)
    {
        header("location:index.php");

    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../static/css/register.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/stud.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Conditional Textbox Based on Database</title>
    
    <script>
    function checkTeacher() {
    // Get the student ID from the input
    var empId = document.getElementById("empId").value;

    // Create an AJAX request to send to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "checkteacher.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Send the student ID to the server
    xhr.send("empId=" + empId);

    // Handle the response from the server
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;

            // If student is found, activate the textbox
            if (response == "found") {
                
                //not needed
                document.getElementById("firstnameInfo").disabled = false;
                document.getElementById("middlenameInfo").disabled = false;
                document.getElementById("lastnameInfo").disabled = false;
                document.getElementById("emp_id").disabled = false;
                document.getElementById("deptInfo").disabled = false;
                document.getElementById("contactnumberInfo").disabled = false;
                document.getElementById("emailInfo").disabled = false;
                document.getElementById("passwordInfo").disabled = false;
    

                //form control
                document.getElementById("signup").style.display = "flex";
            } else {
                alert("Student not found!");

                //not needed
              

                //form control
                document.getElementById("signup").style.display = "none";
            }
        }
    };
}

    </script>


    <script>

function checkCode() {
    // Get the student ID from the input
    var accessCode = document.getElementById("accessCode").value;

    // Create an AJAX request to send to the server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "checkcode.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    // Send the student ID to the server
    xhr.send("accessCode=" + accessCode);

    // Handle the response from the server
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;

            // If student is found, activate the textbox
            if (response == "found") {
                
                //not needed
                document.getElementById("firstnameInfo").disabled = false;
                document.getElementById("middlenameInfo").disabled = false;
                document.getElementById("lastnameInfo").disabled = false;
                document.getElementById("emp_id").disabled = false;
                document.getElementById("deptInfo").disabled = false;
                document.getElementById("contactnumberInfo").disabled = false;
                document.getElementById("emailInfo").disabled = false;
                document.getElementById("passwordInfo").disabled = false;
    

               
            } else {
                alert("Student not found!");

                //not needed
                document.getElementById("firstnameInfo").disabled = true;
                document.getElementById("middlenameInfo").disabled = true;
                document.getElementById("lastnameInfo").disabled = true;
                document.getElementById("emp_id").disabled = true;
                document.getElementById("deptInfo").disabled = true;
                document.getElementById("contactnumberInfo").disabled = true;
                document.getElementById("emailInfo").disabled = true;
                document.getElementById("passwordInfo").disabled = true;
                document.getElementById("signup").disabled = true;

               
            }
        }
    };
}



    </script>


    <script>
        function autoFill() {
            const input1 = document.getElementById('empId').value;
            document.getElementById('emp_id').value = input1;
        }
    </script>
</head>
<body class="d-flex justify-content-center align-items-center" style="height: 100vh; margin: 0;">



<div class="container" id="check" style="padding: 20px; max-width: 500px; margin: auto; border: 2px solid  #f3f3f3; border-radius: 8px; background-color: #f3f3f3; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); text-align: center;">
    <!-- Logo -->
    <img src="../pic/srclogo.png" alt="Logo" style="width: 90px; margin: 10px auto;">

    <h4 class="mb-3" style="font-weight: bold; color: #333;">Teacher ID Verification</h4>

    <div style="margin-bottom: 15px;">
        <label for="empId" style="font-size: 16px; color: #333; font-weight: 500;">Enter Teacher ID:</label>
        <input type="text" id="empId" name="empId" class="form-control mt-2" style="width: 80%; margin: 0 auto; border-radius: 5px; border: 1px solid #ccc;" oninput="autoFill()" placeholder="Teacher ID">
    </div>

    <div>
        <button type="button" class="btn btn-success" style="width: 150px; height: 45px; font-size: 16px; font-weight: bold;" onclick="checkTeacher()">Check ID</button>
    </div>

    <div class="mt-3">
        <a href="../index.php" class="btn btn-danger" style="width: 150px; height: 45px; font-size: 16px; font-weight: bold;">Back</a>
    </div>
</div>

<div class="container" id="signup" style="display: none; width: 500px; padding: 20px; margin: 30px auto; border: 2px solid #f3f3f3; border-radius: 8px; background-color: #fff; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);">
    <form action="registerteacher.php" method="POST">
        <!-- Logos and ID -->
        <div class="text-center mb-4" style="display: flex; align-items: center; justify-content: center;">
            <img src="../pic/srclogo.png" alt="Logo 1" style="width: 75px; height: auto; margin-right: 15px;">
            <div style="text-align: left;">
                <label for="emp_id" class="form-label" style="font-size: 16px; font-weight: bold;">Teacher ID:</label>
                <input type="text" id="emp_id" name="emp_id" class="form-control" disabled style="width: 200px; margin-top: 5px;">
            </div>
            <img src="../pic/ccs-logo.png" alt="Logo 2" style="width: 75px; height: auto; margin-left: 15px;">
        </div>

        <!-- First Name and Last Name -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="firstnameInfo" class="form-label">First Name:</label>
                <input type="text" id="firstnameInfo" name="firstnameInfo" class="form-control" disabled required>
            </div>
            <div class="col-md-6">
                <label for="middlenameInfo" class="form-label">Middle Name:</label>
                <input type="text" id="middlenameInfo" name="middlenameInfo" class="form-control" disabled required>
            </div>
        </div>

        <!-- Middle Name and Department -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="lastnameInfo" class="form-label">Last Name:</label>
                <input type="text" id="lastnameInfo" name="lastnameInfo" class="form-control" disabled required>
            </div>
            <div class="col-md-6">
                <label for="deptInfo" class="form-label">Department:</label>
                <select id="deptInfo" name="deptInfo" class="form-control" disabled required>
                    <option value="">--Select Department--</option>
                    <option value="COE">College of Education</option>
                    <option value="CCS">College of Computer Studies</option>
                    <option value="CBS">College of Business Studies</option>
                </select>
            </div>
        </div>

        <!-- Contact Number and Email -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="contactnumberInfo" class="form-label">Contact Number:</label>
                <input type="number" id="contactnumberInfo" name="contactnumberInfo" class="form-control" disabled required>
            </div>
            <div class="col-md-6">
                <label for="emailInfo" class="form-label">Email:</label>
                <input type="email" id="emailInfo" name="emailInfo" class="form-control" disabled required>
            </div>
        </div>

        <!-- Password -->
        <div class="row mb-3">
            <div class="col-md-12">
                <label for="passwordInfo" class="form-label">Password:</label>
                <input type="password" id="passwordInfo" name="passwordInfo" class="form-control" disabled required>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
            <button type="submit" name="submit" class="btn btn-primary" style="width: 200px; height: 50px; font-size: 16px; font-weight: bold;">Register</button>
        </div>
    </form>
</div>

</body>
</html>
