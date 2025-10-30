<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id          = mysqli_real_escape_string($conn, $_POST['id']);
    $first_name  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name   = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone       = mysqli_real_escape_string($conn, $_POST['phone']);
    $email       = mysqli_real_escape_string($conn, $_POST['email']);
    $lab_id      = mysqli_real_escape_string($conn, $_POST['lab_id']);
    $course_id   = mysqli_real_escape_string($conn, $_POST['course_id']);

    if (!empty($id)) {

        // Check if instructor already exists for another instructor
        $check = $conn->prepare("SELECT id FROM instructors WHERE email = ? AND phone = ? AND id != ?");
        $check->bind_param("ssi", $email, $phone, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Email belongs to another instructor
            header("Location: ../instructors.php?status=instructor_exist");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE instructors 
                                SET first_name = ?, last_name = ?, lab_id = ?, phone = ?, email = ?, course_id = ? 
                                WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssisssi", $first_name, $last_name, $lab_id, $phone, $email, $course_id, $id);
            if ($stmt->execute()) {
                header("Location: ../instructors.php?status=update");
            } else {
                header("Location: ../instructors.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../instructors.php?status=error");
        }

    } else {
        
        $check = $conn->prepare("SELECT id FROM instructors WHERE email = ? AND phone = ? AND id != ?");
        $check->bind_param("ssi", $email, $phone, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            // Email belongs to another instructor
            header("Location: ../instructors.php?status=exist");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO instructors (first_name, last_name, phone, email, course_id, lab_id)
                                VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssi", $first_name, $last_name, $phone, $email, $course_id, $lab_id);
            if ($stmt->execute()) {
                header("Location: ../instructors.php?status=save");
            } else {
                header("Location: ../instructors.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../instructors.php?status=error");
        }
    }

    exit();
}
?>
