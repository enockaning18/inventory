<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = mysqli_real_escape_string($conn, $_POST['id']);
    $monitor_name   = mysqli_real_escape_string($conn, $_POST['monitor_name']);
    $size           = mysqli_real_escape_string($conn, $_POST['size']);
    $monitor_serial   = mysqli_real_escape_string($conn, $_POST['monitor_serial']);
    $brand     = mysqli_real_escape_string($conn, $_POST['brand']);
    $lab = mysqli_real_escape_string($conn, $_POST['lab']);






    if (!empty($id)) {

        // Check if serial number already exists for another computer
        $check = $conn->prepare("SELECT id FROM `monitor` WHERE serial_number = ? AND id != ?");
        $check->bind_param("si", $serial_number, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../monitors.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE monitor 
                                SET computer_name = ?, brand = ?, serial_number = ?, memory_size = ?, hard_drive_size = ?, lab = ?, 
                                monitor_name = ?, size = ?, monitor_serial = ?, processor = ?, generation = ?, speed = ?, processor_type = ?, monitor_brand = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssssssssssssi", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab, $monitor, $size, $monitor_serial, $processor, $generation, $speed, $processor_type, $monitor_brand, $id);
            if ($stmt->execute()) {
                header("Location: ../monitors.php?status=update");
            } else {
                header("Location: ../monitors.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../monitors.php?status=error");
        }
    } else {

        $check = $conn->prepare("SELECT id FROM `monitor` WHERE monitor_serial = ?");
        $check->bind_param("s", $monitor_serial);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../monitors.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO `monitor`(monitor_name, `size`, monitor_serial, brand, lab)
                                VALUES (?,?,?,?,?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $monitor_name, $size, $monitor_serial, $brand, $lab);
            if ($stmt->execute()) {
                header("Location: ../monitors.php?status=save");
            } else {
                header("Location: ../monitors.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../monitors.php?status=error");
        }
    }

    exit();
}
