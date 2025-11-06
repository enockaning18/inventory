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
        // send password link to user for reset
        // include mail notification
        include 'send_notification.php';
        sendNotification('users', 'id', $conn, $email.'#reset', $defaultkey);
        
    } 
    else {
        header("Location: ../forgot_password.php?status=user_not_found");
        exit();
    }

    $stmt->close();
}
?>