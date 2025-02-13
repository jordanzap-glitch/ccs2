<?php
session_start();
include('../db.php');
if(strlen($_SESSION['id']==0)) {
    header('location:index.php');
     } else{
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Information</title>
    <link rel="stylesheet" href="static/ccs/register2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script>
        function fetchAdminInfo() {
            const empId = document.getElementById('emp_id').value;
            if (empId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `fetch_admin.php?emp_id=${empId}`, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            document.getElementById('firstname').value = response.data.firstname;
                            document.getElementById('middlename').value = response.data.middlename;
                            document.getElementById('lastname').value = response.data.lastname;
                            document.getElementById('dept').value = response.data.dept;
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
    </script>

<script>
    function loadInput1() {
        const mirroredInput = localStorage.getItem('mirroredInput');
        document.getElementById('emp_id').value = mirroredInput ? mirroredInput : '';
        document.getElementById('showInfoButton').click();
    }
</script>
</head>
<body onload="loadInput1()">
    <form action="update.php" method="POST">
        <div class="form-box">
            <center>
                <img src="../pic/srclogo.png" alt="School Logo" class="logo">
            </center>

            <h2>Admin Registration</h2>
            <div class="text-center">
                <i id="showInfoButton" class="fas fa-info-circle fa-2x text-primary" style="display: none;" onclick="fetchAdminInfo()"></i>
            </div>

            <div class="form-container">

                <div class="form-group">
                    <i class="fas fa-id-card mr-2"></i>
                    <label for="emp_id" >Employee ID:</label>
                    <input type="text" id="emp_id" name="emp_id" readonly onblur="fetchadminInfo()">
                </div>

                <div class="form-group">
                <i class="fas fa-user mr-2""></i>
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" required>
                </div>

                <div class="form-group">
                <i class="fas fa-user mr-2"></i>
                    <label for="middlename">Middle Name:</label>
                    <input type="text" id="middlename" name="middlename" required>
                </div>

                <div class="form-group">
                <i class="fas fa-user mr-2"></i>
                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>

                <div class="form-group">
                <i class="fas fa-book mr-2"></i>
                    <label for="dept">Course:</label>
                    <select id="dept" name="dept" required>
                        <option value="">--Select Course--</option>
                        <option value="BSIS">Bachelor of Science in Information Systems</option>
                        <option value="BSAIS">Bachelor of Science in Accounting Information Systems</option>
                        <option value="BEED">Bachelor of Elementary Education</option>
                        <option value="BSET">Bachelor of Science in Entrepreneurship</option>
                    </select>
                </div>

                <div class="form-group">
                <i class="fas fa-phone mr-2"></i>
                    <label for="contactnumber">Contact Number:</label>
                    <input type="text" id="contactnumber" name="contactnumber" required>
                </div>

                <div class="form-group">
                <i class="fas fa-envelope mr-2"></i>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                <i class="fas fa-lock mr-2"></i>
                    <label for="password">Password:</label>
                    <input type="text" id="password" name="password" required>
                </div>

                <div class="center-btn">
                    <input type="submit" value="REGISTER">
                </div>

                <a href="../index.php" class="back-link">Back to Home Page</a>
            </div>
        </div>
    </form>  
    
</body>
</html>
<?php } ?>