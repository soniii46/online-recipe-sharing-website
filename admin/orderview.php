<?php
include('../includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order View</title>
    <link rel="stylesheet" href="../CSS/admincss.css">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>
    <!-- Sidebar -->
    <section id="menu" style="height: 250vh; width: 300px;">
        <div class="logo">
            <h2>Online Recipes</h2>
        </div>
        <div class="items">
            <!-- <li><i class="fa-solid fa-chart-bar"></i><a href="adminside.php">Dashboard</a></li> -->
            <li><i class="fa-solid fa-chart-bar"></i><a href="dash.php">Dashboard</a></li>
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-message"></i><a href="msg-view.php">Messages</a></li>
            <li><i class="fa-solid fa-star"></i><a href="com-rate.php">Rating</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Orders</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="admin-logout.php">Logout</a></li>
        </div>
    </section>

    <!-- Orders Display -->
    <section class="display_order">
        <h2 class="title">Order Info</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tran_id</th>
                    <th>User Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th>Total Products</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Receipt</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ensure $conn is properly defined
                if (!$conn) {
                    die("Database connection failed: " . mysqli_connect_error());
                }

                // Determine current page
                $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = 5;
                $offset = ($page - 1) * $limit;


                // Fetch orders from the database
                $query = "SELECT * FROM ordertable ORDER BY order_date DESC LIMIT ? OFFSET ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $limit, $offset);
                $stmt->execute();
                $result = $stmt->get_result();

                $total_query = "SELECT COUNT(*) AS total FROM ordertable";
                $total_result = $conn->query($total_query);
                $total_row = $total_result->fetch_assoc();
                $total_orders = $total_row['total'];
                $total_pages = ceil($total_orders / $limit);


                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['ord_id']); ?></td>
                    <td><?= htmlspecialchars($row['tran_id']); ?></td>
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
                    <td>
                        <form action="update_order_status.php" method="POST" id="orderForm-<?= $row['ord_id']; ?>" onsubmit="disableButton(<?= $row['ord_id']; ?>)">
                            <input type="hidden" name="order_id" value="<?= $row['ord_id']; ?>">
                            <input type="hidden" name="status" value="Delivered">
                            <?php if ($row['status'] != 'Delivered') { ?>
                                <button type="submit" class="up-btn">Mark as Delivered</button>
                            <?php } else { ?>
                                <button type="button" class="delivered-btn" disabled>Delivered</button>
                            <?php } ?>
                        </form>
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
        <div class="pagination">
    <a 
        href="<?= $page > 1 ? '?page=' . ($page - 1) : '#' ?>" 
        class="pagination-btn <?= $page == 1 ? 'disabled' : '' ?>" 
        <?= $page == 1 ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
        Previous
    </a>

    <a 
        href="<?= $page < $total_pages ? '?page=' . ($page + 1) : '#' ?>" 
        class="pagination-btn <?= $page == $total_pages ? 'disabled' : '' ?>" 
        <?= $page == $total_pages ? 'style="pointer-events: none; opacity: 0.5;"' : '' ?>>
        Next
    </a>
</div>


    </section>

    <!-- JavaScript -->
    <script>
        // Disable button after form submission to prevent multiple clicks
        function disableButton(orderId) {
            var form = document.getElementById("orderForm-" + orderId);
            var button = form.querySelector("button");
            button.disabled = true;
            button.innerHTML = "Processing...";
        }
    </script>
</body>

</html>
