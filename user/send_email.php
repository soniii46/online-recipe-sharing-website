<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                               // Set the SMTP server to Gmail
        $mail->SMTPAuth = true;                                       // Enable SMTP authentication
        $mail->Username = 'sonivaharjan@gmail.com';                     // SMTP username
        $mail->Password = 'qfim ixkd bzad nlhd';                  // SMTP password (Use an App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;           // Enable TLS encryption
        $mail->Port = 587;                                           // TCP port to connect to

        // Recipients
        $mail->setFrom('sonivaharjan@gmail.com', 'soniva');
        $mail->addAddress('swaagyy2@gmail.com');             // Add a recipient

        // Content
        $mail->isHTML(true);                                          // Set email format to HTML
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body    = "You have received a new message from $name.<br>Email: $email";

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
