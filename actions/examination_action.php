<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $examination_date    = mysqli_real_escape_string($conn, $_POST['examination_date']);
    $batch_time     = mysqli_real_escape_string($conn, $_POST['batch_time']);
    $session        = mysqli_real_escape_string($conn, $_POST['session']);
    $course_id      = mysqli_real_escape_string($conn, $_POST['course_id']);
    $date_booked       = mysqli_real_escape_string($conn, $_POST['date_booked']);
    $start_time      = mysqli_real_escape_string($conn, $_POST['start_time']);
    $module_id      = mysqli_real_escape_string($conn, $_POST['module_id']);
    $instructor_id      = mysqli_real_escape_string($conn, $_POST['instructor_id']);
    $batch_semester      = mysqli_real_escape_string($conn, $_POST['batch_semester']);
    $status      = mysqli_real_escape_string($conn, $_POST['status']);
    // $      = mysqli_real_escape_string($conn, $_POST['']);


    // check whether computer id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE examination SET examination_date = ?, batch_time = ?, session = ?, course_id = ?, date_booked = ?, start_time = ?, module_id = ?, batch_semester = ?,  status = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("sssissssii", $examination_date, $batch_time, $session, $course_id, $date_booked, $start_time, $module_id, $batch_semester, $status, $id);

            if ($stmt->execute()) {
                header("Location: ../examination.php?status=update");
                exit();
            } else {
                header("Location: ../examination.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../examination.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO examination (examination_date, batch_time, session, course_id, date_booked, start_time, module_id, instructor_id, batch_semester, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");

        if ($stmt) {
            $stmt->bind_param("sssisssisi", $examination_date, $batch_time, $session, $course_id, $date_booked, $start_time, $module_id, $instructor_id, $batch_semester, $status);

            if ($stmt->execute()) {
                header("Location: ../examination.php?status=save");
                exit();
            } else {
                header("Location: ../examination.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../examination.php?status=error");
            exit();
        }
    }
}
