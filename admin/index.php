<!--Server Side Scripting Language to inject login code-->

<?php
session_start();
include('vendor/inc/config.php');


function logEvent($message) {
    $logFile = 'log.txt';
    $timestamp = date("Y-m-d H:i:s");
    $logMessage = "[{$timestamp}] {$message}\n";
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

if (isset($_POST['admin_login'])) {
  $a_email = filter_var($_POST['a_email'], FILTER_SANITIZE_EMAIL);
    $a_pwd = $_POST['a_pwd'];



    if ($a_email !== 'admin@mail.com') {
        $error = "It doesn't seem to be an admin email address.";
    } else {

    $hashed_pwd = hash('sha256', $a_pwd);

    $stmt = $mysqli->prepare("SELECT a_email, a_pwd, a_id FROM tms_admin WHERE a_email=? AND a_pwd=?");
    $stmt->bind_param('ss', $a_email, $hashed_pwd);
    $stmt->execute();
    $stmt->bind_result($a_email, $hashed_pwd, $a_id);
    $rs = $stmt->fetch();
    $_SESSION['a_id'] = $a_id;

    if ($rs) {

              logEvent("Successful login - User: {$_SESSION['sidx']}, IP: {$_SERVER['REMOTE_ADDR']}");
        header("location:admin-dashboard.php");
    } else {
        $error = "Access Denied. Please Check Your Credentials";

    logEvent("Failed login attempt - User: $x, IP: {$_SERVER['REMOTE_ADDR']}");
    }
  }
}
?>


<!--End Server side-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Vehicle Booking System Transport Saccos, Matatu Industry">
  <meta name="author" content="MartDevelopers">

  <title>Vehicle Booking System - Admin Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">
  <!--Trigger Sweet Alert-->
  <?php if(isset($error)) {?>
  <!--This code for injecting an alert-->
      <script>
            setTimeout(function () 
            { 
              swal("Failed!","<?php echo $error;?>!","error");
            },
              100);
      </script>
					
  <?php } ?>

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">

      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
            <div class="form-label-group">
              <input type="email" id="inputEmail" name="a_email" class="form-control" placeholder="Email address" required="required" autofocus="autofocus">
              <label for="inputEmail">Email address</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="inputPassword" name ="a_pwd" class="form-control" placeholder="Password" required="required">
              <label for="inputPassword">Password</label>
            </div>
          </div>
          <div class="form-group">
            <div class="checkbox">
              <label>
                <input type="checkbox" value="remember-me">
                Remember Password 
              </label>
            </div>
          </div>
          <input type="submit"  class="btn btn-success btn-block" name="admin_login" value="Login">
        </form>

        <div class="text-center">
        <a class="d-block small mt-3" href="../index.php">Home</a>
          <a class="d-block small" href="admin-reset-pwd.php">Forgot Password?</a>
        </div> 

      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!--Sweet alerts js-->
  <script src="vendor/js/swal.js"></script>

  <script>
    var timeoutPeriod = 5; 
    timeoutPeriod = timeoutPeriod * 60 * 1000;
    function resetTimeout() {
      clearTimeout(window.logoutTimer);
      window.logoutTimer = setTimeout(function() {
        window.location.href = 'index.php';
      }, timeoutPeriod);
    }
    $(document).on('mousemove keydown', resetTimeout);
    resetTimeout();
  </script>


</body>

</html>
