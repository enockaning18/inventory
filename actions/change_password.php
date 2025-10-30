<?php
require_once('../baseConnect/dbConnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $old_password = trim($_POST['old_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');

    if (empty($old_password) || empty($new_password)) {
        header("Location: ../change_password.php?status=missing_fields");
        exit();
    }

    // Fetch the user's current hashed password
    $stmt = $conn->prepare("SELECT user_key FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($stored_hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (!$stored_hashed_password) {
        header("Location: ../change_password.php?status=user_not_found");
        exit();
    }

    // Verify the old password
    if (!password_verify($old_password, $stored_hashed_password)) {
        header("Location: ../change_password.php?status=incorrect_old_password");
        exit();
    }

   
    $new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);


    $update_stmt = $conn->prepare("UPDATE users SET user_key = ? WHERE id = ?");
    $update_stmt->bind_param("si", $new_hashed_password, $id);

    if ($update_stmt->execute()) {
        header("Location: ../users.php?status=change_password");
    } else {
        header("Location: ../change_password.php?status=update_failed");
    }

    $update_stmt->close();
}
