<?php
    require_once('alert.php');
    require_once('actions/start_session.php');
    require_once('baseConnect/dbConnect.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Change Password - IPMC INVENTORY MANAGER</title>
    <link rel="icon" type="image/ico" href="assets/imgs/inventory_logo.png" />
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
    ?>

    <!-- ======================= Cards ================== -->


    <form method="POST" class="" id="contactForm" action="actions/change_password.php">
        <div class=" mx-auto" style="margin-top: 4rem; width:85%">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><ion-icon name="key-outline"></ion-icon>Change Password</h3>
                </div>


            </div>
            <hr style="margin-bottom: 3rem;">

            <div class="row g-3">
                <input type="hidden" name="id" value="<?php echo $_SESSION['id'] ?>">

                <div class="col-md-6">
                    <label for="input1" class="form-label">Old Password</label>
                    <input required type="password" name="old_password" class="form-control">
                </div>

                <div class="col-md-6">
                    <label for="input1" class="form-label">New Password</label>
                    <input required type="password" name="new_password" class="form-control">
                </div>


                <div class="">
                    <button class="btn text-white px-4 mt-5" style="background-color:rgb(200, 72, 105)">Update Password</button>
                </div>

            </div>
        </div>
    </form>



    <!--  Table Section -->
    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow ">
                    <!-- Card Header -->
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Users</h5>
                        <form method="POST" id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" id="searchBox" name="search" placeholder="Search..">
                        </form>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive " style="height: 500px">
                        <table class="table table-striped align-middle ">
                            <thead class="">
                                <tr>
                                    <th>#</th>
                                    <th>Email</th>
                                    <th>Usertype</th>
                                    <th>Assigned Instructor</th>
                                    <th>DateCreated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody id="users_table">
                                <!-- fetch the data using the ajax -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer">
                        <nav>
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        $(document).ready(function() {
            function load_users(search = '') {
                $.ajax({
                    url: "actions/fetch_user.php",
                    type: "POST",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        $("#users_table").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + error);
                    }
                });
            }

            load_users();

            $("#searchBox").on("keyup", function() {
                load_users($(this).val());
            });
        });
    </script>


    <?php
    $title = "User";
    successAlert($title);
    ?>
</body>

</html>