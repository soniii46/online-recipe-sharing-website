<?php
session_start();
$tidx = $_GET['transaction_id'];
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['uname'])) {
    header("Location: login.php");
    exit();
}

// Retrieve order details from session
if (isset($_SESSION['order_id'])) {
    $order_id = $_SESSION['order_id'];

    // Use prepared statement to fetch order details
    $stmt = $conn->prepare("SELECT * FROM ordertable WHERE ord_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $order_details = $result->fetch_assoc();
        $name = htmlspecialchars($order_details['name']);
        $email = htmlspecialchars($order_details['email']);
        $number = htmlspecialchars($order_details['number']);
        $total_products = htmlspecialchars($order_details['total_products']);
        $total_price = htmlspecialchars($order_details['total_price']);

        // Absolute directory path for saving the receipt
        $directory = __DIR__ . '/receipts'; // Using __DIR__ to get the current directory path

        // Check if directory exists, create it if it doesn't
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0777, true)) {
                die("Failed to create directory for receipts.");
            }
        }
        
        // Base URL for the receipt
        $base_url = 'http://localhost/4thsemproject/receipts/'; 
        $receipt_filename = "$directory/receipt_order_" . $order_id . ".html";
        $receipt_url = $base_url . "receipt_order_" . $order_id . ".html";
        
        // Debugging
        error_log("Receipt File Path: " . $receipt_filename);
        error_log("Receipt URL: " . $receipt_url);
        
        // Create the receipt HTML content
        $receipt_content = "
        <html>
        <head>
            <title>Order Receipt - Order ID: $order_id</title>
        </head>
        <body>
            <h1>Order Receipt - Order ID: $order_id</h1>
            <p><strong>Order ID:</strong> $order_id</p>
            <p><strong>Transaction ID:</strong> $tidx</p>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone Number:</strong> $number</p>
            <p><strong>Products Ordered:</strong> $total_products</p>
            <p><strong>Total Price:</strong> Rs. $total_price</p>
            <p><strong>Payment Status:</strong> Paid</p>
        </body>
        </html>
        ";
        
        // Save the receipt HTML to a file
        if (file_put_contents($receipt_filename, $receipt_content) === false) {
            die("Failed to write receipt file.");
        }

        // Update the order table to save the receipt URL
        $update_stmt = $conn->prepare("UPDATE ordertable SET receipt_url = ? WHERE ord_id = ?");
        $update_stmt->bind_param("si", $receipt_url, $order_id);

        if (!$update_stmt->execute()) {
            die("Failed to update order with receipt URL.");
        }

        // Update the order status to "Paid"
        $update_status_stmt = $conn->prepare("UPDATE ordertable SET Payment_status = 'Paid', mode='Khalti', tran_id='$tidx' WHERE ord_id = ?");
        $update_status_stmt->bind_param("i", $order_id);

        if (!$update_status_stmt->execute()) {
            die("Failed to update payment status.");
        }

    } else {
        echo "<p>Order not found.</p>";
        exit();
    }
} else {
    echo "<p>Order ID not found in session.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt</title>
    <link rel="stylesheet" href="CSS/style.css">
    <style>
        
/* Receipt Section */
.receipt {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.receipt h1 {
    font-size: 36px;
    margin-bottom: 20px;
}

.receipt-details {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.receipt-details h2 {
    font-size: 24px;
    margin-bottom: 15px;
}

.receipt-details p {
    font-size: 16px;
    line-height: 1.5;
    margin-bottom: 10px;
}

.receipt-details p strong {
    color: #333;
}

/* Button */
.btn {
    display: inline-block;
    background-color: #ff6f61;
    color: white;
    padding: 12px 20px;
    text-align: center;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    text-decoration: none;
}

.btn:hover {
    background-color: #e85a50;
}

.btn:focus {
    outline: none;
}

/* Footer */
footer {
    margin-top: 20px;
    padding: 20px;
    background-color: #333;
    color: white;
    text-align: center;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .contain {
        padding: 15px;
    }

    .receipt h1 {
        font-size: 28px;
    }

    .receipt-details h2 {
        font-size: 20px;
    }
}
        </style>
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="contain">
        <section class="receipt">
            <h1 class="heading">Order Receipt</h1>
            <div class="receipt-details">
                <p><strong>Order ID:</strong> <?= $order_id; ?></p>
                <p><strong>Transaction Id:</strong> <?= $tidx; ?></p>
                <p><strong>Name:</strong> <?= $name; ?></p>
                <p><strong>Email:</strong> <?= $email; ?></p>
                <p><strong>Phone Number:</strong> <?= $number; ?></p>
                <p><strong>Products Ordered:</strong> <?= $total_products; ?></p>
                <p><strong>Total Price:</strong> Rs. <?= $total_price; ?></p>
                <p><strong>Payment Status:</strong> Paid</p>

                <a href="index.php" class="btn">Back to Home</a>
            </div>
        </section>
    </div>

    <?php include('includes/footer.php'); ?>
</body>

</html>
