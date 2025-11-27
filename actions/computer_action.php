<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = mysqli_real_escape_string($conn, $_POST['id']);
    $computer_name   = mysqli_real_escape_string($conn, $_POST['computer_name']);
    $brand           = mysqli_real_escape_string($conn, $_POST['brand']);
    $serial_number   = mysqli_real_escape_string($conn, $_POST['serial_number']);
    $memory_size     = mysqli_real_escape_string($conn, $_POST['memory_size']);
    $hard_drive_size = mysqli_real_escape_string($conn, $_POST['hard_drive_size']);
    $lab             = mysqli_real_escape_string($conn, $_POST['lab']);
    $monitor         = mysqli_real_escape_string($conn, $_POST['monitor']);
    $size            = mysqli_real_escape_string($conn, $_POST['size']);
    $monitor_serial  = mysqli_real_escape_string($conn, $_POST['monitor_serial']);
    $date_added      = mysqli_real_escape_string($conn, $_POST['date_added']);
    $processor      = mysqli_real_escape_string($conn, $_POST['processor']);
    $generation      = mysqli_real_escape_string($conn, $_POST['generation']);
    $speed      = mysqli_real_escape_string($conn, $_POST['speed']);
    $processor_type      = mysqli_real_escape_string($conn, $_POST['processor_type']);
    $monitor_brand      = mysqli_real_escape_string($conn, $_POST['monitor_brand']);

    if (!empty($id)) {

        // Check if serial number already exists for another computer
        $check = $conn->prepare("SELECT id FROM computers WHERE serial_number = ? AND id != ?");
        $check->bind_param("si", $serial_number, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../computers.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE computers 
                                SET computer_name = ?, brand = ?, serial_number = ?, memory_size = ?, hard_drive_size = ?, lab = ?, 
                                monitor_name = ?, size = ?, monitor_serial = ?, processor = ?, generation = ?, speed = ?, processor_type = ?, monitor_brand = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssssssssssssi", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab, $monitor, $size, $monitor_serial, $processor, $generation, $speed, $processor_type, $monitor_brand, $id);
            if ($stmt->execute()) {
                header("Location: ../computers.php?status=update");
            } else {
                header("Location: ../computers.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../computers.php?status=error");
        }

    } else {
        
        $check = $conn->prepare("SELECT id FROM computers WHERE serial_number = ?");
        $check->bind_param("s", $serial_number);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../computers.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO computers (computer_name, brand, serial_number, memory_size, hard_drive_size, lab, monitor_name, size, monitor_serial, processor, generation, speed, processor_type, monitor_brand)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssssssssssss", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab, $monitor, $size, $monitor_serial, $processor, $generation, $speed, $processor_type, $monitor_brand);
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
?>
