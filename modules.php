<?php

require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, name FROM module WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $module_name = $row['name'];
    }
    $stmt->close();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Modules - IPMC INVENTORY MANAGER</title>
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

    <?php
    require("includes/sidebar.php");
    require("includes/topbar.php");
    ?>
    <div class=" mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3><ion-icon name="document-outline"></ion-icon> Module</h3>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Module</button>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 4rem">
            <form class=" border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/module_action.php" class="row g-3 p-3 border rounded bg-light shadow-sm">
                <div class="col-md-4">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>" class="form-control">
                    <label class="form-label">Module Name</label>
                    <input required type="text" name="module_name" value="<?php echo isset($module_name) ? $module_name : '' ?>" class="form-control">
                </div>
            </form>
        </div>
    </div>

    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Modules</h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control px-4" id="searchBox" name="search" placeholder="Search..">
                        </form>
                    </div>
                    <div class="table-responsive" style="height: 300px">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>DateCreated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="module_table">
                                <!-- fetch the data using the ajax -->
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <nav>
                            <ul class="pagination justify-content-center mb-0">
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        $(document).ready(function() {
            function load_module(search = '') {
                $.ajax({
                    url: "actions/fetch_module.php",
                    type: "POST",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        $("#module_table").html(data);
                    }
                });
            }

            // Load on page start
            load_module();

            // Search computer
            $("#searchBox").on("keyup", function() {
                let search = $(this).val();
                load_module(search);
            });
        });
    </script>


    <?php
    $title = "Module";
    successAlert($title);
    ?>
</body>

</html>