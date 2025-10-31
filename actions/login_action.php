<?php
require_once('../baseConnect/dbConnect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email   = trim($_POST['email'] ?? '');
    $userkey = trim($_POST['userkey'] ?? '');

    if (empty($email) || empty($userkey)) {
        header("Location: ../index.php?status=empty_fields");
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($userkey, $user['user_key'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['type'] = $user['user_type'];
            $_SESSION['instructorid'] = $user['instructor_id'];
            $_SESSION['logged_in'] = true;

            header("Location: ../dashboard.php?status=login");
            exit();
        } else {
            header("Location: ../index.php?status=incorrect_password");
            exit();
        }
    } else {
        header("Location: ../index.php?status=user_not_found");
        exit();
    }
}
?>
