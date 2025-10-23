<?php

require_once('alert.php');
// require_once('../baseConnect/dbConnect.php');


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Frhab Rehabilitation Center</title>
    <link rel="shortcut icon" href="../assets/imgs/icons/frhab-favlogo.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Exo+2&family=Montserrat&family=Raleway&family=Roboto&display=swap" rel="stylesheet" />
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
</head>

<body>
    <!-- =============== Navigation ================ -->
    <?php
    require("includes/sidebar.php");
    require("includes/topbar.php");

    // dashboard data summary
    require("includes/analytics.php");
    require("includes/summary.php");
    ?>

    <!-- =========== Scripts =========  -->
    <!-- <script src="assets/js/main.js"></script> -->

    <!-- ====== ionicons ======= -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>

   
</body>

</html>