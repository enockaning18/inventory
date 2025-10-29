<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id               = mysqli_real_escape_string($conn, $_POST['id']);
    $lab_name         = mysqli_real_escape_string($conn, $_POST['lab_name']);
    $course_id        = mysqli_real_escape_string($conn, $_POST['course_id']);
    $number_computers = mysqli_real_escape_string($conn, $_POST['number_computers']);

    // Check if lab name already exists for the same course
    $check_sql = "SELECT id FROM lab WHERE lab_name = ? AND course_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $lab_name, $course_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    // If we're updating, ignore the current record when checking
    $duplicate_found = false;
    while ($row = $check_result->fetch_assoc()) {
        if (empty($id) || $row['id'] != $id) {
            $duplicate_found = true;
            break;
        }
    }
    $check_stmt->close();

    if ($duplicate_found) {
        // Duplicate lab name and course combination found
        header("Location: ../labs.php?status=duplicate");
        exit();
    }

    // If record exists (update)
    if (!empty($id)) {
        $stmt = $conn->prepare("UPDATE lab SET lab_name = ?, course_id = ?, number_computers = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("sisi", $lab_name, $course_id, $number_computers, $id);
            if ($stmt->execute()) {
                header("Location: ../labs.php?status=update");
                exit();
            } else {
                header("Location: ../labs.php?status=error");
                exit();
            }
            $stmt->close();
        } else {
            header("Location: ../labs.php?status=error");
            exit();
        }
    } 
    // Otherwise insert new record
    else {
        $stmt = $conn->prepare("INSERT INTO lab (lab_name, course_id, number_computers) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sis", $lab_name, $course_id, $number_computers);
            if ($stmt->execute()) {
                header("Location: ../labs.php?status=save");
                exit();
            } else {
                header("Location: ../labs.php?status=error");
                exit();
            }
            $stmt->close();
        } else {
            header("Location: ../labs.php?status=error");
            exit();
        }
    }
}
?>
