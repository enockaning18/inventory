<?php
session_start();
require_once('../baseConnect/dbConnect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes (ensure this folder exists)
require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

// Check user session
if (!isset($_SESSION['id'])) {
    die("User not logged in.");
}

$userid = $_SESSION['id'];

// Fetch logged-in user's email
$stmt = $conn->prepare("SELECT email, defaultkey FROM users WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $usermail = $row['email'];
    $defaultkey = $row['defaultkey'];
} 
else {
    die("User not found.");
}

/**
 * Send notification email for the latest record in a given table.
 */
function sendNotification($table, $idColumn, $conn, $usermail, $defaultkey)
{
    $sql = "SELECT * FROM $table ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $to = $usermail;
        $subject = "New User Account - IPMC COLLEGE";
        $body  = "<h2>User Account Details</h2>";
        $body .= "<p><strong>UserEmail:</strong> {$row['email']}</p>";
        $body .= "<p><strong>Account Key:</strong> {$row['defaultkey']}</p>";
        $body .= "<p><strong>Account Type:</strong> {$row['user_type']}</p>";
        $body .= "<p>Access app via: http://127.0.0.1:100/inventory/index.php</p>";
        $body .= "<p><em>Sent from IPMC COLLEGE</em></p>";

        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@paishans.com'; // host mail
            $mail->Password   = 'WsrmM/TcJq#2';     // hostkey
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
            $mail->Port       = 465;

            // Email content
            $mail->setFrom('info@paishans.com', 'IPMC GHANA');
            $mail->addAddress($to);
            $mail->addReplyTo('info@paishans.com');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            error_log("Email sent to $to");
        } catch (Exception $e) {
            error_log("Mail error: {$mail->ErrorInfo}");
        }
    } else {
        error_log("No record found in $table table.");
    }
}

// Example: send notification from a table (you can call dynamically)
sendNotification('users', 'id', $conn, $usermail, $defaultkey);
?>
