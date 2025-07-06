<?php
session_start();
include('../includes/dbconnection.php');

// Handle the order button click
if ( isset($_POST['order_btn'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];

    // Get the user ID using the session username.
    $username = $_SESSION['uname'];
    $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
    $userId_row = mysqli_fetch_assoc($select_userId);
    $userId = $userId_row['UserID'];

    // Query the cart for products.
    $cart_query = mysqli_query($conn, "SELECT * FROM cart WHERE UserID= $userId");
    $price_total = 0;
    $product_name = [];
    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            $product_name[] = $product_item['Title'] . ' (' . $product_item['quantity'] . ' )';
            $product_price = $product_item['Price'] * $product_item['quantity'];
            $price_total += $product_price;
        }
    }
        $total_product = implode(', ', $product_name);

        // Insert order details into the database.
        $detail_query = mysqli_query($conn, "INSERT INTO `ordertable` (name,UserID, number, email, total_products, total_price) 
                                             VALUES('$name','$userId', '$number', '$email', '$total_product', '$price_total')");


        if ($detail_query) {
            $order_id = mysqli_insert_id($conn);
            $_SESSION['order_id'] = $order_id;
            mysqli_query($conn, "DELETE FROM cart WHERE UserID = '$userId'");
            // Redirect to payment or final order page
            header("Location: ../pay_now.php");
             exit();;
        }
    } 
  
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Recipes</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/order-form.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Pangolin&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>

</head>

<body>

<?php include('uheader.php'); ?>


    <div class="contain">
        <section class="form">
            <h1 class="heading">Complete your order</h1>
            <form method="post" onsubmit="return validate()">
                <div class="display-order">
                    <?php
                    if (isset($_SESSION['uname'])) {
                        $username = $_SESSION['uname'];
                        $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
                        $userId_row = mysqli_fetch_assoc($select_userId);
                        $userId = $userId_row['UserID'];

                        $select_useremail= mysqli_query($conn, "SELECT User_email FROM userinfo WHERE Username = '$username'");
                        $useremail_row = mysqli_fetch_assoc($select_useremail);
                        $useremail = $useremail_row['User_email'];


                        $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE UserID= $userId");
                        $total = 0;
                        $grand_total = 0;
                        if (mysqli_num_rows($select_cart) > 0) {
                            while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                                $total_price = $fetch_cart['Price'] * $fetch_cart['quantity'];
                                $grand_total = $total += $total_price;
                    ?>
                                <span><?= $fetch_cart['Title']; ?>(<?= $fetch_cart['quantity']; ?>)</span>
                    <?php
                            }
                        } else {
                            echo "<div>Your cart is empty!</div>";
                        }
                    }
                    ?>
                    <span class="garnd-total">Grand Total: Rs. <?= $grand_total; ?></span>
                </div>
                <div class="flex">
                    <div class="inputbo">
                    
                        <span>Your name</span>
                        <input type="text" placeholder="Enter your name" name="name" value="<?php echo $username?>">
                        <p style="color:red" id="nameError" class="error"></p>
                    <input type="hidden" name="grand_total" value="<?php echo $grand_total; ?>">
                    </div>
                    <div class="inputbo">
                        <span>Your number</span>
                        <input type="number" placeholder="Enter your number" name="number" id="num">
                        <p style="color:red" id="numError" class="error"></p>
                    </div>
                    <div class="inputbo">
                        <span>Your email</span>
                        <input type="text" placeholder="Enter your email" name="email" id="email" value="<?php echo $useremail ?>">
                        <p style="color:red" id="emailError" class="error"></p>
                    </div>
                </div>
               
                <input type="submit" value="Pay with Khalti" name="order_btn" class="btn" >
            </form>
        </section>
    </div>

    <script>
        function validate() {
            var nameRGEX = /^[a-zA-Z0-9_]+$/;
            var emailREGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            var numREGEX = /^98\d{8}$/;
        
            var email = document.getElementById('email').value;
            var emailError = document.getElementById('emailError');
            if (!emailREGEX.test(email)) {
                emailError.textContent = "*Invalid email";
            } else {
                emailError.textContent = "";
            }

            var num = document.getElementById('num').value;
            var numError = document.getElementById('numError');
            if (!numREGEX.test(num)) {
                numError.textContent = "*Invalid number.";
            } else {
                numError.textContent = "";
            }

            // Prevent form submission if there are errors
            if (emailError.textContent || numError.textContent) {
                return false;
            }
        }
    </script>

<?php include('../includes/footer.php'); ?>

</body>

</html>
