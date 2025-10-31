<?php
require_once('../baseConnect/dbConnect.php');

if (!$conn) {
    header("Location: ../users.php?status=dberror");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id            = mysqli_real_escape_string($conn, $_POST['id'] ?? '');
    $email         = trim(mysqli_real_escape_string($conn, $_POST['email'] ?? ''));
    $userkey       = trim($_POST['userkey'] ?? '');
    $user_type     = mysqli_real_escape_string($conn, $_POST['usertype'] ?? '');
    $instructor_id = !empty($_POST['instructor_id']) ? intval($_POST['instructor_id']) : null;

    // Validate required fields
    if (empty($email) || empty($user_type)) {
        header("Location: ../users.php?status=missing");
        exit();
    }

    // encrypt password
    $hashedKey = password_hash($userkey, PASSWORD_BCRYPT);

    // Check for duplicate email (excluding current ID)
    $emailCheck = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $emailCheck->bind_param("si", $email, $id);
    $emailCheck->execute();
    $emailResult = $emailCheck->get_result();

    if ($emailResult->num_rows > 0) {
        header("Location: ../users.php?status=emailexists");
        exit();
    }
    $emailCheck->close();

    // Check if instructor already assigned
    if (!empty($instructor_id)) {
        $checkQuery = $conn->prepare("SELECT id FROM users WHERE instructor_id = ? AND id != ?");
        $checkQuery->bind_param("ii", $instructor_id, $id);
        $checkQuery->execute();
        $checkResult = $checkQuery->get_result();

        if ($checkResult->num_rows > 0) {
            header("Location: ../users.php?status=assigned");
            exit();
        }
        $checkQuery->close();
    }

    try {
        if (!empty($id)) {
            
            $stmt = $conn->prepare("
                UPDATE users 
                SET email = ?, user_type = ?, instructor_id = ?, user_key = ?
                WHERE id = ?
            ");

            if (!$stmt) {
                throw new Exception("prepare_failed");
            }

            $stmt->bind_param("ssisi", $email, $user_type, $instructor_id, $hashedKey, $id);

            if ($stmt->execute()) {
                header("Location: ../users.php?status=update");
            } else {
                header("Location: ../users.php?status=error");
            }
        } else {
            
            $stmt = $conn->prepare("
                INSERT INTO users (email, user_type, instructor_id, user_key, date_created)
                VALUES (?, ?, ?, ?, NOW())
            ");

            if (!$stmt) {
                throw new Exception("prepare_failed");
            }

            $stmt->bind_param("ssis", $email, $user_type, $instructor_id, $hashedKey);

            if ($stmt->execute()) {

               // include mail notification
                include 'send_notification.php';
                sendNotification('users', 'id', $conn, $usermail);

                header("Location: ../users.php?status=save");
            } 
            else {
                header("Location: ../users.php?status=error");
            }
        }

        $stmt->close();
    } catch (Exception $e) {
        header("Location: ../users.php?status=error");
        exit();
    }
}

$conn->close();
?>
