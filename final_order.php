<?php
include('includes/dbconnection.php');
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Recipes</title>
    <link rel="stylesheet" href=".../CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Pangolin&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #efe3e2;
        }

        a {
            text-decoration: none;
            color: rgb(224, 120, 155);
        }

        p {
            color: black;
        }

        .container {
            max-width: 1300px;
            margin: auto;
            padding-left: 25px;
            padding-right: 25px;
        }

        .order-message-container {
            width: 80%;
            /* Adjust the width as needed */
            margin: 0 auto;
            margin-top: 20px;
            /* Adjust margin-top as needed */
        }

        .message-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .message-container h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .order-detail {
            margin-top: 20px;
        }

        .order-detail span {
            display: flex;
            margin-bottom: 10px;
        }

        .order-detail .total {
            font-weight: bold;
        }

        .customer-detail {
            margin-top: 20px;
        }

        .customer-detail p {
            margin-bottom: 5px;
        }

        .customer-detail p span {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            background-color: palevioletred;
            color: white;
            padding: 8px 30px;
            margin: 30px 0;
            border-radius: 30px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        .btn:hover {
            background-color: rgb(218, 161, 180);
            /* Darker blue on hover */
        }
    </style>
</head>

<body>
<div>
    <a href="user/user-dashboard.php"><i class="fa-solid fa-arrow-left"></i>Back to dashboard</a>
    </div>
<div class="container">
   
<div class='order-message-container'>
        <div class='message-container'>
            <h3>Thank You for Your Order!</h3>
            <div class='order-detail'>
        <p>Thank you for ordering from Online Recipes. Your order has been placed successfully.</p>
        <p>We will send you a confirmation email with the order details shortly.</p>
        <p>If you have any questions, feel free to <a href="contact.php">contact us</a>.</p>
        <p>We hope to see you again soon!</p>
            </div>
        </div>
    </div>
</body>

</html>