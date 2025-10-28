<?php

require_once('alert.php');
require_once('baseConnect/dbConnect.php');


// initialize variables used in the form when edit btn is called

if (isset($_GET['edit_id']) && is_numeric($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT id, examination _name, brand, serial_number, memory_size, hard_drive_size, lab 
                            FROM examination s WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $id = $row['id'];
        $examination_name = $row['examination _name'];
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
    <div class=" mx-auto" style="margin-top: 4rem; width:85%">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="my-auto">
                <ion-icon name="book-outline"></ion-icon>
                Examinations
            </h3>
            <button type="submit" form="Form" class="btn text-white px-4" style="background-color:rgb(200, 72, 105)">Save/Update Examination </button>
        </div>
        <hr style="margin-bottom: 3rem;">
        <div class="g-3" style="margin-bottom: 7rem">
            <form class="row g-3 border rounded bg-light shadow-sm p-3 pb-5" id="Form" method="POST" action="actions/examination_action.php">
                <div class="col-md-4">
                    <label class="form-label">Examination Date </label> 
                    <input type="hidden" value="" name="id">
                    <input required type="date" name="examination_date" value="" class="form-control">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label"> Course</label>
                    <select required id="Type" name="course_id" class="form-select">
                        <option value="none">Choose Course</option>
                        <option value="software">Software</option>
                        <option value="database">Database</option>
                        <option value="graphics">Graphics & Web</option>
                        <option value="workplace">IT @ Workplace</option>
                        <option value="systemengineer">System Engineering</option>
                        <option value="hardware_network">Hardware & Networking</option>
                        <option value="cyber">Cyber Security</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"> Course Module</label>
                    <select required id="Type" name="course_model" class="form-select">
                        <option value="">Choose Module</option>
                        <option value="1">PHP</option>
                        <option value="2">HTML 5</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"> Batch Time</label>
                    <select required id="Type" name="batch_time" class="form-select">
                        <option value="none">Choose Batch</option>
                        <option value="7am - 9am">7am - 9am</option>
                        <option value="9am - 11am">9am - 11am</option>
                        <option value="11am - 1pm">11am - 1pm</option>
                        <option value="1pm - 3pm">1pm - 3pm</option>
                        <option value="3pm - 5pm">3pm - 5pm</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"> Batch Semester </label>
                    <select required id="Type" name="batch_semester" class="form-select">
                        <option value="">Choose Semester</option>
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2">Semester 2</option>
                        <option value="Semester 3">Semester 3</option>
                        <option value="Semester 4">Semester 4</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label"> Session</label>
                    <select required id="Type" name="session" class="form-select">
                        <option value="none">Choose Session</option>
                        <option value="weekday">Weekday</option>
                        <option value="weekend">Weekend</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Start Time </label>
                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Date Booked </label>
                    <input required type="date" name="date_booked" value="" class="form-control">
                </div>                              

                <div class="col-md-4">
                    <label class="form-label"> Exam Lab </label>
                    <select required id="Type" name="lab_id" class="form-select">
                        <option value="none">Choose Lab</option>
                        <option value="1">Lab 1</option>
                        <option value="2">Lab 2</option>
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
                        <h5 class="mb-0" style="color: maroon;">List of Exams </h5>
                        <form id="filterForm" class="d-flex gap-2">
                            <input type="search" class="form-control" id="searchBox" name="search" placeholder="Search ..">

                            <select name="reporttype" id="reporttype" class="form-select">
                                <option value="">Filter Batch</option>
                                <option value=""></option>
                            </select>
                            <select name="reporttype" id="reporttype" class="form-select">
                                <option value="">Filter Course</option>
                                <option value=""></option>
                            </select>
                            <select name="reporttype" id="reporttype" class="form-select">
                                <option value="">Filter Module</option>
                                <option value=""></option>
                            </select>
                        </form>
                    </div>
                    <div class="table-responsive" style="height: 300px">
                        <table class="table table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>ExaminationDate </th>
                                    <th>Batch </th>
                                    <th>Session </th>
                                    <th>Course </th>
                                    <th>DateBooked </th>
                                    <th>StartTime </th>
                                    <th>Module </th>
                                    <th>Semester </th>
                                    <th>Lab</th>
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

    <!-- =========== Scripts =========  -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
        $(document).ready(function() {
            function load_examination(search = '') {
                $.ajax({
                    url: "actions/fetch_examination.php",
                    type: "POST",
                    data: {
                        search: search
                    },
                    success: function(data) {
                        $("#examination_table").html(data);
                    }
                });
            }

            // Load on page start
            load_examination();

            // Search examination 
            $("#searchBox").on("keyup", function() {
                let search = $(this).val();
                load_examination(search);
            });
        });
    </script>


    <?php
    $title = "Examination ";
    successAlert($title);
    ?>
</body>

</html>