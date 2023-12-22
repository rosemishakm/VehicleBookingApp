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

define('MAX_LOGIN_ATTEMPTS', 3);
define('LOCKOUT_DURATION', 120); // in seconds

if (isset($_SESSION["account_locked"]) && $_SESSION["account_locked"] > time()) {
    $remainingTime = $_SESSION["account_locked"] - time();
    echo "<h3><span style='color:red;'>Account is locked due to too many failed login attempts. Please try again later. Remaining time: {$remainingTime} seconds.</span></h3>";
    logEvent("Account locked - User: {$_POST['u_email']}, IP: {$_SERVER['REMOTE_ADDR']}");
    exit;
}

if (isset($_POST['Usr-login'])) {
    $u_email = filter_var($_POST['u_email'], FILTER_SANITIZE_EMAIL);
    $u_pwd = $_POST['u_pwd'];
    $hashed_password = hash('sha256', $u_pwd);

    $query = "SELECT u_email, u_pwd, u_id FROM tms_user WHERE u_email=? AND u_pwd=?";
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ss', $u_email, $hashed_password);
    $stmt->execute();
    $stmt->bind_result($u_email, $hashed_password, $u_id);
    $rs = $stmt->fetch();
    $stmt->close();

    $_SESSION['u_id'] = $u_id;
    $_SESSION['login'] = $u_email;
    $uip = $_SERVER['REMOTE_ADDR'];
    $ldate = date('d/m/Y h:i:s', time());

    if ($rs) {
        // Get user logs
        $uid = $_SESSION['u_id'];
        $u_email = $_SESSION['login'];
        $ip = $_SERVER['REMOTE_ADDR'];
        $geopluginURL = 'https://www.geoplugin.net/php.gp?ip=' . $ip;
        $addrDetailsArr = unserialize(file_get_contents($geopluginURL));
        $city = $addrDetailsArr['geoplugin_city'];
        $country = $addrDetailsArr['geoplugin_countryName'];

        $logQuery = "INSERT INTO userLog(u_id, u_email, u_ip, u_city, u_country) VALUES (?, ?, ?, ?, ?)";
        

        $logStmt = $mysqli->prepare($logQuery);

        if ($logStmt) {

            $logStmt->bind_param('issss', $uid, $u_email, $ip, $city, $country);

            $logStmt->execute();
            $logStmt->close();
        }


        setcookie('user_email', $u_email, time() + 3600, '/','',true,true);

        logEvent("Successful login - User: {$_SESSION['login']}, IP: {$_SERVER['REMOTE_ADDR']}");
        header("location:user-dashboard.php");
    } else {

        $_SESSION["login_attempts"] = isset($_SESSION["login_attempts"]) ? ($_SESSION["login_attempts"] + 1) : 1;

        logEvent("Failed login attempt - User: $u_email, IP: {$_SERVER['REMOTE_ADDR']}");

  
        if ($_SESSION["login_attempts"] >= MAX_LOGIN_ATTEMPTS) {
            $_SESSION["account_locked"] = time() + LOCKOUT_DURATION;
            echo "<h3><span style='color:red;'>Account is locked due to too many failed login attempts. Please try again later.</span></h3>";
            
            logEvent("Account locked - User: $u_email, IP: {$_SERVER['REMOTE_ADDR']}");
        } else {
            $error = "Access Denied. Please Check Your Credentials";
        }
    }
}
?>
<!--End Server Side Script Injection-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Vehicle Booking System - Client Login</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Client Login Panel</div>
      <div class="card-body">
        <!--INJECT SWEET ALERT-->
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
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
            <div class="form-label-group">
              <input type="email" name="u_email" id="inputEmail" class="form-control"  required="required" autofocus="autofocus">
              <label for="inputEmail">Email address</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="u_pwd" id="inputPassword" class="form-control"  required="required">
              <label for="inputPassword">Password</label>
            </div>
          </div>
          <input type="submit" name="Usr-login" class="btn btn-success btn-block" value="Login">
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="usr-register.php">Register an Account</a>
          <a class="d-block small" href="usr-forgot-password.php">Forgot Password?</a>
          <a class="d-block small" href="../index.php">Home</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!--INject Sweet alert js-->
 <script src="vendor/js/swal.js"></script>

   <!-- User inactivity timeout script -->


</body>

</html>

