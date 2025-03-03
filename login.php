<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if (isset($_POST['login'])) {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Assuming you're using md5 for password hashing
    // $password = md5($password); // Uncomment this line if you are using md5

    // Check in Teacher table
    $query_teacher = "SELECT * FROM tblteacher WHERE email = ? AND password = ?";
    $stmt_teacher = $conn->prepare($query_teacher);
    $stmt_teacher->bind_param("ss", $username, $password);
    $stmt_teacher->execute();
    $rs_teacher = $stmt_teacher->get_result();
    $num_teacher = $rs_teacher->num_rows;

    if ($num_teacher > 0) {
        // Teacher detected
        $rows_teacher = $rs_teacher->fetch_assoc();
        $_SESSION['userId'] = $rows_teacher['id']; // Use 'id' from tblteacher
        $_SESSION['firstName'] = $rows_teacher['firstname'];
        $_SESSION['lastName'] = $rows_teacher['lastname'];
        $_SESSION['emailAddress'] = $rows_teacher['email'];
        $_SESSION['user_type'] = 'Teacher'; // Set session user type

        header('Location:admin/dashboard.php'); // Redirect to the teacher dashboard
        exit();
    } else {
        // Check in Student table
        $query_student = "SELECT * FROM tblstudent WHERE email = ? AND password = ?";
        $stmt_student = $conn->prepare($query_student);
        $stmt_student->bind_param("ss", $username, $password);
        $stmt_student->execute();
        $rs_student = $stmt_student->get_result();
        $num_student = $rs_student->num_rows;

        if ($num_student > 0) {
            // Student detected
            $rows_student = $rs_student->fetch_assoc();
            $_SESSION['userId'] = $rows_student['id']; // Use 'id' from tblstudent
            $_SESSION['firstName'] = $rows_student['firstname'];
            $_SESSION['lastName'] = $rows_student['lastname'];
            $_SESSION['email'] = $rows_student['email'];
            $_SESSION['user_type'] = 'Student'; // Set session user type

            header('Location:dashboardstud.php'); // Redirect to the student dashboard
            exit();
        } else {
            // Invalid username or password
            echo "<div class='alert alert-danger' role='alert'>
            Invalid Username/Password!
            </div>";
        }
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="img/logo/attnlg.jpg" rel="icon">
  <title>OJT-MS - Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-login">
  <!-- Login Content -->
  <div class="container-login">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card shadow-sm my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-12">
                <div class="login-form">
                  <h5 align="center">OJT MONITORING SYSTEM</h5>
                  <div class="text-center">
                    <img src="img/logo/src-logo.jpg" style="width:100px;height:100px">
                    <br><br>
                    <h1 class="h4 text-gray-900 mb-4">Login Panel</h1>
                  </div>
                  <form class="user" method="POST" action="">
                    <div class="form-group">
                      <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small" style="line-height: 1.5rem;">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <div class="form-group">
                      <input type="submit" class="btn btn-success btn-block" value="Login" name="login" />
                    </div>
                  </form>
                  <div class="text-center"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Login Content -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
</body>

</html>