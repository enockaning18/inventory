\<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize inputs
    $id               = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $examination_date = isset($_POST['examination_date']) ? trim($_POST['examination_date']) : '';
    $batch_time       = isset($_POST['batch_time']) ? trim($_POST['batch_time']) : '';
    $session          = isset($_POST['session']) ? trim($_POST['session']) : '';
    $course_id        = isset($_POST['course_id']) ? (int) $_POST['course_id'] : 0;
    $date_booked      = isset($_POST['date_booked']) ? trim($_POST['date_booked']) : '';
    $start_time       = isset($_POST['start_time']) ? trim($_POST['start_time']) : '';
    $module_id        = isset($_POST['module_id']) ? (int) $_POST['module_id'] : 0;
    $instructor_id    = isset($_POST['instructor_id']) ? (int) $_POST['instructor_id'] : 0;
    $batch_semester   = isset($_POST['batch_semester']) ? trim($_POST['batch_semester']) : '';
    $status           = isset($_POST['status']) ? trim($_POST['status']) : '';

    // --- Prevent exams on Thursdays ---
    if (!empty($examination_date)) {
        $dayOfWeek = date('l', strtotime($examination_date));
        if (strtolower($dayOfWeek) === 'thursday') {
            header("Location: ../examination.php?status=not_allowed");
            exit();
        }
    }

    // --- Prevent past exam dates ---
    if (!empty($examination_date) && strtotime($examination_date) < strtotime(date('Y-m-d'))) {
        header("Location: ../examination.php?status=past_date");
        exit();
    }

    // --- Prevent duplicate date + batch ---
    if ($id > 0) {
        $checkDateBatch = $conn->prepare("SELECT id FROM examination WHERE examination_date = ? AND batch_time = ? AND id != ?");
        $checkDateBatch->bind_param("ssi", $examination_date, $batch_time, $id);
    } else {
        $checkDateBatch = $conn->prepare("SELECT id FROM examination WHERE examination_date = ? AND batch_time = ?");
        $checkDateBatch->bind_param("ss", $examination_date, $batch_time);
    }
    $checkDateBatch->execute();
    $resultDateBatch = $checkDateBatch->get_result();
    if ($resultDateBatch && $resultDateBatch->num_rows > 0) {
        $checkDateBatch->close();
        header("Location: ../examination.php?status=date_time_exists");
        exit();
    }
    $checkDateBatch->close();

    // --- Prevent duplicate (course + module + date + batch) ---
    if ($id > 0) {
        $dupCheck = $conn->prepare("SELECT id FROM examination 
                                    WHERE examination_date = ? AND batch_time = ? 
                                    AND module_id = ? AND course_id = ? AND id != ?");
        $dupCheck->bind_param("ssiii", $examination_date, $batch_time, $module_id, $course_id, $id);
    } else {
        $dupCheck = $conn->prepare("SELECT id FROM examination 
                                    WHERE examination_date = ? AND batch_time = ? 
                                    AND module_id = ? AND course_id = ?");
        $dupCheck->bind_param("ssii", $examination_date, $batch_time, $module_id, $course_id);
    }
    $dupCheck->execute();
    $dupResult = $dupCheck->get_result();
    if ($dupResult && $dupResult->num_rows > 0) {
        $dupCheck->close();
        header("Location: ../examination.php?status=exists");
        exit();
    }
    $dupCheck->close();

    // --- Insert or Update ---
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE examination 
                                SET examination_date = ?, batch_time = ?, session = ?, course_id = ?, 
                                    date_booked = ?, start_time = ?, module_id = ?, instructor_id = ?, 
                                    batch_semester = ?, status = ? 
                                WHERE id = ?");
        $stmt->bind_param("sssisssisii", 
            $examination_date, $batch_time, $session, $course_id, 
            $date_booked, $start_time, $module_id, $instructor_id, 
            $batch_semester, $status, $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ../examination.php?status=update");
            exit();
        } else {
            $stmt->close();
            header("Location: ../examination.php?status=error");
            exit();
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO examination 
            (examination_date, batch_time, session, course_id, date_booked, start_time, module_id, instructor_id, batch_semester, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssisssisi", 
            $examination_date, $batch_time, $session, $course_id, 
            $date_booked, $start_time, $module_id, $instructor_id, 
            $batch_semester, $status
        );

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ../examination.php?status=save");
            exit();
        } else {
            $stmt->close();
            header("Location: ../examination.php?status=error");
            exit();
        }
    }
}
?>
