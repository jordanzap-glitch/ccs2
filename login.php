<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

$error_message = ''; // Initialize an error message variable

if (isset($_POST['login'])) {
    // Get the submitted username and password
    $username = $_POST['username'];
    $password = $_POST['password'];
    // Assuming you're using md5 for password hashing
    // $password = md5($password); // Uncomment this line if you are using md5

    // Clean old logs from user_logs table
    $clean_logs_query = "DELETE FROM user_logs WHERE timestamp < NOW() - INTERVAL 1 MONTH";
    $conn->query($clean_logs_query);

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
        $_SESSION['userId'] = $rows_teacher['id']; 
        $_SESSION['emp_id'] = $rows_teacher['emp_id']; // Use 'emp_id' from tblteacher
        $_SESSION['firstName'] = $rows_teacher['firstname'];
        $_SESSION['lastName'] = $rows_teacher['lastname'];
        $_SESSION['dept'] = $rows_teacher['dept'];
        $_SESSION['emailAddress'] = $rows_teacher['email'];
        $_SESSION['user_type'] = 'Teacher'; // Set session user type

        // Insert log into user_logs table
        $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
        $log_query = "INSERT INTO user_logs (user_id, fullname, course, action, user_type, timestamp) VALUES ( ?, ?, ?, 'Logged in as Teacher', ?, NOW())";
        $log_stmt = $conn->prepare($log_query);
        $log_stmt->bind_param("isss", $_SESSION['emp_id'], $fullname, $_SESSION['dept'], $_SESSION['user_type']);
        $log_stmt->execute();

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
            $_SESSION['userId'] = $rows_student['id']; 
            $_SESSION['student_id'] = $rows_student['student_id']; // Use 'student_id' from tblstudent
            $_SESSION['firstName'] = $rows_student['firstname'];
            $_SESSION['lastName'] = $rows_student['lastname'];
            $_SESSION['course'] = $rows_student['course']; // Assuming 'course' is in tblstudent
            $_SESSION['email'] = $rows_student['email'];
            $_SESSION['user_type'] = 'Student'; // Set session user type

            // Insert log into user_logs table
            $fullname = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
            $log_query = "INSERT INTO user_logs (user_id, fullname, course, action, user_type, timestamp) VALUES ( ?, ?, ?, 'Logged in as Student', ?, NOW())";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param("isss", $_SESSION['student_id'], $fullname, $_SESSION['course'], $_SESSION['user_type']);
            $log_stmt->execute();

            header('Location:student/dashboardstud.php'); // Redirect to the student dashboard
            exit();
        } else {
            // Invalid username or password
            $error_message = "Invalid Username/Password!"; // Set the error message
        }
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Panel</title>
  <link rel="icon" href="img/logo/attnlg.jpg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to bottom,rgb(20, 37, 95), #fffc58);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-card {
      width: 100%;
      max-width: 400px;
      background: #fff;
      border-radius: 10px;
      padding: 30px;
      box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
    }
    .btn-login {
      background-color: #4e73df;
      border: none;
    }
    .btn-login:hover {
      background-color: #3752aa;
    }
    .form-control {
      border-radius: 30px;
      padding: 10px;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #4e73df;
    }
    .custom-checkbox .custom-control-label {
      cursor: pointer;
    }
    /* SCHOOL LOGO */
    .logo {
        width: 90px;
        height: auto;
        margin-bottom: 10px;
    }
  </style>
</head>

<body>
 <div class="login-card text-center">
    <center>
      <img src="pic/srclogo.png" alt="School Logo" class="logo">
    </center>
    <h3 class="text-dark mb-4 text-center">Log in</h3>
    <form method="POST" action="">
      <div class="mb-3">
        <input type="text" class="form-control" required name="username" id="exampleInputEmail" placeholder="Enter Email Address">
      </div>
      <div class="mb-3">
        <input type="password" name="password" required class="form-control" id="exampleInputPassword" placeholder="Enter Password">
      </div>
      <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="rememberMe">
          <label class="form-check-label" for="rememberMe">Remember Me</label>
        </div>
        <a href="#" class="small text-primary">Forgot Password?</a>
      </div>
      <div class="d-flex justify-content-between">
        <input type="submit" class="btn btn-success" value="Login" name="login" />
        <a href="index.php" class="btn btn-secondary">Back</a> 
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>