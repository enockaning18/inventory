<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $lab_name  = mysqli_real_escape_string($conn, $_POST['lab_name']);
    $lab_course  = mysqli_real_escape_string($conn, $_POST['lab_course']);
    $instructor     = mysqli_real_escape_string($conn, $_POST['instructor']);
    $number_computers        = mysqli_real_escape_string($conn, $_POST['number_computers']);



    // check whether lab id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE lab SET lab_name = ?,  lab_course = ?, instructor = ?, number_computers = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("ssisi",$lab_name, $lab_course, $instructor, $number_computers, $id);

            if ($stmt->execute()) {
                header("Location: ../labs.php?status=update");
                exit();
            } else {
                header("Location: ../labs.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../labs.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO lab (lab_name, lab_course, instructor, number_computers) 
                VALUES (?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssis", $lab_name, $lab_course, $instructor, $number_computers);

            if ($stmt->execute()) {
                header("Location: ../labs.php?status=save");
                exit();
            } else {
                header("Location: ../labs.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../labs.php?status=error");
            exit();
        }
    }
}
