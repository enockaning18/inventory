<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id          = mysqli_real_escape_string($conn, $_POST['id']);
    $brand_name  = mysqli_real_escape_string($conn, trim($_POST['brand_name']));

    if (!empty($id)) {

        // Check if another brand already has the same name
        $check = $conn->prepare("SELECT id FROM brand WHERE brand_name = ? AND id != ?");
        $check->bind_param("si", $brand_name, $id);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../brands.php?status=exists");
            exit();
        }
        $check->close();

        
        $stmt = $conn->prepare("UPDATE brand SET brand_name = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $brand_name, $id);
            if ($stmt->execute()) {
                header("Location: ../brands.php?status=update");
            } else {
                header("Location: ../brands.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../brands.php?status=error");
        }

    } else {
        
        $check = $conn->prepare("SELECT id FROM brand WHERE brand_name = ?");
        $check->bind_param("s", $brand_name);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            header("Location: ../brands.php?status=exists");
            exit();
        }
        $check->close();

        $stmt = $conn->prepare("INSERT INTO brand (brand_name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $brand_name);
            if ($stmt->execute()) {
                header("Location: ../brands.php?status=save");
            } else {
                header("Location: ../brands.php?status=error");
            }
            $stmt->close();
        } else {
            header("Location: ../brands.php?status=error");
        }
    }

    exit();
}
?>
