<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');

// fetch userid
if (!isset($_SESSION['id'])) {
    die("Instructor not logged in.");
}

$userid = $_SESSION['id'];

// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT lab_name, course.course_name, lab.number_computers, lab.date_added, lab.id, lab.course_id, course.course_name FROM lab
    INNER JOIN course ON course.id = lab.course_id WHERE  lab.id = ?");

    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $lab_name = $row['lab_name'];
        $course_id  = $row['course_id'];
        $number_computers = $row['number_computers'];
        $course_name = $row['course_name'];
    }
    $stmt->close();

} 
// edit course
else if (isset($_GET['edit_course_id']) && is_numeric($_GET['edit_course_id'])) {
    $edit_course_id = intval($_GET['edit_course_id']);
    $stmt = $conn->prepare("SELECT * FROM course WHERE id = ?");

    $stmt->bind_param("i", $edit_course_id);
    $stmt->execute();
    $course_row = $stmt->get_result()->fetch_assoc();
    if ($course_row) {
        $id_course = $course_row['id'];
        $course_name = $course_row['course_name'];
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Labs/Courses - IPMC INVENTORY MANAGER</title>
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
            <h3><ion-icon name="home-outline"></ion-icon> Lab & Course</h3>
            <a href="viewcourse.php"><button class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">View Courses</button></a>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 7rem">
            <div class="container mt-4">
                <div class="row g-4">

                    <!-- FORM 1: LAB INFORMATION -->

                    <div class="col-12 col-md-6 ">
                        <form id="LabForm" method="POST" action="actions/lab_action.php" class="p-3 border rounded bg-light shadow-sm">
                            <h5 class="mb-3 ">Lab Information</h5>

                            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

                            <div class="mb-3">
                                <label class="form-label">Lab Name</label>
                                <input required type="text" name="lab_name" value="<?php echo isset($lab_name) ? $lab_name : '' ?>" class="form-control">
                            </div>

                            <div class="mb-3">
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



                            <div class="mb-3">
                                <label class="form-label">Number of Computers</label>
                                <input required type="number" name="number_computers" value="<?php echo isset($number_computers) ? $number_computers : '' ?>" class="form-control">
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn text-white btn-primary">Save / Update Lab</button>
                            </div>
                        </form>
                    </div>

                    <!-- ======================= -->
                    <!-- FORM 2: COURSE DETAILS -->
                    <!-- ======================= -->
                    <div class="col-12 col-md-6 ">
                        <form id="CourseForm" method="POST" action="actions/course_action.php" class="p-3 border rounded bg-light shadow-sm">
                            <h5 class="mb-3 ">Course Details</h5>

                            <input type="hidden" name="id" value="<?php echo isset($id_course) ? $id_course : '' ?>">

                            <div class="mb-3">
                                <label class="form-label">Course Name</label>
                                <input required type="text" name="course_name" value="<?php echo isset($course_name) ? $course_name : '' ?>" class="form-control">
                                <input required type="hidden" name="userid" value="<?php echo isset($userid) ? $userid : $userid ?>" class="form-control">
                            </div>


                            <div class="text-end">
                                <button type="submit" class="btn text-white btn-success">Save / Update Course</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class=" mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Labs</h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" style="padding-right: 200px" id="searchBox" name="search" placeholder="Search..">

                            <select name="reporttype" id="reporttype" class="form-select">
                                <option value="">All</option>
                                <option value="">Labs</option>
                                <option value="">Courses</option>
                            </select>
                        </form>
                    </div>
                    <div class="table-responsive" style="height: 300px">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Number of Computer</th>
                                    <th>DateCreated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="lab_table">
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
            function load_lab(search = '') {
                $.ajax({
                    url: "actions/fetch_lab.php",
                    type: "POST",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        $("#lab_table").html(data);
                    }
                });
            }

            // Load on page start
            load_lab();

            // Search computer
            $("#searchBox").on("keyup", function() {
                let search = $(this).val();
                load_lab(search);
            });
        });
    </script>


    <?php
    $title = "Lab";
    successAlert($title);
    ?>
</body>

</html>