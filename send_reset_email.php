<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require 'source/src/PHPMailer.php';
require 'source/src/SMTP.php';
require 'source/src/Exception.php';



// Database connection
include('includes/dbconnection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if email exists in the database
    $query = $conn->prepare("SELECT * FROM userinfo WHERE User_email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Generate reset token
        $token = bin2hex(random_bytes(50));
        $expires = date("U") + 3600; // 1 hour expiration

        // Insert token into the database
        $query = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
        $query->bind_param("sss", $email, $token, $expires);
        $query->execute();

        // Send the reset email
        $reset_link = "http://localhost/4thsemproject/reset_password.php?token=$token";

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your email provider's SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'sonivaharjan@gmail.com'; // Your email address
            $mail->Password = 'xczw jnnw xshj icyi'; // Your email password or app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipient
            $mail->setFrom('sonivaharjan@gmail.com', 'Online Recipe');
            $mail->addAddress($email);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br><a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo "A reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Failed to send reset email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No account found with this email.";
    }
}
?>
