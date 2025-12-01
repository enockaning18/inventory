<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id              = mysqli_real_escape_string($conn, $_POST['id']);
    $monitor_name    = mysqli_real_escape_string($conn, $_POST['monitor_name']);
    $size            = mysqli_real_escape_string($conn, $_POST['size']);
    $monitor_serial  = mysqli_real_escape_string($conn, $_POST['monitor_serial']);
    $brand           = mysqli_real_escape_string($conn, $_POST['brand']);
    $lab             = mysqli_real_escape_string($conn, $_POST['lab']);

    // ========================================================
    // UPDATE MONITOR
    // ========================================================
    if (!empty($id)) {

        // Check duplicate serial
        $check = $conn->prepare("SELECT id FROM monitor WHERE monitor_serial = ? AND id != ?");
        $check->bind_param("si", $monitor_serial, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../monitors.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE monitor 
                                SET monitor_name = ?, 
                                    size = ?, 
                                    monitor_serial = ?, 
                                    brand = ?, 
                                    lab = ?
                                WHERE id = ?");

        if ($stmt) {
            // monitor_name, size, serial = string | brand, lab, id = int
            $stmt->bind_param("sssiii", $monitor_name, $size, $monitor_serial, $brand, $lab, $id);

            if ($stmt->execute()) {
                header("Location: ../monitors.php?status=update");
            } else {
                header("Location: ../monitors.php?status=error");
            }

            $stmt->close();
        } else {
            header("Location: ../monitors.php?status=error");
        }

        exit();
    }

    // ========================================================
    // INSERT NEW MONITOR
    // ========================================================

    // Check duplicate serial
    $check = $conn->prepare("SELECT id FROM monitor WHERE monitor_serial = ?");
    $check->bind_param("s", $monitor_serial);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        header("Location: ../monitors.php?status=exists");
        exit();
    }
    $check->close();

    $stmt = $conn->prepare("INSERT INTO monitor 
                            (monitor_name, size, monitor_serial, brand, lab)
                            VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {

        $stmt->bind_param("sssii", $monitor_name, $size, $monitor_serial, $brand, $lab);

        if ($stmt->execute()) {
            header("Location: ../monitors.php?status=save");
        } else {
            header("Location: ../monitors.php?status=error");
        }

        $stmt->close();
    } else {
        header("Location: ../monitors.php?status=error");
    }

    exit();
}
?>
