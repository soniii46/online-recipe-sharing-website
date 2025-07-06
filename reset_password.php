<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    include('includes/dbconnection.php');

    // Get the current timestamp
    $current_time = date("U");

    // Validate token
    $query = $conn->prepare("SELECT * FROM password_resets WHERE token = ? AND expires >= ?");
    $query->bind_param("si", $token, $current_time);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $row = $result->fetch_assoc();
            $email = $row['email'];

            // Update the user's password
            $query = $conn->prepare("UPDATE userinfo SET User_password = ? WHERE User_email = ?");
            $query->bind_param("ss", $new_password, $email);
            $query->execute();

            // Delete the reset token
            $query = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
            $query->bind_param("s", $email);
            $query->execute();

            echo "<script>
            alert('Password has been reset successfully.');
            window.location.href='login.php';
            </script>";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <form action="" method="POST" class="form-container">
        <label for="password"><b>New Password:</b></label>
        <input type="password" name="password" placeholder="Enter your new password" required>
        <button type="submit" name="submit" class="login-btn">Reset Password</button>
    </form>
</body>
</html>
