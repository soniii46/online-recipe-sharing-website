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
    $Username = $_SESSION['uname'];
    $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$Username'");
    $userId_row = mysqli_fetch_assoc($select_userId);
    $userId = $userId_row['UserID'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="..\CSS\style.css" />



    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>

    <title>Ordered recipes</title>
    <style>
/* Back Button */
.back-btn {
    display: inline-block;
    margin: 20px 40px;
    padding: 10px 15px;
    background-color: #20b2aa;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color: #178c85;
}

/* Title */
.title {
    text-align: center;
    font-size: 26px;
    margin-top: 20px;
    color: #333;
}

/* Table Styling */
table {
    width: 90%;
    margin: 30px auto;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    overflow: hidden;
}

thead {
    background-color: #20b2aa;
    color: white;
}

table th,
table td {
    padding: 15px 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    font-size: 15px;
}

table tbody tr:hover {
    background-color: #f1f1f1;
}

/* View Receipt Button */
.view-receipt-btn {
    background-color: #ff6f61;
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    font-size: 14px;
}

.view-receipt-btn:hover {
    background-color: #e85a50;
}

/* No Receipt Message */
td span {
    color: #999;
    font-style: italic;
}

/* Responsive Table */
@media (max-width: 768px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }

    thead {
        display: none;
    }

    table tr {
        margin-bottom: 15px;
        border-bottom: 2px solid #ddd;
        padding: 10px;
        background-color: white;
    }

    table td {
        padding: 10px;
        text-align: right;
        position: relative;
    }

    table td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        text-align: left;
        font-weight: bold;
    }

    .view-receipt-btn {
        width: 100%;
        text-align: center;
    }
}

        </style>
</head>

<body>
<?php include('uheader.php'); ?>
    <div class=grid>
        <div style="margin-top:10px;margin-left: 20px;">
           
            <a href="user-dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to dashboard</a>
                <!-- <li><a href="user-dashboard.php">Dashboard</a></li> -->
                <!-- <li><a href="cart.php">Cart</a></li>
                <li><a href="bookmarked-recipes.php">Bookmarked recipes</a></li>
                <li><a href="logout.php">Logout</a></li> -->
          
        </div>
        </div>
        <section class="display_product">
        <h2 class="title">Order Info</h2>
          
            <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th style="text-align:left;">Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ensure $conn is properly defined
                if (!$conn) {
                    die("Database connection failed: " . mysqli_connect_error());
                }

                // Fetch orders from the database
                $query = "SELECT * FROM ordertable WHERE UserID = ? ORDER BY order_date DESC";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                    <td><?= htmlspecialchars($row['ord_id']); ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['number']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td><?= htmlspecialchars($row['total_products']); ?></td>
                    <td><?= htmlspecialchars($row['total_price']); ?></td>
                    <td><?= htmlspecialchars($row['order_date']); ?></td>
                    <td><?= htmlspecialchars($row['Payment_status']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>
                        <?php 
                        if ($row['Payment_status'] == 'Paid' && isset($row['receipt_url']) && !empty($row['receipt_url'])) { 
                            // Ensure the URL is properly formatted
                            $receipt_url = htmlspecialchars($row['receipt_url']); 
                        ?>
                            <a href="<?= $receipt_url; ?>" target="_blank" class="view-receipt-btn">View Receipt</a>
                        <?php 
                        } else { 
                            echo "<span>No Receipt</span>";
                        } 
                        ?>
                    </td>
                </tr>
                <?php
                    }
                } else {
                    echo "<tr><td colspan='11'>No orders found.</td></tr>";
                }
                ?>
                  </tbody>
        </table>

            </section>
<?php include('../includes/footer.php'); ?>
</body>

</html>