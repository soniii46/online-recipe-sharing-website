<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../CSS/admincss.css">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"> -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</head>
<body>
    <section id="menu">
        <div class="logo">
            <h2 style="color:white;">Online Recipes</h2>
        </div>
        <div class="items">
            <!-- <li><i class="fa-solid fa-chart-bar"></i><a href="adminside.php">Admin</a></li> -->
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

    <section id="interface">
        <!-- <div>
            <i id="menu-btn" class="fa-solid fa-bars"></i>
        </div> -->
        <h3 class="i-name">Dashboard</h3>

        <div class="value">
            <?php
                include('../includes/dbconnection.php');
                
                // Fetch count of completed orders
                $completed_query = "SELECT COUNT(*) AS delivered FROM ordertable WHERE status = 'delivered'";
                $completed_result = mysqli_query($conn, $completed_query);
                $completed_count = mysqli_fetch_assoc($completed_result)['delivered'];

                // Fetch count of pending orders
                $pending_query = "SELECT COUNT(*) AS pending FROM ordertable WHERE status = 'pending'";
                $pending_result = mysqli_query($conn, $pending_query);
                $pending_count = mysqli_fetch_assoc($pending_result)['pending'];
            ?>
<a style="text-decoration:none;" href="viewProduct1.php"> <div class="value-box">
                <i class="fa-solid fa-list"></i>
                    <div>
                    <?php
                        include('../includes/dbconnection.php');
                        $product_query = "SELECT COUNT(*) AS RecipeId FROM product";
                        $product_result = mysqli_query($conn, $product_query);
                        while ($product_row = mysqli_fetch_array($product_result)) {
                            $product_count = $product_row["RecipeId"];
                            ?>
                        <h3><?php echo $product_count ?> </h3>
                        <?php } ?>
                        <span>Total Recipes</span>
                    </div>
                </div>
            </a>
            <a style="text-decoration:none;" href="userview.php"> <div class="value-box">
                <i class="fa-solid fa-list"></i>
                    <div>
                    <?php
                        include('../includes/dbconnection.php');
                        $product_query = "SELECT COUNT(*) AS id FROM ordertable";
                        $product_result = mysqli_query($conn, $product_query);
                        while ($product_row = mysqli_fetch_array($product_result)) {
                            $product_count = $product_row["id"];
                            ?>
                        <h3><?php echo $product_count ?> </h3>
                        <?php } ?>
                        <span>Total Users </span>
                    </div>
                </div>
            </a>
            <a style="text-decoration:none;" href="orderview.php"> <div class="value-box">
                <i class="fa-solid fa-list"></i>
                    <div>
                    <?php
                        include('../includes/dbconnection.php');
                        $product_query = "SELECT COUNT(*) AS id FROM ordertable";
                        $product_result = mysqli_query($conn, $product_query);
                        while ($product_row = mysqli_fetch_array($product_result)) {
                            $product_count = $product_row["id"];
                            ?>
                        <h3><?php echo $product_count ?> </h3>
                        <?php } ?>
                        <span>Total orders </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pie Chart for Completed vs Pending Orders -->
        <div class="chart-container">
            <canvas id="ordersChart"></canvas>
        </div>
    </section>

    <script>
    // Chart.js Pie Chart for Completed vs Pending Orders
    var ctx = document.getElementById('ordersChart').getContext('2d');
    var ordersChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: [
                'Completed Orders (' + <?php echo $completed_count; ?> + ')', // Append the count to the label
                'Pending Orders (' + <?php echo $pending_count; ?> + ')'
            ],
            datasets: [{
                label: 'Order Status',
                data: [<?php echo $completed_count; ?>, <?php echo $pending_count; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)', // Completed orders (green)
                    'rgba(255, 99, 132, 0.7)'  // Pending orders (red)
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Order Status: Completed vs Pending'
                }
            }
        }
    });

    // Toggle sidebar
    $('#menu-btn').click(function() {
        $('#menu').toggleClass("active");
    });
</script>

</body>
</html>
