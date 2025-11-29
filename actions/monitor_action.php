<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id             = (int)$_POST['id'];
    $monitor        = mysqli_real_escape_string($conn, $_POST['monitor']);
    $size           = mysqli_real_escape_string($conn, $_POST['size']);
    $monitor_serial = mysqli_real_escape_string($conn, $_POST['monitor_serial']);
    $brand          = (int)$_POST['brand'];
    $lab            = (int)$_POST['lab'];  

    // -------- UPDATE --------
    if (!empty($id)) {

        // Check if serial exists for another monitor
        $check = $conn->prepare("SELECT id FROM monitor WHERE monitor_serial = ? AND id != ?");
        $check->bind_param("si", $monitor_serial, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../computers.php?status=exists");
            exit();
        }
        $check->close();

        // UPDATE monitor
        $stmt = $conn->prepare("
            UPDATE monitor 
            SET monitor_name = ?, size = ?, monitor_serial = ?, brand = ?, lab = ?
            WHERE id = ?
        ");

        if ($stmt) {
            $stmt->bind_param("sssiii", $monitor, $size, $monitor_serial, $brand, $lab, $id);

            if ($stmt->execute()) {
                header("Location: ../computers.php?status=update");
            } else {
                header("Location: ../computers.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../computers.php?status=error");
        }

    } 
    // -------- INSERT --------
    else {

        // Check if serial number exists
        $check = $conn->prepare("SELECT id FROM monitor WHERE monitor_serial = ?");
        $check->bind_param("s", $monitor_serial);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../computers.php?status=exists");
            exit();
        }
        $check->close();

        // INSERT new monitor
        $stmt = $conn->prepare("
            INSERT INTO monitor (monitor_name, size, monitor_serial, brand, lab)
            VALUES (?, ?, ?, ?, ?)
        ");

        if ($stmt) {
            $stmt->bind_param("sssii", $monitor, $size, $monitor_serial, $brand, $lab);

            if ($stmt->execute()) {
                header("Location: ../computers.php?status=save");
            } else {
                header("Location: ../computers.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../computers.php?status=error");
        }
    }

    exit();
}
