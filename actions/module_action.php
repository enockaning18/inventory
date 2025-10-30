<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id             = mysqli_real_escape_string($conn, $_POST['id']);
    $module_name    = mysqli_real_escape_string($conn, trim($_POST['module_name']));
    $semester       = mysqli_real_escape_string($conn, trim($_POST['batch_semester']));
    $course_id      = mysqli_real_escape_string($conn, trim($_POST['course_id']));

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

        $stmt = $conn->prepare("UPDATE module SET name = ?, semester = ?, course_id = ? WHERE id = ?");
        if ($stmt) {
            // s = string, i = integer, i = integer
            $stmt->bind_param("ssii", $module_name, $semester, $course_id, $id); 

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

        $stmt = $conn->prepare("INSERT INTO module (name, semester, course_id) VALUES (?,?,?)");
        if ($stmt) {
            $stmt->bind_param("ssi", $module_name, $semester, $course_id);
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
