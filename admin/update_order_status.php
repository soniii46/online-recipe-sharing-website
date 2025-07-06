<?php
// Include PHPMailer files from the source directory
require '../source/src/PHPMailer.php';
require '../source/src/SMTP.php';
require '../source/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include your database connection file
include('../includes/dbconnection.php');

// Fetch the order ID and new status from the POST data
$order_id = $_POST['order_id'];
$status = $_POST['status'];

// Query to fetch the email of the user based on order ID
$query = "SELECT email FROM ordertable WHERE ord_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$email = $row['email'];  // User's email address

// Update the order status in the database
$update_query = "UPDATE ordertable SET status = ? WHERE ord_id = ?";
$stmt_update = $conn->prepare($update_query);
$stmt_update->bind_param("si", $status, $order_id);

if ($stmt_update->execute()) {
    // If status is updated, send the email to the user
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Replace with your SMTP host (e.g., smtp.gmail.com)
        $mail->SMTPAuth = true;
        $mail->Username = 'sonivaharjan@gmail.com';  // Your email address
        $mail->Password = 'zbyl nibv wzcy dunx';  // Your email password (or app password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;  // SMTP port (587 for STARTTLS, 465 for SSL)

        // Recipients
        $mail->setFrom('sonivaharjan@gmail.com', 'Online Recipes');  // Sender's email
        $mail->addAddress($email);  // Recipient's email

        // Attachments (recipe PDF)
        $pdfFilePath = '../pdfs/recipe.pdf';  // Path to your PDF file
        if (file_exists($pdfFilePath)) {
            $mail->addAttachment($pdfFilePath);  // Attach the PDF file
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Your Recipe Order Status';  // Email subject
        $mail->Body    = 'Thank you for your order! Your recipe is attached below. The order status has been updated to: ' . $status . '.';  // HTML email body

        // Send the email
        $mail->send();
       // After email is sent, update the status to "Delivered"
       $query_update = "UPDATE ordertable SET status = 'Delivered' WHERE ord_id = ?";
       $stmt_update = $conn->prepare($query_update);
       $stmt_update->bind_param('i', $order_id);
       $stmt_update->execute();

       // Redirect back to orderview.php with success message
      
       echo "<script>
       alert('Email sent');
       window.location.href = 'orderview.php?status=success';
     </script>";
exit;
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Failed to update the order status.";
}
?>
