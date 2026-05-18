<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {


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
    if (!empty($examination_date) && strtotime($examination_date) < strtotime('today')) {
        header("Location: ../examination.php?status=past_date");
        exit();
    }

    // --- Check for first Monday-Friday of March, June, September, December ---
    if (!empty($examination_date)) {
        $timestamp = strtotime($examination_date);
        $month     = (int)date('n', $timestamp);
        $dayOfMonth= (int)date('j', $timestamp);
        $dayOfWeek = (int)date('N', $timestamp); 

        // Target months: 3 (March), 6 (June), 9 (Sept), 12 (Dec)
        if (in_array($month, [3, 6, 9, 12])) {
            
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5 && $dayOfMonth <= 7) {
                header("Location: ../examination.php?status=acca_inprogress");
                exit();
            }
        }
    } 

    if ($id > 0) {
        $check = $conn->prepare("SELECT id FROM examination WHERE examination_date = ? AND start_time = ? AND id != ?");
        $check->bind_param("ssi", $examination_date, $start_time, $id);
    } else {
        $check = $conn->prepare("SELECT id FROM examination WHERE examination_date = ? AND start_time = ?");
        $check->bind_param("ss", $examination_date, $start_time);
    }

    $check->execute();
    $result = $check->get_result();

    if ($result && $result->num_rows > 0) {
        $check->close();
        header("Location: ../examination.php?status=date_time_exists");
        exit();
    }
    $check->close();

    // --- Limit exams per day to 3 ---
    if ($id > 0) {
        $countCheck = $conn->prepare("SELECT COUNT(*) as exam_count FROM examination WHERE examination_date = ? AND id != ?");
        $countCheck->bind_param("si", $examination_date, $id);
    } else {
        $countCheck = $conn->prepare("SELECT COUNT(*) as exam_count FROM examination WHERE examination_date = ?");
        $countCheck->bind_param("s", $examination_date);
    }
    
    $countCheck->execute();
    $countResult = $countCheck->get_result();
    $countRow = $countResult->fetch_assoc();
    $exam_count = $countRow['exam_count'];
    $countCheck->close();

    if ($exam_count >= 3) {
        header("Location: ../examination.php?status=daily_limit_reached");
        exit();
    }

    // --- Insert or Update ---
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE examination 
                                SET examination_date = ?, batch_time = ?, session = ?, course_id = ?, 
                                    date_booked = ?, start_time = ?, module_id = ?, instructor_id = ?, 
                                    batch_semester = ?, status = ? 
                                WHERE id = ?");
        $stmt->bind_param("sssisssissi", 
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
        $stmt->bind_param("sssisssiss", 
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