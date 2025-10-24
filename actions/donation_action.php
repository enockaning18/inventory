<?php

// require your database connection
require_once "../../baseConnect/dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $computer_name    = mysqli_real_escape_string($conn, $_POST['firstname']);
    $brand     = mysqli_real_escape_string($conn, $_POST['lastname']);
    $serial_number        = mysqli_real_escape_string($conn, $_POST['phone']);
    $memory_size      = mysqli_real_escape_string($conn, $_POST['memory_size']);
    $hard_drive_size       = mysqli_real_escape_string($conn, $_POST['hard_drive_size']);
    $lab      = mysqli_real_escape_string($conn, $_POST['lab']);
    $date_added      = mysqli_real_escape_string($conn, $_POST['date_added']);


    // check whether computer id exists
    if ($id != "") {

        // update the record
        $stmt = $conn->prepare("UPDATE computer SET computer_name = ?, brand = ?, serial_number = ?, memory_size = ?, hard_drive_size = ?, lab = ? WHERE computer_id = ?");

        if ($stmt) {
            $stmt->bind_param("ssssssi", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab, $id);

            if ($stmt->execute()) {
                header("Location: ../computers.php?status=update");
                exit();
            } else {
                header("Location: ../computers.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../computers.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO computer (computer_name, brand, serial_number, memory_size, hard_drive_size, lab) 
                VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssssss", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab);

            if ($stmt->execute()) {
                header("Location: ../computers.php?status=save");
                exit();
            } else {
                header("Location: ../computers.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../computers.php?status=error");
            exit();
        }
    }
}


