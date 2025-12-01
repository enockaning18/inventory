<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id                = mysqli_real_escape_string($conn, $_POST['id']);
    $system_name       = mysqli_real_escape_string($conn, $_POST['system_name']);
    $brand             = mysqli_real_escape_string($conn, $_POST['brand']);
    $serial_number     = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $memory_size       = mysqli_real_escape_string($conn, $_POST['memory_size']);
    $hard_drive_size   = mysqli_real_escape_string($conn, $_POST['hard_drive_size']);
    $processor_type    = mysqli_real_escape_string($conn, $_POST['processor_type']);
    $iseries           = mysqli_real_escape_string($conn, $_POST['iseries']);
    $speed             = mysqli_real_escape_string($conn, $_POST['speed']);
    $generation        = mysqli_real_escape_string($conn, $_POST['generation']);
    $lab               = mysqli_real_escape_string($conn, $_POST['lab']);

    // =============================
    // UPDATE EXISTING SYSTEM
    // =============================

    if (!empty($id)) {

        // Prevent duplicate serial numbers
        $check = $conn->prepare("SELECT id FROM `system` WHERE serial_number = ? AND id != ?");
        $check->bind_param("si", $serial_number, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../system.php?status=exists");
            exit();
        }
        $check->close();


        $stmt = $conn->prepare("UPDATE system 
                                SET system_name = ?,
                                    brand = ?,
                                    serial_number = ?,
                                    memory_size = ?,
                                    hard_drive_size = ?,
                                    processor_type = ?,
                                    generation = ?,
                                    iseries = ?,
                                    speed = ?,
                                    lab = ?
                                WHERE id = ?");

        if ($stmt) {

            // s i s s s s s s s i i
            $stmt->bind_param(
                "sissssssssi",
                $system_name,
                $brand,
                $serial_number,
                $memory_size,
                $hard_drive_size,
                $processor_type,
                $generation,
                $iseries,
                $speed,
                $lab,
                $id
            );

            if ($stmt->execute()) {
                header("Location: ../system.php?status=update");
            } else {
                header("Location: ../system.php?status=error");
            }

            $stmt->close();
        } else {
            header("Location: ../system.php?status=error");
        }
    }

    // =============================
    // INSERT NEW SYSTEM
    // =============================

    else {

        // Prevent duplicate serial numbers
        $check = $conn->prepare("SELECT id FROM `system` WHERE serial_number = ?");
        $check->bind_param("s", $serial_number);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../system.php?status=exists");
            exit();
        }
        $check->close();


        $stmt = $conn->prepare("INSERT INTO `system` 
                (system_name, brand, serial_number, memory_size, hard_drive_size, processor_type, generation, iseries, speed, lab)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt) {

            // s i s s s s s s s i
            $stmt->bind_param(
                "sisssssssi",
                $system_name,
                $brand,
                $serial_number,
                $memory_size,
                $hard_drive_size,
                $processor_type,
                $generation,
                $iseries,
                $speed,
                $lab
            );

            if ($stmt->execute()) {
                header("Location: ../system.php?status=save");
            } else {
                header("Location: ../system.php?status=error");
            }

            $stmt->close();
        } else {
            header("Location: ../system.php?status=error");
        }
    }

    exit();
}
