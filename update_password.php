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

            <form action="actions/update_password_action.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Enter Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Enter New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" autofocus />
                </div>

            <div class="mb-3 form-password-toggle">
                <div class="input-group input-group-merge">
                    <span class="input-group-text cursor-pointer" id="togglePassword"><i class="bx bx-hide">
                        <i class="bi bi-eye"></i></i> &nbsp; View Password
                    </span>
                </div>
            </div>

            <div class="mb-3">
                <button class="btn btn-primary d-flex align-items-center justify-content-center w-100" name="auth_login" type="submit">
                    Reset Password Now <i class="bi bi-arrow-right ms-2"></i>
                </button>
                <!-- <a href="forgot_password.php"><small>Login Page </small></a> -->
            </div>
        </form>
    </div>
</div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.innerHTML = type === 'password' ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });
    </script>

    <?php $title = "Login";
    successAlert($title);
    ?>

</body>
</html>