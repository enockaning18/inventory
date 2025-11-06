<?php


require_once "../baseConnect/dbConnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        header("Location: ../forgot_password.php?status=missing");
        exit();
    }

     // Check if user exists
     $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
     if (!$stmt) {
         header("Location: ../forgot_password.php?status=user_not_found");
         exit();
     }
 
     $stmt->bind_param("s", $email);
     $stmt->execute();
     $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // send password link to user for reset
        // include mail notification
        include 'send_notification.php';
        sendNotification('users', 'id', $conn, $email, $defaultkey, $type = 'reset');

        header("Location: ../forgot_password.php?status=mail_sent");
        
    } 
    else {
        header("Location: ../forgot_password.php?status=user_not_found");
        exit();
    }

    $stmt->close();
}
?>