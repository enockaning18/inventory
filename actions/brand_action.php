<?php

// require your database connection
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $brand_name    = mysqli_real_escape_string($conn, $_POST['brand_name']);

    // check whether brand id exists
    if (!empty($id)) {

        // update the record
        $stmt = $conn->prepare("UPDATE brand SET brand_name = ? WHERE id = ?");

        if ($stmt) {
            $stmt->bind_param("si", $brand_name, $id);

            if ($stmt->execute()) {
                header("Location: ../brands.php?status=update");
                exit();
            } else {
                header("Location: ../brands.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../brands.php?status=error");
            exit();
        }
    } else {

        // insert a new record
        $stmt = $conn->prepare("INSERT INTO brand (brand_name) 
                VALUES (?)");

        if ($stmt) {
            $stmt->bind_param("s", $brand_name);

            if ($stmt->execute()) {
                header("Location: ../brands.php?status=save");
                exit();
            } else {
                header("Location: ../brands.php?status=error");
                exit();
            }

            $stmt->close();
        } else {
            // SQL error (wrong table/columns)
            header("Location: ../brands.php?status=error");
            exit();
        }
    }
}
