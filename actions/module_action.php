<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id  = mysqli_real_escape_string($conn, $_POST['id']);
    $module_name = mysqli_real_escape_string($conn, $_POST['module_name']);

    // check whether module id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE module SET name = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("si", $module_name, $id);

            if ($stmt->execute()) {
                header("Location: ../modules.php?status=update");
                exit();
            } else {
                header("Location: ../modules.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            
            header("Location: ../modules.php?status=error");
            exit();
        }
    } else {

        $stmt = $conn->prepare("INSERT INTO module (name) 
                VALUES (?)");

        if ($stmt) {
            $stmt->bind_param("s", $module_name);

            if ($stmt->execute()) {
                header("Location: ../modules.php?status=save");
                exit();
            } else {
                header("Location: ../modules.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            
            header("Location: ../modules.php?status=error");
            exit();
        }
    }
}
