<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);

    $stmt = $conn->prepare("
        SELECT module.id AS modid, module.name AS modname, 
        module.course_id AS courseid, semester
        FROM module 
        INNER JOIN course ON course.id = module.course_id
        WHERE module.id = ?
    ");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $edit_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result ? $row = $result->fetch_assoc() : null;

    if ($row) {
        $id = $row['modid'];
        $module_name = $row['modname'];
        $semester = $row['semester'];
        $course_id = $row['courseid'];
    }

    $stmt->close();
}
?>

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
        <div class="g-3" style="margin-bottom: 7rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/module_action.php">
                
                <div class="col-md-4">
                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>" class="form-control">
                    <label class="form-label">Module Name</label>
                    <input required type="text" name="module_name" value="<?php echo isset($module_name) ? $module_name : '' ?>" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Semester</label>
                    <select required name="batch_semester" class="form-select">
                        <option value="">Choose Semester</option>
                        <?php
                        $semesters = ["Sem-1" => "Semester 1", "Sem-2" => "Semester 2", "Sem-3" => "Semester 3", "Sem-4" => "Semester 4","Year-1" => "One Year","Short-Course" => "Short Course"];
                        foreach ($semesters as $key => $val) {
                            $selected = ($semester == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$val</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Course</label>
                    <?php
                    $query_command = "SELECT * FROM course";
                    $result = $conn->query($query_command);
                    ?>
                    <select required name="course_id" class="form-select">
                        <option value="">Select Course</option>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['id'] ?>" <?php echo (isset($course_id) && $course_id ==  $row['id']) ? 'selected' : '' ?>>
                                <?php echo $row['course_name']  ?>
                            </option>
                        <?php } ?>
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
                                    <th>Semester</th>
                                    <th>Course</th>
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