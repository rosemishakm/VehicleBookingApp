<?php
include('vendor/inc/config.php');
function isStrongPassword($password) {
    return strlen($password) >= 8 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/\d/', $password);
}

if (isset($_POST['add_user'])) {
    $u_fname = htmlspecialchars($_POST['u_fname']);
    $u_lname = htmlspecialchars($_POST['u_lname']);
    $u_phone = htmlspecialchars($_POST['u_phone']);
    $u_addr = htmlspecialchars($_POST['u_addr']);
    $u_email = filter_var($_POST['u_email'], FILTER_SANITIZE_EMAIL);
    $u_pwd = htmlspecialchars($_POST['u_pwd']);

    $errors = array();

    if (!preg_match("/^[a-zA-Z\s]+$/", $u_fname) || !preg_match("/^[a-zA-Z\s]+$/", $u_lname)) {
        $errors[] = "First name and last name should contain only letters and spaces.";
    }

    if (!ctype_digit($u_phone)) {
        $errors[] = "Contact should contain only numeric characters.";
    }

    if (!filter_var($u_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (!preg_match("/^[a-zA-Z0-9\s]+$/", $u_addr)) {
        $errors[] = "Address should contain only letters, numbers, and spaces.";
    }

    if (!isStrongPassword($u_pwd)) {
      $errors[] = "Password should be at least 8 characters long, include at least one uppercase letter, one lowercase letter, and one digit.";
  }

  if (empty($errors)) {
      $hashedPwd = hash('sha256', $u_pwd);

      $u_category = "User";

      $query = "INSERT INTO tms_user (u_fname, u_lname, u_phone, u_addr, u_category, u_email, u_pwd) VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = $mysqli->prepare($query);

      if ($stmt) {
          $stmt->bind_param('sssssss', $u_fname, $u_lname, $u_phone, $u_addr, $u_category, $u_email, $hashedPwd);
          $stmt->execute();
          if ($stmt->affected_rows > 0) {
              $succ = "Account Created. Proceed To Log In";
          } else {
              $err = "Please Try Again Later";
          }
          $stmt->close();
      }
  } else {
      $err = implode("<br>", $errors);
  }
}
?>

<!--End Server Side Scripting-->
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Transport Management System, Saccos, Matwana Culture">
  <meta name="author" content="MartDevelopers ">

  <title>Transport Management System Client - Register</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Custom styles for this template-->
  <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

<body class="bg-dark">
<?php if(isset($succ)) {?>
                        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Success!","<?php echo $succ;?>!","success");
                    },
                        100);
        </script>

        <?php } ?>
        <?php if(isset($err)) {?>
        <!--This code for injecting an alert-->
        <script>
                    setTimeout(function () 
                    { 
                        swal("Failed!","<?php echo $err;?>!","Failed");
                    },
                        100);
        </script>

        <?php } ?>
  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Create An Account With Us</div>
      <div class="card-body">
        <!--Start Form-->

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-4">
                <div class="form-label-group">
                <input type="text" required class="form-control" id="exampleInputEmail1" name="u_fname">
                  <label for="firstName">First name</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-label-group">
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_lname">
                  <label for="lastName">Last name</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-label-group">
                <input type="text" class="form-control" id="exampleInputEmail1" name="u_phone">
                  <label for="lastName">Contact</label>
                </div>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
            <input type="text" class="form-control" id="exampleInputEmail1" name="u_addr">
              <label for="inputEmail">Address</label>
            </div>
          </div>
          <div class="form-group" style ="display:none">
            <div class="form-label-group">
            <input type="text" class="form-control" id="exampleInputEmail1" value="User" name="u_category">
              <label for="inputEmail">User Category</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
            <input type="email" class="form-control" name="u_email"">
              <label for="inputEmail">Email address</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-row">
              <div class="col-md-12">
                <div class="form-label-group">
                <input type="password" class="form-control" name="u_pwd" id="exampleInputPassword1">
                  <label for="inputPassword">Password</label>
                </div>
              </div>
            </div>
          </div>
          <button type="submit" name="add_user" class="btn btn-success">Create Account</button>
        </form>
        <!--End FOrm-->
        <div class="text-center">
          <a class="d-block small mt-3" href="index.php">Login Page</a>
          <a class="d-block small" href="usr-forgot-pwd.php">Forgot Password?</a>
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
