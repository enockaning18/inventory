<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id           = mysqli_real_escape_string($conn, $_POST['id']);
    $course_name  = mysqli_real_escape_string($conn, trim($_POST['course_name']));

    if (!empty($id)) {

        // Check if another course already has the same name
        $check = $conn->prepare("SELECT id FROM course WHERE course_name = ? AND id != ?");
        $check->bind_param("si", $course_name, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../labs.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("UPDATE course SET course_name = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $course_name, $id);
            if ($stmt->execute()) {
                header("Location: ../labs.php?status=update_course");
            } else {
                header("Location: ../labs.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../labs.php?status=error");
        }

    } else {

        // Check if course already exists
        $check = $conn->prepare("SELECT id FROM course WHERE course_name = ?");
        $check->bind_param("s", $course_name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../labs.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO course (course_name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $course_name);
            if ($stmt->execute()) {
                header("Location: ../labs.php?status=save_course");
            } else {
                header("Location: ../labs.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../labs.php?status=error");
        }
    }

    exit();
}
?>
