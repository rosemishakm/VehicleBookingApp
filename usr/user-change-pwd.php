<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();
$aid = $_SESSION['u_id'];

function isStrongPassword($password) {

    return strlen($password) >= 8 && preg_match('/[A-Za-z]/', $password) && preg_match('/\d/', $password) && preg_match('/[^A-Za-z\d]/', $password);
}


if (isset($_POST['update_password'])) {
    $u_id = $_SESSION['u_id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];


    $stmt = $mysqli->prepare("SELECT u_pwd FROM tms_user WHERE u_id = ?");
    $stmt->bind_param('i', $u_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();


    $errors = [];

    if (empty($old_password)) {
        $errors[] = "Old Password is required";
    } elseif (hash('sha256', $old_password) !== $hashed_password) {
        $errors[] = "Old Password is incorrect";
    }

    if (empty($new_password)) {
        $errors[] = "New Password is required";
    } elseif (!isStrongPassword($new_password)) {
        $errors[] = "New Password should be at least 8 characters long and include a mix of letters, numbers, and special characters";
    } elseif ($new_password === $old_password) {
        $errors[] = "New Password should be different from the Old Password";
    }

    if ($new_password !== $confirm_password) {
        $errors[] = "New Password and Confirm Password do not match";
    }

    if (empty($errors)) {

        $hashed_new_password = hash('sha256', $new_password); 

        if ($hashed_new_password !== $hashed_password) {
            $query = "UPDATE tms_user SET u_pwd=? WHERE u_id=?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('si', $hashed_new_password, $u_id);
            $stmt->execute();

            if ($stmt) {
                $succ = "Password Updated";
            } else {
                $err = "Please Try Again Later";
            }
        } else {
            $err = "New Password should be different from the Old Password";
        }
    } else {

        $err = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<body id="page-top">
 <!--Start Navigation Bar-->
  <?php include("vendor/inc/nav.php");?>
  <!--Navigation Bar-->

  <div id="wrapper">

    <!-- Sidebar -->
    <?php include("vendor/inc/sidebar.php");?>
    <!--End Sidebar-->
    <div id="content-wrapper">

      <div class="container-fluid">
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

         <!-- Breadcrumbs-->
         <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="user-dashboard.php">Dashboard</a>
          </li>
          <li class="breadcrumb-item">Profile</li>
          <li class="breadcrumb-item active">Change Password</li>
        </ol>
        <hr>
        <div class="card">
        <div class="card-header">
          Add User
        </div>
        <div class="card-body">
        
          <form method ="POST"> 
            
            <div class="form-group">
                <label for="exampleInputEmail1">Old Password</label>
                <input type="password" class="form-control" name="old_password" required>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">New Password</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            
            <button type="submit" name="update_password" class="btn btn-outline-danger">Change Password</button>
          </form>
          <!-- End Form-->
        </div>
      </div>
       
      <hr>

      <!-- Sticky Footer -->
      <?php include("vendor/inc/footer.php");?>

    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger" href="user-logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/chart.js/Chart.min.js"></script>
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="vendor/js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="vendor/js/demo/datatables-demo.js"></script>
  <script src="vendor/js/demo/chart-area-demo.js"></script>
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
