<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $module_name  = mysqli_real_escape_string($conn, trim($_POST['module_name']));

    if (!empty($id)) {

        // Check if another module already has the same name
        $check = $conn->prepare("SELECT id FROM module WHERE name = ? AND id != ?");
        $check->bind_param("si", $module_name, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../modules.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE module SET name = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $module_name, $id);
            if ($stmt->execute()) {
                header("Location: ../modules.php?status=update");
            } else {
                header("Location: ../modules.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../modules.php?status=error");
        }

    } else {
        
        // Check if module already exists
        $check = $conn->prepare("SELECT id FROM module WHERE name = ?");
        $check->bind_param("s", $module_name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../modules.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO module (name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $module_name);
            if ($stmt->execute()) {
                header("Location: ../modules.php?status=save");
            } else {
                header("Location: ../modules.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../modules.php?status=error");
        }
    }

    exit();
}
?>
