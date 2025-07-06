<!DOCTYPE html>
<html>
<head>
    <title>Account Page</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/login.css">

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <script src="javascript/check-login.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">
	  
</head>

<body>

<?php include('includes\header.php');?>
    
    <div class = "topic">
        <br>
        Login page
        <hr>
        <br>
    </div>
    
    <form name = "login"  action = "validateLogin.php" method = "POST">
        <div class = "form-container">   
            <label><b>Username : </b></label>
            <input type="text" name="uname"  placeholder="Enter Username">  <br>
            <label><b>Password :</b> </label>
            <input type="password" placeholder="Enter Password" name="password">  <br>
            <button type="submit" name="login" class="login-btn" value = "Login">Login</button>
            <!-- <p class="form-login"><a href="forgot_password.php">Forgot Password</a></p> -->
            <p class="form-login">Don't have an account? <a href="register.php">Register now</a></p>
        </div>
    </form>	
    <br>
    <br>

<?php include_once('includes/footer.php');?>
</body>
</html>
