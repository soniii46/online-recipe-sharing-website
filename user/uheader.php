<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        /* Header styles */
        header {
            background-color: #20b2aa;
            color: #fff;
            padding: 15px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        header h2 {
            font-size: 1.8rem;
            margin: 0;
        }

        nav {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            align-items: center;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            font-size: 17px;
        }

        nav a:hover {
            color: black;
        }

        /* Cart Icon */
        .cart-link {
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            position: relative;
        }

        .cart-link i {
            font-size: 1.5rem;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -10px;
            font-size: 0.75rem;
            color: #fff;
            background-color: red;
            border-radius: 50%;
            padding: 2px 6px;
            font-weight: bold;
        }

        /* User info and auth buttons */
        .user, .sign-in-up {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user a, .sign-in-up a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .sign-in-up button {
            padding: 6px 14px;
            background-color: #fff;
            color: #20b2aa;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }

        .sign-in-up button:hover {
            background-color: #e0e0e0;
            color: #1d908e;
        }

        .logout-btn {
            padding: 6px 14px;
            background-color: #ff6347;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s, color 0.3s;
        }

        .logout-btn:hover {
            background-color: #e53e3e;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }

            nav {
                margin: 10px 0;
                flex-direction: column;
                align-items: flex-start;
            }

            .user, .sign-in-up {
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<header>
    <h2>Online Recipes</h2>
    <nav>
        <a href="../index.php">Home</a>
        <a href="../about.php">About Us</a>
        <a href="../recipes.php">Recipes</a>
        <a href="../contact.php">Contact Us</a>
        <a href="user-dashboard.php">Dashboard</a>
        <a href="cart.php" class="cart-link">
            <i class='bx bx-cart'></i>

            <?php
            $cart_count = 0;
            if (isset($_SESSION['uname'])) {
                $username = $_SESSION['uname'];
                $get_user = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
                if ($get_user && mysqli_num_rows($get_user) > 0) {
                    $user_data = mysqli_fetch_assoc($get_user);
                    $user_id = $user_data['UserID'];

                    $get_cart_items = mysqli_query($conn, "SELECT COUNT(*) as count FROM cart WHERE UserID = '$user_id'");
                    $cart_row = mysqli_fetch_assoc($get_cart_items);
                    $cart_count = $cart_row['count'];
                }
            }
            echo "<span class='cart-count'>$cart_count</span>";
            ?>
        </a>
    </nav>

    <?php
    if (isset($_SESSION['uname'])) {
        echo "
        <div class='user'>
            $_SESSION[uname] - <a href='ulogout.php' class='logout-btn'>LOGOUT</a>
        </div>";
    } else {
        echo "
        <div class='sign-in-up'>
            <a href='login.php'><button type='button'>Login</button></a>
            <a href='register.php'><button type='button'>Register</button></a>
        </div>";
    }
    ?>
</header>

</body>
</html>