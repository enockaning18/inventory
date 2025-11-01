<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');

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
    <title>Examination - IPMC INVENTORY MANAGER</title>
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
                Examinations
            </h3>
            <div>
                <a href="approved_exams.php"><button class="btn text-white px-2" style="background-color:green;">Approved</button></a>
                <a href="pending_exams.php"><button class="btn text-white px-2" style="background-color:gold;">Pending</button></a>
                <a href="cancelled_exams.php"><button class="btn text-white px-2" style="background-color:red;">Cancelled</button></a>
            </div>
            <a href="examination.php"><button class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Book Examination</button></a>
        </div>
        <hr class="mb-4">
    </div>

    <!-- Exams Table (unchanged) -->
    <div class="mt-5 mx-auto" style="width: 95%">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 px-4 py-3">
                        <h5 class="mb-0" style="color: maroon;">List of Exams</h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" id="searchBox" name="search" placeholder="Search..">

                            <select required id="module_filter" name="module" class="form-select" data-selected="<?= isset($module_id) ? $module_id : '' ?>">
                                <option value="">Select Module</option>
                                <?php
                                $modules2 = $conn->query("SELECT * FROM module");
                                while ($module2 = $modules2->fetch_assoc()) {
                                    $selected = ($module_id == $module2['id']) ? 'selected' : '';
                                    echo "<option value='{$module2['id']}' $selected>" . htmlspecialchars($module2['name']) . "</option>";
                                }
                                ?>
                            </select>

                            <?php
                            $query_command = "SELECT * FROM course ";
                            $result = $conn->query($query_command);
                            ?>
                            <select required id="course_filter" name="course" class="form-select">
                                <option value="">All Course</option>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo (isset($course_id) && $course_id ==  $row['id']) ? 'selected' : '' ?>><?php echo htmlspecialchars($row['course_name']) ?></option>
                                <?php } ?>
                            </select>

                            <select name="semester" id="semester" class="form-select">
                                <option value="">All Semesters</option>
                                <?php
                                $semesters = ["Sem-1" => "Semester 1", "Sem-2" => "Semester 2", "Sem-3" => "Semester 3", "Sem-4" => "Semester 4"];
                                foreach ($semesters as $key => $val) {
                                    $selected = ($batch_semester == $key) ? 'selected' : '';
                                    echo "<option value='$key' $selected>$val</option>";
                                }
                                ?>

                            </select>

                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="Approve">Approve</option>
                                <option value="Pending">Pending</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </form>

                    </div>
                    <div class="table-responsive" style="height: 300px;">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>ExamsDate</th>
                                    <th>Course</th>
                                    <th>Module</th>
                                    <th>BatchTime</th>
                                    <th>Session</th>
                                    <th>StartTime</th>
                                    <th>Semester</th>
                                    <th>Instructor</th>
                                    <th>Status</th>
                                    <th>DateBooked</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="examination_table">
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

    <script src="assets/js/fetch_data_helper.js"></script>

    <!-- JS -->
    <script>
        $(document).ready(function() {
            function load_examination() {
                const filters = {
                    search: $("#searchBox").val(),
                    module: $("#module_filter").val(),
                    course: $("#course_filter").val(),
                    semester: $("#semester").val(),
                    status: $("#status").val()
                };

                $.ajax({
                    url: "actions/fetch_examination.php",
                    type: "POST",
                    data: filters,
                    success: function(data) {
                        $("#examination_table").html(data);
                    },
                    error: function() {
                        $("#examination_table").html("<tr><td colspan='12' class='text-center text-danger'>Error loading data</td></tr>");
                    }
                });
            }

            // Initial load
            load_examination();

            // Reload on any change
            $("#filterForm select, #searchBox").on("change keyup", load_examination);
        });
    </script>


    <script src="assets/js/main.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

    <?php
    $title = "Examination";
    successAlert($title);
    ?>

    <script>
        // Set min date for exam_date to today (prevents picks in past)
        document.addEventListener('DOMContentLoaded', function() {
            const d = new Date();
            d.setMinutes(d.getMinutes() - d.getTimezoneOffset()); // local date normalization
            const today = d.toISOString().split('T')[0];
            const examDate = document.getElementById('exam_date');
            if (examDate) examDate.min = today;
        });
    </script>

</body>

</html>
