<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $computer    = mysqli_real_escape_string($conn, $_POST['computers']);
    $issue_type    = mysqli_real_escape_string($conn, $_POST['issue_type']);
    $lab    = mysqli_real_escape_string($conn, $_POST['lab']);
    $issue_date    = mysqli_real_escape_string($conn, $_POST['issue_date']);
    $issue_description    = mysqli_real_escape_string($conn, $_POST['issue_description']);

    // check whether issue id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE issues SET computer = ?, issue_type = ?, lab = ?, issue_date = ?, issue_description = ?  WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("isissi", $computer, $issue_type, $lab, $issue_date, $issue_description, $id);

            if ($stmt->execute()) {
                header("Location: ../issues.php?status=update");
                exit();
            } else {
                header("Location: ../issues.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../issues.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO issues (computer, issue_type, lab, issue_date, issue_description) VALUES (?,?,?,?,?)");

        if ($stmt) {
            $stmt->bind_param("isiss", $computer, $issue_type, $lab, $issue_date, $issue_description);

            if ($stmt->execute()) {
                header("Location: ../issues.php?status=save");
                exit();
            } else {
                header("Location: ../issues.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../issues.php?status=error");
            exit();
        }
    }
}
