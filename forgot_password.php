<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
    /* General Styles */
/* body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
} */

.topic {
    text-align: center;
    margin: 20px 0px;
}

div.topic h2 {
    color: #333;
}

div.topic hr {
    width: 50%;
    border: 1px solid #ddd;
    margin-left:25%;
}

/* Form Styles */
.form-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    width: 300px;
    text-align: center;
    margin-left: 40%;
    margin-bottom: 70px;
    margin-top: 30px;
}

.form-container label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.form-container input[type="email"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

/* Button Styles */
.login-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    width: 100%;
    transition: background 0.3s;
}

.login-btn:hover {
    background-color: #0056b3;
}
</style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="topic">
        <h2>Forgot Password</h2>
        <hr>
    </div>
    <form action="send_reset_email.php" method="POST" class="form-container">
        <label for="email"><b>Email:</b></label>
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <button type="submit" name="submit" class="login-btn">Send Reset Link</button>
    </form>

    <?php include('includes/footer.php'); ?>
</body>
</html>
