<?php


require_once "../baseConnect/dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = trim($_POST['email'] ?? '');
    $userkey = trim($_POST['userkey'] ?? '');

    if (empty($email) || empty($userkey)) {
        header("Location: ../forgot_password.php?status=missing");
        exit();
    }

    // Check if user exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Hash new password securely
        $hashedPassword = password_hash($userkey, PASSWORD_DEFAULT);

        // Update user password
        $update_stmt = $conn->prepare("UPDATE users SET user_key = ? WHERE email = ?");
        if ($update_stmt) {
            $update_stmt->bind_param("ss", $hashedPassword, $email);
            if ($update_stmt->execute()) {
                header("Location: ../index.php?status=change_password");
                exit();
            } else {
                header("Location: ../forgot_password.php?status=update_failed");
                exit();
            }
        } else {
            header("Location: ../forgot_password.php?status=query_error");
            exit();
        }
    } else {
        header("Location: ../forgot_password.php?status=user_not_found");
        exit();
    }

    $stmt->close();
}
?>