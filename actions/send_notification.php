<?php
session_start();
require_once('../baseConnect/dbConnect.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes (ensure this folder exists)
require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';


// function to handle mailer after new account or password reset request
function sendNotification($table, $idColumn, $conn, $usermail, $defaultkey, $type)
{
    $sql = "SELECT * FROM $table ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if($type === 'new_account')
        {
            $to = $usermail;
            $subject = "New User Account - IPMC COLLEGE";
            $body  = "<h2>User Account Details</h2>";
            $body .= "<p><strong>UserEmail:</strong> {$row['email']}</p>";
            $body .= "<p><strong>Account Key:</strong> {$row['defaultkey']}</p>";
            $body .= "<p><strong>Account Type:</strong> {$row['user_type']}</p>";
            $body .= "<p>Access app via: http://192.168.1.254/ipmc.exams_inventory</p>";
            $body .= "<p><em>Sent from IPMC COLLEGE</em></p>";
        }
        else{

            $to = $usermail;
            $subject = "User Password Reset - IPMC COLLEGE";
            $body  = "<h2>Follow this Instructions</h2>";
            $body .= "<p>In order to reset your account password, kindly click on the link provided below..</p>";
            $body .= "<p>Access app via: http://192.168.1.254/ipmc.exams_inventory/update_password.php</p>";
            $body .= "<p><em>Sent from IPMC COLLEGE</em></p>";
        }

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

// send email after passing args
sendNotification('users', 'id', $conn, $usermail, $defaultkey, $type);
?>
