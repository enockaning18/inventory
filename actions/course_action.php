<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id  = mysqli_real_escape_string($conn, $_POST['id']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);

    // check whether course id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE course SET course_name = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("si", $course_name, $id);

            if ($stmt->execute()) {
                header("Location: ../labs.php?status=update_course");
                exit();
            } else {
                header("Location: ../labs.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../courses.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO course (course_name) 
                VALUES (?)");

        if ($stmt) {
            $stmt->bind_param("s", $course_name);

            if ($stmt->execute()) {
                header("Location: ../labs.php?status=save_course");
                exit();
            } else {
                header("Location: ../labs.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../lab.php?status=error");
            exit();
        }
    }
}
