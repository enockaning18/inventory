<?php
require_once('../baseConnect/dbConnect.php');

// Ensure DB connection is valid
if (!$conn) {
    header("Location: ../users.php?status=dberror");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input
    $id            = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
    $email         = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $user_key      = mysqli_real_escape_string($conn, $_POST['userkey'] ?? '');
    $user_type     = mysqli_real_escape_string($conn, $_POST['usertype'] ?? '');
    $instructor_id = !empty($_POST['instructor_id']) ? intval($_POST['instructor_id']) : null;
    

    // Validate required fields
    if (empty($email) || empty($user_type)) {
        header("Location: ../users.php?status=missing");
        exit();
    }

    try {
        if (!empty($id)) {
            // === UPDATE EXISTING USER ===
            $stmt = $conn->prepare("UPDATE users 
                                    SET email = ?, user_type = ?, instructor_id = ?, user_key = ? 
                                    WHERE id = ?");
            if (!$stmt) {
                throw new Exception("prepare_failed");
            }

            $stmt->bind_param("ssisi", $email, $user_type, $instructor_id, $user_key, $id);

            if ($stmt->execute()) {
                header("Location: ../users.php?status=update");
            } else {
                header("Location: ../users.php?status=error");
            }

        } else {
            // === INSERT NEW USER ===
            $stmt = $conn->prepare("INSERT INTO users (email, user_type, instructor_id, user_key)
                                    VALUES (?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("prepare_failed");
            }

            $stmt->bind_param("ssis", $email, $user_type, $instructor_id, $user_key);

            if ($stmt->execute()) {
                header("Location: ../users.php?status=save");
            } else {
                header("Location: ../users.php?status=error");
            }
        }

        $stmt->close();

    } catch (Exception $e) {
        // Handle any unexpected errors
        header("Location: ../users.php?status=error");
    }

    exit();
}

$conn->close();
?>
