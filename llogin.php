<?php
session_start();
include('includes/dbconnection.php');

if (isset($_POST['login'])) {
    $username = $_POST['uname'];
    $password = $_POST['password'];

    // Query to check credentials
    $query = mysqli_query($conn, "SELECT * FROM userinfo WHERE Username='$username'");

    if (mysqli_num_rows($query) > 0) {
        $_SESSION['uname'] = $username;

        // Auto add item to cart if flagged before login
        if (isset($_SESSION['add_to_cart_after_login'])) {
            $RecipeID = $_SESSION['add_to_cart_after_login'];
            unset($_SESSION['add_to_cart_after_login']);

            $get_user = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
            $user = mysqli_fetch_assoc($get_user);
            $userId = $user['UserID'];

            $get_product = mysqli_query($conn, "SELECT * FROM product WHERE RecipeID = '$RecipeID'");
            $product = mysqli_fetch_assoc($get_product);

            $check_cart = mysqli_query($conn, "SELECT * FROM cart WHERE UserID = '$userId' AND Title = '{$product['Title']}'");

            if (mysqli_num_rows($check_cart) == 0) {
                mysqli_query($conn, "INSERT INTO cart(UserID, Title, Price, Recipe_image, quantity) 
                    VALUES ('$userId', '{$product['Title']}', '{$product['Price']}', '{$product['Recipe_image']}', 1)");
            }

            $_SESSION['cart_added_after_login'] = true;
            header("Location: view-recipe.php?var=" . urlencode($RecipeID));
            exit();
        }

        // Normal redirect
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Recipe Website</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        .login-container {
            width: 300px;
            margin: 80px auto;
            padding: 30px;
            border: 1px solid #1fab89;
            border-radius: 10px;
            background-color: #f4f4f4;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #1fab89;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<?php include('includes/header.php'); ?>

<div class="login-container">
    <h2>Login</h2>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
        <label>Username:</label>
        <input type="text" name="uname" required>

        <label>Password:</label>
        <input type="password" name="psw" required>

        <input type="submit" name="login" value="Login">
    </form>
</div>

<?php include('includes/footer.php'); ?>

</body>
</html>
