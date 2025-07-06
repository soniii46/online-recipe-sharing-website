
<?php
    session_start();
    if (!isset($_SESSION['uname']))
        {
            header("Location: ../login.php");
            exit();
        }
?>
<?php
 include('../includes/dbconnection.php');

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM cart WHERE id = '$remove_id'");
    header('location:cart.php');
}


// if (isset($_POST['submit_btn'])) {
//     $message = mysqli_real_escape_string($con, $_POST['message']);
//     $sql = "INSERT INTO cart (message) VALUES ('$message')";
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Pangolin&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
     * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background-color: #f8f8f8;
    color: #333;
    line-height: 1.6;
}

/* Container for the cart page */
.cart-page {
    padding: 40px 60px;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border-radius: 12px;
    margin: 30px auto;
    max-width: 1100px;
}

/* Back button styling */
.back-btn {
    color: #20b2aa;
    text-decoration: none;
    font-weight: bold;
    font-size: 16px;
    transition: color 0.3s;
}

.back-btn i {
    margin-right: 5px;
}

.back-btn:hover {
    color: #178c85;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

thead th {
    background-color: #20b2aa;
    color: white;
    padding: 14px;
    text-align: left;
    font-size: 16px;
}

tbody td {
    padding: 15px;
    border-bottom: 1px solid #ccc;
    vertical-align: middle;
    font-size: 15px;
}

/* Image in table */
table img {
    width: 100px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}

/* Delete button */
.delete-btn {
    background-color: #ff4d4d;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    font-size: 14px;
    border-radius: 6px;
    display: inline-block;
    transition: background 0.3s;
}

.delete-btn i {
    margin-left: 5px;
}

.delete-btn:hover {
    background-color: #e60000;
}

/* Total section */
.total-price {
    margin-top: 30px;
    text-align: right;
}

.total-price table {
    width: 100%;
    max-width: 400px;
    float: right;
    border: 1px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
}

.total-price td {
    padding: 15px;
    font-size: 16px;
}

.total-price td:first-child {
    font-weight: bold;
}

/* Proceed to checkout button */
.pro-btn {
    background-color: #20b2aa;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background 0.3s;
    margin-left: 15px;
}

.pro-btn:hover {
    background-color: #178c85;
}

/* Responsive */
@media (max-width: 768px) {
    .cart-page {
        padding: 20px;
    }

    table img {
        width: 70px;
        height: 60px;
    }

    thead {
        display: none;
    }

    table, tbody, tr, td {
        display: block;
        width: 100%;
    }

    tbody td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    tbody td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        width: 45%;
        padding-left: 10px;
        font-weight: bold;
        text-align: left;
    }

    .total-price table {
        float: none;
        width: 100%;
    }
}

    </style>
</head>

<body>
<?php include('uheader.php'); ?>
    <!-- CART ITEM DETAILS -->
    <div class="small-container cart-page">
    <div class="checkout-btn" style="margin-left:100px">
        <td><a href="user-dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to dashboard</a></td> &nbsp;
    </div>
        <table>
            <thead>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Action</th>
            </thead>

            <tbody>
                <?php
                $grand_total = 0;
                if (isset($_SESSION['uname'])) {
                    $username = $_SESSION['uname'];
                    $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
                    $userId_row = mysqli_fetch_assoc($select_userId);
                    $userId = $userId_row['UserID'];
                    $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE UserID= $userId") or die('O');
                    if (mysqli_num_rows($select_cart) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                            $product_id = $fetch_cart["id"];
                            $product_name = $fetch_cart["Title"];
                            $product_image = $fetch_cart["Recipe_image"];
                            $product_price = $fetch_cart["Price"];
                            $product_quantity = $fetch_cart["quantity"];

                            $sub_total = floatval($product_price) * intval($product_quantity);
                            $grand_total += $sub_total;
                            ?>
                            <tr>
                                <td><img style="border-radius:10px;" src="../images/<?php echo $product_image ?>"></td>
                                <td><?php echo $product_name ?></td>
                                <td>Rs.<?php echo number_format($sub_total); ?></td>
                                <td><a href="cart.php?remove=<?php echo $product_id ?>"
                                        onclick="return confirm('Remove item from cart?')" class="delete-btn">Remove<i
                                            class="fas fa-trash"></i></a></td>
                            </tr>
                            <?php
                        }
                        ;
                    }
                }
                ;
                ?>

            </tbody>

        </table>
        <div class="total-price">
            <table>
                <tr>
                    <td>Total</td>
                    <td>Rs.<?php echo number_format($grand_total); ?></td>
                    <td><a href="checkout.php" name="submit_btn" class="pro-btn">Proceed to Checkout</a></td>
                </tr>
            
        
            </table>

        </div>

    </div>
   

    <?php include('../includes/footer.php'); ?>

</body>

</html>