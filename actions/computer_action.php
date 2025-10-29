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
    $date_added      = mysqli_real_escape_string($conn, $_POST['date_added']);

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
                                SET computer_name = ?, brand = ?, serial_number = ?, memory_size = ?, hard_drive_size = ?, lab = ? 
                                WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssssi", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab, $id);
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

        $stmt = $conn->prepare("INSERT INTO computers (computer_name, brand, serial_number, memory_size, hard_drive_size, lab)
                                VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssss", $computer_name, $brand, $serial_number, $memory_size, $hard_drive_size, $lab);
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
