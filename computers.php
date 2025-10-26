<?php

require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, computer_name, brand, serial_number, memory_size, hard_drive_size, lab 
                            FROM computers WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $computer_name = $row['computer_name'];
        $brand  = $row['brand'];
        $serial_number = $row['serial_number'];
        $memory_size = $row['memory_size'];
        $hard_drive_size = $row['hard_drive_size'];
        $lab = $row['lab'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> Computers </title>
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

    <?php
    require("includes/sidebar.php");
    require("includes/topbar.php");
    ?>
    <div class=" mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="my-auto">
                <ion-icon name="laptop-outline"></ion-icon>
                Computers
            </h3>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Computer</button>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 7rem">
            <form class="row g-3" id="Form" method="POST" action="actions/computer_action.php">
                <div class="col-md-4">
                    <label class="form-label">Computer Name</label>
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>" class="form-control">
                    <input required type="text" name="computer_name" value="<?php echo isset($computer_name) ? $computer_name : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Select Brad</label>
                    <select required id="Type" name="brand" class="form-select">
                        <option value="">Choose Brand</option>
                        <option value="1" <?php echo (isset($brand) && $brand == '1') ? 'selected' : '' ?>>HP</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Serial Number</label>
                    <input required type="text" name="serial_number" value="<?php echo isset($serial_number) ? $serial_number : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Memory Size</label>
                    <input required type="text" name="memory_size" value="<?php echo isset($memory_size) ? $memory_size : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hard Drive Size</label>
                    <input required type="text" name="hard_drive_size" value="<?php echo isset($hard_drive_size) ? $hard_drive_size : '' ?>" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Lab</label>
                    <select required id="Type" name="lab" class="form-select">
                        <option value="">Choose Lab</option>
                        <option value="1" <?php echo (isset($lab) && $lab == '1') ? 'selected' : '' ?>>Lab 1</option>
                    </select>
                </div>

            </form>
        </div>
    </div>

    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Computers </h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" id="searchBox" name="search" placeholder="Search ..">

                            <select name="reporttype" id="reporttype" class="form-select">
                                <option value="">All</option>
                                <option value=""></option>

                            </select>
                        </form>
                    </div>
                    <div class="table-responsive" style="height: 300px">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Computer Name</th>
                                    <th>Computer Brand</th>
                                    <th>Serial Number</th>
                                    <th>Memory Size</th>
                                    <th>Hard Drive Size</th>
                                    <th>Lab</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="computer_table">
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
            function load_computer(search = '') {
                $.ajax({
                    url: "actions/fetch_computer.php",
                    type: "POST",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        $("#computer_table").html(data);
                    }
                });
            }

            // Load on page start
            load_computer();

            // Search computer
            $("#searchBox").on("keyup", function() {
                let search = $(this).val();
                load_computer(search);
            });
        });
    </script>


    <?php
    $title = "Computer";
    successAlert($title);
    ?>
</body>

</html>