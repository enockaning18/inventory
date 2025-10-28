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
    $course_model      = mysqli_real_escape_string($conn, $_POST['course_model']);
    $batch_semester      = mysqli_real_escape_string($conn, $_POST['batch_semester']);
    $lab_id      = mysqli_real_escape_string($conn, $_POST['lab_id']);
    // $      = mysqli_real_escape_string($conn, $_POST['']);


    // check whether computer id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE examination SET examination_date = ?, batch_time = ?, session = ?, course_id = ?, date_booked = ?, start_time = ?, course_model = ?, batch_semester = ?,  lab_id = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("sssissssii", $examination_date, $batch_time, $session, $course_id, $date_booked, $start_time, $course_model, $batch_semester, $lab_id, $id);

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
        $stmt = $conn->prepare("INSERT INTO examination (examination_date, batch_time, session, course_id, date_booked, start_time, course_model, batch_semester, lab_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sssissssi", $examination_date, $batch_time, $session, $course_id, $date_booked, $start_time, $course_model, $batch_semester, $lab_id);

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
