<?php
require_once('actions/start_session.php');
require_once('alert.php');
require_once('baseConnect/dbConnect.php');

// fetch instructor name
if (!isset($_SESSION['instructorid'])) {
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

// Initialize form variables
$id = $examination_date = $batch_time = $session = $course_id = $date_booked = $start_time = $module_id = $batch_semester = $instructor_id = $instructor_name = $status = "";


// Edit mode - fetch exam details
if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);

    $query = "
        SELECT 
            e.id, e.examination_date, e.batch_time, e.session,
            e.course_id, e.date_booked, e.start_time, 
            e.module_id, e.batch_semester, e.instructor_id,
            m.name AS module_name, c.course_name, CONCAT(i.first_name,' ',i.last_name) 
            AS instructor_name
        FROM examination e
        INNER JOIN module m ON m.id = e.module_id
        INNER JOIN course c ON c.id = e.course_id
        INNER JOIN instructors i ON i.id = e.instructor_id
        WHERE e.id = ?
    ";

    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $id               = $row['id'];
        $examination_date = $row['examination_date'];
        $batch_time       = $row['batch_time'];
        $session          = $row['session'];
        $course_id        = $row['course_id'];
        $date_booked      = $row['date_booked'];
        $start_time       = $row['start_time'];
        $module_id        = $row['module_id'];
        $batch_semester   = $row['batch_semester'];
        $instructor_id    = $row['instructor_id'];
        $instructor_name  = $row['instructor_name'];
    }

    $stmt->close();
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
                Exams Booking
            </h3>
            <div>
                <a href="approved_exams.php"><button class="btn text-white px-2" style="background-color:green;">Approved</button></a>
                <a href="pending_exams.php"><button class="btn text-white px-2" style="background-color:gold;">Pending</button></a>
                <a href="cancelled_exams.php"><button class="btn text-white px-2" style="background-color:red;">Cancelled</button></a>
            </div>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Exams</button>
        </div>
        <hr class="mb-4">

        <!-- Exam Form -->
        <form class="row g-3 border rounded bg-light shadow-sm p-4" id="Form" method="POST" action="actions/examination_action.php">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <input type="hidden" name="instructor_id" value="<?= htmlspecialchars($instid) ?>">
            <input type="hidden" name="status" value="2">

            <div class="col-md-4">
                <label class="form-label">Examination Date</label>
                <input required type="date" name="examination_date" id="exam_date" value="<?php echo isset($examination_date) ? htmlspecialchars($examination_date) : '' ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Course</label>
                <select required id="course" name="course_id" class="form-select">
                    <option value="">Select Course</option>
                    <?php
                    $courses = $conn->query("SELECT * FROM course");
                    while ($course = $courses->fetch_assoc()) {
                        $selected = ($course_id == $course['id']) ? 'selected' : '';
                        echo "<option value='{$course['id']}' $selected>" . htmlspecialchars($course['course_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Batch Semester</label>
                <select required id="batch_semester" name="batch_semester" class="form-select">
                    <option value="">Choose Semester</option>
                    <?php
                    $semesters = ["Sem-1" => "Semester 1", "Sem-2" => "Semester 2", "Sem-3" => "Semester 3", "Sem-4" => "Semester 4", "Year-1" => "One Year", "Short-Course" => "Short Course"];
                    foreach ($semesters as $key => $val) {
                        $selected = ($batch_semester == $key) ? 'selected' : '';
                        echo "<option value='$key' $selected>$val</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Module</label>
                <select required id="module" name="module_id" class="form-select">
                    <option value="">Select Module</option>
                    <?php
                    $modules = $conn->query("SELECT * FROM module");
                    while ($module = $modules->fetch_assoc()) {
                        $selected = ($module_id == $module['id']) ? 'selected' : '';
                        echo "<option value='{$module['id']}' $selected>" . htmlspecialchars($module['name']) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Batch Time</label>
                <select required name="batch_time" class="form-select">
                    <option value="">Choose Batch</option>
                    <?php
                    $batches = ["7am - 9am", "9am - 11am", "11am - 1pm", "1pm - 3pm", "3pm - 5pm"];
                    foreach ($batches as $batch) {
                        $selected = ($batch_time == $batch) ? 'selected' : '';
                        echo "<option value='$batch' $selected>$batch</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Session</label>
                <select required name="session" class="form-select">
                    <option value="">Choose Session</option>
                    <option value="Weekday" <?= ($session == "Weekday") ? 'selected' : '' ?>>Weekday</option>
                    <option value="Weekend" <?= ($session == "Weekend") ? 'selected' : '' ?>>Weekend</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Date Booked</label>
                <input required type="date" name="date_booked" value="<?php echo isset($date_booked) ? htmlspecialchars($date_booked) : '' ?>" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Start Time</label>
                <input required type="time" name="start_time" value="<?php echo isset($start_time) ? htmlspecialchars($start_time) : '' ?>" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">Booking By</label>
                <input type="text" name="instructor" class="form-control" value="<?php echo isset($inst_name) ? htmlspecialchars($inst_name) : htmlspecialchars($inst_name) ?>" readonly>
            </div>

        </form>
        <hr class="mb-5">
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
