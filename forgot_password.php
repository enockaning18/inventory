<?php require_once('alert.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Inventory System</title>
    <link rel="icon" type="image/ico" href="assets/imgs/inventory_logo.png" />
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Exo+2&family=Montserrat&family=Raleway&family=Roboto&display=swap" rel="stylesheet" />
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
</head>

<body class="log-body">
    <div class="log-card">
        <div class="card-body px-5 py-4">
            <div class="app-brand justify-content-center">
                <a href="index.html" class="app-brand-link gap-2">
                    <img src="assets/imgs/inventory_logo.png" class="log-logo" alt="Logo">
                </a>
            </div>

        <form action="actions/forgot_password_action.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                </div>

            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                </div>
            </div>

            <div class="mb-3">
                <button class="btn btn-primary d-flex align-items-center justify-content-center w-100" name="auth_login" type="submit">
                    Change Password <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
            <center>
                <a href="index.php"><small style="color: #fff;">Remember now..? Login here </small></a>
            </center>
        </form>

        </div>
    </div>

    <?php $title = "Login";
    successAlert($title);
    ?>

</body>

</html>