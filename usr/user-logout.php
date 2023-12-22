<?php
    session_start();
    print_r($_SESSION);
    unset($_SESSION['u_id']);
    session_destroy();

    header("Location: index.php");
    exit;
?>
