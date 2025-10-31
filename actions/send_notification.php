<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/SMTP.php';

function sendNotification($table, $idColumn, $conn) {
    // Fetch the latest record
    $sql = "SELECT * FROM $table ORDER BY $idColumn DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result && $result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_ASSOC);

        // Email details
        $to = "info@paishans.com";
        $subject = "New " . ucfirst($table) . " Submission - Paishans Hydraulics";
        $body  = "<h2>New " . ucfirst($table) . " Form Submission</h2>";
        $body .= "<p><strong>Name:</strong> {$row['firstname']} {$row['lastname']}</p>";
        $body .= "<p><strong>Email:</strong> {$row['email']}</p>";

        if ($table === "contact") {
            $body .= "<p><strong>Subject:</strong> {$row['subject']}</p>";
            $body .= "<p><strong>Message:</strong> {$row['message']}</p>";
        } elseif ($table === "enquiry") {
            $body .= "<p><strong>Product Details:</strong> {$row['product_details']}</p>";
        }

        $body .= "<p><strong>Client IP:</strong> {$row['client_ip']}</p>";
        $body .= "<p><em>Sent from Paishans Hydraulics Website</em></p>";

        // Initialize PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'info@paishans.com'; // your Hostinger email
            $mail->Password   = 'WsrmM/TcJq#2';   // replace with the email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
            $mail->Port       = 465;

            // Recipients
            $mail->setFrom('info@paishans.com', 'Paishans Hydraulics');
            $mail->addAddress($to);
            $mail->addReplyTo('info@paishans.com');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            error_log("Email notification sent to $to");
        } catch (Exception $e) {
            error_log("Email error: {$mail->ErrorInfo}");
        }
    } else {
        error_log("No record found in $table table");
    }
}
?>
