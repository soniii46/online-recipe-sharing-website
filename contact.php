
<?php
include('includes/dbconnection.php');
session_start();
if(!isset($_SESSION['uname'])){
    header("Location: login.php");
    exit();
}
if (isset($_SESSION['uname'])) {
    $username = $_SESSION['uname'];
    $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
    $userId_row = mysqli_fetch_assoc($select_userId);
    $userId = $userId_row['UserID'];

    $select_useremail= mysqli_query($conn, "SELECT User_email FROM userinfo WHERE Username = '$username'");
    $useremail_row = mysqli_fetch_assoc($select_useremail);
    $useremail = $useremail_row['User_email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        /* Contact Section Styling */
.contact {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 50px;
    background-color: #f0f8f7;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Contact Information Block */
.contact-info {
    flex: 1 1 40%;
    background-color: #20b2aa;
    color: #fff;
    padding: 30px;
    border-radius: 10px;
    margin-right: 20px;
}

.contact-info h2 {
    margin-bottom: 20px;
    font-size: 24px;
}

.contact-info p {
    margin-bottom: 10px;
    font-size: 16px;
}

/* Contact Form Block */
.contact-form {
    flex: 1 1 50%;
    background-color: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.contact-form h2 {
    margin-bottom: 20px;
    font-size: 24px;
    color: #20b2aa;
}

/* Form Fields */
.contact-form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    margin-top: 15px;
    color: #333;
}

.contact-form input,
.contact-form textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 10px;
    font-size: 15px;
    transition: border-color 0.3s ease;
}

.contact-form input:focus,
.contact-form textarea:focus {
    border-color: #20b2aa;
    outline: none;
}

/* Submit Button */
.contact-form button {
    background-color: #20b2aa;
    color: #fff;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-top: 10px;
}

.contact-form button:hover {
    background-color: #178c85;
}

/* Responsive */
@media (max-width: 768px) {
    .contact {
        flex-direction: column;
        padding: 20px;
    }

    .contact-info,
    .contact-form {
        flex: 1 1 100%;
        margin-right: 0;
        margin-bottom: 30px;
    }
}

        </style>
</head>
<body>
<?php require "includes/header.php" ;?>

<section class="contact">
        <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Address: Teku, Kathmandu, Nepal</p>
            <p>Phone: 1234567890</p>
            <p>Email: soniii@gamil.com</p>
        </div>
        <div class="contact-form">
            <h2>Contact Form</h2>
            <form action="" method="POST" onsubmit="return validate()">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="Your Name" value="<?php echo $username?>">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Your email" value="<?php echo $useremail?>" >
                <label for="message">Message:</label>
                <textarea name="message" id="message" placeholder="Your message"  required></textarea>
                <button name="submit" type="submit">Send Message</button>
            </form>
        </div>
    </section>
    <?php
     // Database connection
     include('includes/dbconnection.php');
    if (isset($_POST['submit'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);

        $sql = "INSERT INTO contact (name, email, msg) VALUES ('$name', '$email', '$message')";

        if (mysqli_query($conn, $sql)) {
            echo '<script>alert("Your message has been successfully submitted.");</script>';
        } else {
            echo '<script>alert("Error: " . $sql . "<br>" . mysqli_error($con));</script>';
        }
    }

    mysqli_close($conn);
    ?>
   

    <!-- footer -->
<?php include_once('includes/footer.php');?>
</body>
</html>
