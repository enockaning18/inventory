<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = mysqli_real_escape_string($conn, $_POST['id']);
    $examination_date = mysqli_real_escape_string($conn, $_POST['examination_date']);
    $batch_time       = mysqli_real_escape_string($conn, $_POST['batch_time']);
    $session          = mysqli_real_escape_string($conn, $_POST['session']);
    $course_id        = mysqli_real_escape_string($conn, $_POST['course_id']);
    $date_booked      = mysqli_real_escape_string($conn, $_POST['date_booked']);
    $start_time       = mysqli_real_escape_string($conn, $_POST['start_time']);
    $module_id        = mysqli_real_escape_string($conn, $_POST['module_id']);
    $instructor_id    = mysqli_real_escape_string($conn, $_POST['instructor_id']);
    $batch_semester   = mysqli_real_escape_string($conn, $_POST['batch_semester']);
    $status           = mysqli_real_escape_string($conn, $_POST['status']);

    // check no exams on thursdays
    $dayOfWeek = date('l', strtotime($examination_date));
    if (strtolower($dayOfWeek) === 'thursday') {
        header("Location: ../examination.php?status=not_allowed");
        exit();
    }

    //checking duplicate exams (same date, course, module, and batch_time)
    if (!empty($id)) {
        $dupCheck = $conn->prepare("SELECT id FROM examination 
                                    WHERE examination_date = ? 
                                    AND batch_time = ? 
                                    AND module_id = ? 
                                    AND course_id = ?
                                    AND id != ?");
        $dupCheck->bind_param("sssii", $examination_date, $batch_time, $module_id, $course_id, $id);
    } else {
        $dupCheck = $conn->prepare("SELECT id FROM examination 
                                    WHERE examination_date = ? 
                                    AND batch_time = ? 
                                    AND module_id = ? 
                                    AND course_id = ?");
        $dupCheck->bind_param("sssi", $examination_date, $batch_time, $module_id, $course_id);
    }

    $dupCheck->execute();
    $dupResult = $dupCheck->get_result();

    if ($dupResult && $dupResult->num_rows > 0) {
        // Duplicate found
        header("Location: ../examination.php?status=exists");
        exit();
    }

    $dupCheck->close();

    if (!empty($id)) {
        $stmt = $conn->prepare("UPDATE examination 
                                SET examination_date = ?, batch_time = ?, session = ?, course_id = ?, 
                                    date_booked = ?, start_time = ?, module_id = ?, instructor_id = ?, 
                                    batch_semester = ?, status = ? 
                                WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("sssisssisii", $examination_date, $batch_time, $session, $course_id, 
                                              $date_booked, $start_time, $module_id, $instructor_id, 
                                              $batch_semester, $status, $id);

            if ($stmt->execute()) {
                header("Location: ../examination.php?status=update");
            } else {
                header("Location: ../examination.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../examination.php?status=error");
        }

    } else {
        
        $stmt = $conn->prepare("INSERT INTO examination 
            (examination_date, batch_time, session, course_id, date_booked, start_time, module_id, instructor_id, batch_semester, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sssisssisi", $examination_date, $batch_time, $session, $course_id, 
                                              $date_booked, $start_time, $module_id, $instructor_id, 
                                              $batch_semester, $status);

            if ($stmt->execute()) {
                header("Location: ../examination.php?status=save");
            } else {
                header("Location: ../examination.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../examination.php?status=error");
        }
    }

    exit();
}
?>
