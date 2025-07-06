<?php
session_start();
include('includes/dbconnection.php'); // Ensure this path is correct

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    // Use prepared statements to prevent SQL injection
    $uname_input = $_POST['Username'];
    $email_input = $_POST['email'];
    $pw_input = $_POST['password'];

    // Validate inputs (basic example, add more as needed)
    if (empty($uname_input) || empty($email_input) || empty($pw_input)) {
        echo "<script>alert('All fields are required.'); window.location.href = 'register.php';</script>";
        exit;
    }
    if (!filter_var($email_input, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href = 'register.php';</script>";
        exit;
    }
    if (strlen($pw_input) < 6) { // Example: minimum password length
        echo "<script>alert('Password must be at least 6 characters long.'); window.location.href = 'register.php';</script>";
        exit;
    }


    // Check if username or email already exists using prepared statements
    $stmt_check = $conn->prepare("SELECT Username, User_email FROM userinfo WHERE Username = ? OR User_email = ?");
    if (!$stmt_check) {
        error_log("Prepare failed (check user): " . $conn->error);
        echo "<script>alert('An error occurred. Please try again later.'); window.location.href = 'register.php';</script>";
        exit;
    }
    $stmt_check->bind_param("ss", $uname_input, $email_input);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $existing_user = $result_check->fetch_assoc();
        if ($existing_user['Username'] == $uname_input) {
            echo "<script>alert('Username already taken. Please choose another.'); window.location.href = 'register.php';</script>";
        } elseif ($existing_user['User_email'] == $email_input) {
            echo "<script>alert('Email address is already registered. Please use a different email or login.'); window.location.href = 'register.php';</script>";
        }
    } else {
        // Generate hashed password
        $hashed_pw = password_hash($pw_input, PASSWORD_DEFAULT);

        // Insert user data into the database without verification token
        // Make sure your userinfo table no longer requires/has 'verification_token' or set it to NULL if it allows
        $stmt_insert = $conn->prepare("INSERT INTO userinfo (Username, User_email, User_password) VALUES (?, ?, ?)");
        if (!$stmt_insert) {
            error_log("Prepare failed (insert user): " . $conn->error);
            echo "<script>alert('An error occurred during registration. Please try again.'); window.location.href = 'register.php';</script>";
            exit;
        }
        $stmt_insert->bind_param("sss", $uname_input, $email_input, $hashed_pw);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Registration successful! You can now log in.'); window.location.href = 'login.php';</script>"; // Redirect to login page
            // Or if you want to stay on register.php and show a message
            // echo "<script>alert('Registration successful! You can now log in.');</script>";
        } else {
            error_log("Execute failed (insert user): " . $stmt_insert->error);
            echo "<script>alert('Failed to register. Please try again.'); window.location.href = 'register.php';</script>";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();
}

mysqli_close($conn);
?>