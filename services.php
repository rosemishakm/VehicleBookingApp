<!DOCTYPE html>
<html lang="en">

<?php include("vendor/inc/head.php");?>

<body>

  <!-- Navigation -->
  <?php include("vendor/inc/nav.php");?>

  <!-- Page Content -->
  <div class="container">

    <!-- Page Heading/Breadcrumbs -->
    <h1 class="mt-4 mb-3">Services
    </h1>

    <ol class="breadcrumb">
      <li class="breadcrumb-item">
        <a href="index.php">Home</a>
      </li>
      <li class="breadcrumb-item active">Services</li>
    </ol>

    <!-- Image Header -->
    <img class="img-fluid rounded mb-4 d-block mx-auto" src="vendor\img\p90475606-highres-rolls-royce-phantom-1677268219.jpg" alt="">

    <!-- Marketing Icons Section -->
    <div class="row">
      <div class="col-lg-4 mb-4">
        <div class="card h-100">
          <h4 class="card-header">Enhanced Transport Modes</h4>
          <div class="card-body">
            <p class="card-text">
              We Improve access to public transport for all people and organizations by strengthening
              he condition s for sustainable transport modes.
            </p>
          </div>
          
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card h-100">
          <h4 class="card-header">Faster And Safe Travels</h4>
          <div class="card-body">
            <p class="card-text">
              Our Travels reduce traffic growth and congestion by achieving a mode shift from private
              motorized vehicle trips to a more efficient and sustainable mode of transport.
            </p>
          </div>
          
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <div class="card h-100">
          <h4 class="card-header">Networking</h4>
          <div class="card-body">
            <p class="card-text">
              Create an efficient multimodal public transport network that will facilitate the
              interconnection and interoperability of associated transport network.
            </p>
          </div>
          
        </div>
      </div>
    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <?php include("vendor/inc/footer.php");?>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>
    // Set the timeout period in minutes 
    var timeoutPeriod = 5; 

    // Convert minutes to milliseconds
    timeoutPeriod = timeoutPeriod * 60 * 1000;

    // Reset the timeout on user activity
    function resetTimeout() {
      clearTimeout(window.logoutTimer);
      window.logoutTimer = setTimeout(function() {
        // Redirect to logout or any other action
        window.location.href = 'index.php';
      }, timeoutPeriod);
    }

    // Bind the resetTimeout function to user activity events
    $(document).on('mousemove keydown', resetTimeout);

    // Initial setup
    resetTimeout();
  </script>

</body>

</html>
