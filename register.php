<!DOCTYPE html>
<html>  
<head>  
    <title>Account page</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- <link rel="stylesheet" href="CSS/login.css"> -->
    <link rel="stylesheet" href="CSS/style.css">

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <script src="script/check-register.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">
    <style>
        body {
    font-family: poppins;
    background-color: #C9E9D2;
}
/* Topic/Title Styling */
.topic {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: #444;
    margin-bottom: 20px;
}

/* Form Container Styling */
.form-container {
    background: #fff;
    padding: 30px;
    max-width: 400px;
    margin: 0 auto;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-container label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #222;
}

.form-container input[type="text"],
.form-container input[type="password"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

.form-container input:focus {
    border-color: #007BFF;
    outline: none;
}

.btn {
    background-color: #007BFF;
    color: white;
    padding: 12px;
    width: 100%;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

/* Error Message Styling */
.error {
    font-size: 12px;
    color: red;
    margin-bottom: 10px;
}

/* Login Redirect */
.form-login {
    text-align: center;
    font-size: 14px;
}

.form-login a {
    color: #007BFF;
    text-decoration: none;
}

.form-login a:hover {
    text-decoration: underline;
}
    </style>
</head>  


<body> 
<?php include('includes\header.php');?>
    
  <div class = topic>
      <br>
      Registration page
      <hr>
      <br>
  </div>
    
  <form name = "Register" onsubmit = "return validateRegisterDetails()" action = "validateRegister.php" method = "POST">
      <div class = "form-container">   
          <label> <b>Username :</b> </label>   
          <input type="text" name="Username" id="Username" placeholder= "Username" size="15"> 
          <p style="color:red" id="unameError" class="error"></p>    

          <label for="email"><b>Email:</b></label>  
          <input type="text" placeholder="Enter Email" name="email" id="email">  
          <p style="color:red" id="emailError" class="error"></p>

          <label for="psw"><b>Password:</b></label>  
          <input type="password" placeholder="Enter Password" name="password" id="password">  
          <p style="color:red" id="pwdError" class="error"></p>
          <button type="submit" name="register" class="btn">Sign Up</button><br><br>
          <p class="form-login">Already have an account? <a href="login.php">Login now</a></p>	
      </div>
  </form>	
  <br>
  <br> 
  <?php include_once('includes/footer.php');?>

</body>  
</html> 