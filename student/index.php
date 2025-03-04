<?php
error_reporting(0);   
session_start();
include("../db.php");
if(isset($_POST['submit']))
{
$email=$_POST['email'];
$password=$_POST['password'];

$sql=mysqli_query($conn,"SELECT * FROM tblstudent WHERE email='$email' and password='$password'");
$result=mysqli_fetch_array($sql);
if($result>0)
{
$_SESSION['login']=$_POST['email'];
$_SESSION['id']=$result['id'];
header("location:../dashboardstud.php");

}
else
{
$_SESSION['errmsg']="Invalid username or password";

}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../static/css/loginstudent.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
        <div class="form-box">
		    <center>
                <img src="../pic/srclogo.png" alt="School Logo" class="logo">
            </center>
			<div class="form-container">
            <h2>Student Login</h2>
            <form method="post">
					<?php echo htmlentities($_SESSION['errmsg']); ?>
                        <?php echo htmlentities($_SESSION['errmsg'] = ""); ?>
                    <span class="text-danger">
                        
                    </span>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="text" id="email" name="email" class="form-control" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="submit">Login</button>
					<a href="../index.php" class="btn btn-link">Back to Home Page</a>				
			</form>
				</div>

			</div>
		</div>
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<script src="vendor/jquery-validation/jquery.validate.min.js"></script>
	
		<script src="assets/js/main.js"></script>

		<script src="assets/js/login.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				Login.init();
			});
		</script>
	
	</body>
	<!-- end: BODY -->
</html>