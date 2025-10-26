<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $first_name  = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name     = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone        = mysqli_real_escape_string($conn, $_POST['phone']);
    $email      = mysqli_real_escape_string($conn, $_POST['email']);
    $lab_assigned      = mysqli_real_escape_string($conn, $_POST['lab_assigned']);
    $course      = mysqli_real_escape_string($conn, $_POST['course']);



    // check whether computer id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE instructors SET first_name = ?, last_name = ?, phone = ?, email = ?, lab_assigned = ?, course = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("ssssisi", $first_name, $last_name, $phone, $email, $lab_assigned, $course, $id);

            if ($stmt->execute()) {
                header("Location: ../instructors.php?status=update");
                exit();
            } else {
                header("Location: ../instructors.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../instructors.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO instructors (first_name, last_name, phone, email, lab_assigned, course) 
                VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssssis", $first_name, $last_name, $phone, $email, $lab_assigned, $course);

            if ($stmt->execute()) {
                header("Location: ../instructors.php?status=save");
                exit();
            } else {
                header("Location: ../instructors.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../instructors.php?status=error");
            exit();
        }
    }
}
