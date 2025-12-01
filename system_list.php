<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');
require_once('includes/counts_analytics.php');


// fetch instructor name
if (!isset($_SESSION['instructorid']) && !isset($_SESSION['type'])) {
    die("Instructor not logged in.");
}

$instid = $_SESSION['instructorid'];
$usertype = $_SESSION['type'];

$stmt = $conn->prepare("SELECT * FROM instructors WHERE id = ?");
$stmt->bind_param("i", $instid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $instructor = $result->fetch_assoc();
    $inst_name = $instructor['first_name'] . " " . $instructor['last_name'];
} else {
    echo "Instructor not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Systems - IPMC INVENTORY MANAGER</title>
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

    <div class="mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="my-auto">
                <ion-icon name="book-outline"></ion-icon>
                Systems
            </h3>
            <div>
                <div class="btn text-white px-2 bg-primary ">Monitors: <?php echo $totalMonitors ?> </div>
                <div class="btn text-white px-2 bg-danger ">System Units: <?php echo $totalSystems ?> </div>
            </div>
            <div>
                <a href="system.php"><button class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Add System</button></a>
            </div>
        </div>
        <hr class="mb-4">
    </div>

    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of System </h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" style="padding-right: 150px" id="searchBox" name="search" placeholder="Search..">

                            <select name="lab_type" id="lab_type" class="form-select">
                                <option value="">All Labs</option>
                                <?php
                                $query_command = "SELECT DISTINCT lab_name FROM lab";
                                $result = $conn->query($query_command);
                                while ($lab_result = $result->fetch_assoc()) {
                                    echo '<option value="' . $lab_result['lab_name'] . '">' . $lab_result['lab_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <select name="brand_type" id="brand_type" class="form-select">
                                <option value="">All Brands</option>
                                <?php
                                $query_command = "SELECT DISTINCT brand_name FROM brand";
                                $result = $conn->query($query_command);
                                while ($brand = $result->fetch_assoc()) {
                                    echo '<option value="' . $brand['brand_name'] . '">' . $brand['brand_name'] . '</option>';
                                }
                                ?>
                            </select>

                            <select name="memory_type" id="memory_type" class="form-select">
                                <option value="">All Memory</option>
                                <?php
                                $query_command = "SELECT DISTINCT memory_size  FROM `system` ";
                                $result = $conn->query($query_command);
                                while ($memory = $result->fetch_assoc()) {
                                    echo '<option value="' . $memory['memory_size'] . '">' . $memory['memory_size'] . "GB" . '</option>';
                                }
                                ?>
                            </select>

                            <select name="drive_type" id="drive_type" class="form-select">
                                <option value="">All HDD/SSD</option>
                                <?php
                                $query_command = "SELECT DISTINCT hard_drive_size FROM `system` ";
                                $result = $conn->query($query_command);
                                while ($drive = $result->fetch_assoc()) {
                                    echo '<option value="' . $drive['hard_drive_size'] . '">' . $drive['hard_drive_size'] . "GB" . '</option>';
                                }
                                ?>
                            </select>
                        </form>
                    </div>

                    <!-- Exams Table (unchanged) -->
                    <div class="table-responsive" style="height: 500px;">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>SystemInfo</th>
                                    <th>Brand</th>
                                    <th>Serial_No</th>
                                    <th>Iseries</th>
                                    <th>Processor Type</th>
                                    <th>Speed</th>
                                    <th>Generation</th>
                                    <th>Memory</th>
                                    <th>HDD/SSD</th>
                                    <th>Lab</th>
                                    <th>DateAdded</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="system_table">
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
            // Function to load filtered results
            function load_systems(search = '', lab_type = '', brand_type = '', memory_type = '', drive_type = '') {
                $.ajax({
                    url: "actions/fetch_system.php",
                    type: "POST",
                    data: {
                        search: search,
                        lab_type: lab_type,
                        brand_type: brand_type,
                        memory_type: memory_type,
                        drive_type: drive_type
                    },
                    success: function(data) {
                        $("#system_table").html(data);
                    }
                });
            }

            // Load all on page start
            load_systems();

            // When typing in search box
            $("#searchBox").on("keyup", function() {
                load_systems(
                    $(this).val(),
                    $("#lab_type").val(),
                    $("#brand_type").val(),
                    $("#memory_type").val(),
                    $("#drive_type").val()
                );
            });

            // When any filter changes
            $("#lab_type, #brand_type, #memory_type, #drive_type").on("change", function() {
                load_systems(
                    $("#searchBox").val(),
                    $("#lab_type").val(),
                    $("#brand_type").val(),
                    $("#memory_type").val(),
                    $("#drive_type").val()
                );
            });
        });
    </script>

    <?php
    $title = "System";
    successAlert($title);
    ?>

</body>

</html>