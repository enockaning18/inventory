<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = mysqli_real_escape_string($conn, $_POST['id']);
    $system_name   = mysqli_real_escape_string($conn, $_POST['computer_name']);
    $brand           = mysqli_real_escape_string($conn, $_POST['brand']);
    $serial_number   = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $memory_size     = mysqli_real_escape_string($conn, $_POST['memory_size']);
    $hard_drive_size = mysqli_real_escape_string($conn, $_POST['hard_drive_size']);
    $processor_type            = mysqli_real_escape_string($conn, $_POST['processor_type']);
    $iseries  = mysqli_real_escape_string($conn, $_POST['iseries']);
    $speed            = mysqli_real_escape_string($conn, $_POST['speed']);
    $lab            = mysqli_real_escape_string($conn, $_POST['lab']);






    if (!empty($id)) {

        // Check if serial number already exists for another computer
        $check = $conn->prepare("SELECT id FROM `system` WHERE serial_number = ? AND id != ?");
        $check->bind_param("si", $serial_number, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../monitors.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE system 
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

    } 
    // -------- INSERT --------
    else {

        $check = $conn->prepare("SELECT id FROM system WHERE serial_number = ?");
        $check->bind_param("s", $serial_number);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../monitors.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO `system`( system_name, brand, serial_number, memory_size, hard_drive_size, processor_type, iseries, speed, lab)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssssss", $system_name, $brand, $serial_number, $memory_size, $hard_drive_size, $processor_type, $iseries, $speed, $lab);
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
