<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();

$aid = $_SESSION['u_id'];

// Add User
if (isset($_POST['give_feedback'])) {
    $f_uname = htmlspecialchars(trim($_POST['f_uname'])); // Sanitize and validate input
    $f_content = htmlspecialchars(trim($_POST['f_content'])); // Sanitize and validate input

    $errors = array();

    if (!preg_match("/^[a-zA-Z\s]+$/", $f_uname)) {
        $errors[] = "My Name should contain only letters and spaces.";
    }

    if (!preg_match("/^[a-zA-Z0-9\s.,!?]+$/", $f_content)) {
        $errors[] = "My Testimonial should contain only letters, numbers, spaces, and common punctuation.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO tms_feedback (f_uname, f_content) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);

        if ($stmt) {
            $stmt->bind_param('ss', $f_uname, $f_content);
            $stmt->execute();

            $stmt->close();

            $succ = "Feedback Submitted";
        } else {
            $err = "Please Try Again Later";
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
    <!-- Start Navigation Bar -->
    <?php include("vendor/inc/nav.php");?>
    <!-- Navigation Bar -->

    <div id="wrapper">
        <!-- Sidebar -->
        <?php include("vendor/inc/sidebar.php");?>
        <!-- End Sidebar -->

        <div id="content-wrapper">
            <div class="container-fluid">
                <?php if (isset($succ)) {?>
                    <!-- This code for injecting an alert -->
                    <script>
                        setTimeout(function () {
                            swal("Success!", "<?php echo $succ;?>", "success");
                        }, 100);
                    </script>
                <?php }?>

                <?php if (isset($err)) {?>
                    <!-- This code for injecting an alert -->
                    <script>
                        setTimeout(function () {
                            swal("Failed!", "<?php echo $err;?>", "Failed");
                        }, 100);
                    </script>
                <?php }?>

                <!-- Breadcrumbs -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="user-dashboard.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">Feedbacks</li>
                </ol>

                <hr>

                <div class="card">
                    <div class="card-header">
                        Give Feedback
                    </div>
                    <div class="card-body">
                        <!-- Add User Form -->
                        <form method="POST">
                            <?php
                            $aid = $_SESSION['u_id'];
                            $ret = "SELECT * FROM tms_user WHERE u_id=?";
                            $stmt = $mysqli->prepare($ret);
                            $stmt->bind_param('i', $aid);
                            $stmt->execute();
                            $res = $stmt->get_result();

                            while ($row = $res->fetch_object()) {
                            ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">My Name</label>
                                    <input type="text" required readonly class="form-control" value="<?php echo $row->u_fname;?> <?php echo $row->u_lname;?>" id="exampleInputEmail1" name="f_uname">
                                </div>
                            <?php }?>

                            <div class="form-group">
                                <label for="exampleInputEmail1">My Testimonial</label>
                                <textarea type="text" class="form-control" placeholder="Give Your Feedback" id="exampleInputEmail1" name="f_content"></textarea>
                            </div>

                            <button type="submit" name="give_feedback" class="btn btn-success">Give Feedback</button>
                        </form>
                        <!-- End Form -->
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

        <!-- Inject Sweet alert js -->
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
