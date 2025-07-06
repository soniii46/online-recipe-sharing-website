<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/admincss.css">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body>
    <section id="menu">
        <div class="logo">
            <h2>Online Recipes</h2>
        </div>
        <div class="items">
            <!-- <li><i class="fa-solid fa-chart-bar"></i><a href="adminside.php">Admin</a></li> -->
            <li><i class="fa-solid fa-chart-bar"></i><a href="dash.php">Dashboard</a></li>
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-circle-plus"></i><a href="add-main.php">Add Cuisines</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-message"></i><a href="msg-view.php">Messages</a></li>
            <li><i class="fa-solid fa-star"></i><a href="com-rate.php">Rating</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Orders</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="admin-logout.php">Logout</a></li>
        </div>
    </section>
    <!-- side container -->
    <section id="interface">
        <div>
            <i id="menu-btn" class="fa-solid fa-bars"></i>
        </div>
           <h3 class="i-name">DashBoard</h3> 
            <div class="value">
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

                <a style="text-decoration:none;" href="userview.php"><div class="value-box">
                <i class="fa-solid fa-users"></i>
                    <div>
                    <?php
                    include('../includes/dbconnection.php');
                        $product_query = "SELECT COUNT(*) AS Username FROM userinfo ";
                        $product_result = mysqli_query($conn, $product_query);
                        while ($product_row = mysqli_fetch_array($product_result)) {
                            $product_count = $product_row["Username"];
                            ?>
                        <h3><?php echo $product_count ?> </h3>
                        <?php } ?>
                        <span>Total Users</span>
                    </div>
                </div>
                </a>

                <a style="text-decoration:none;" href="msg-view.php"> <div class="value-box">
                <i class="fa-solid fa-cart-shopping"></i>
                <div>
                    <?php
                        include('../includes/dbconnection.php');
                        $product_query = "SELECT COUNT(*) AS id FROM `contact` ";
                        $product_result = mysqli_query($conn, $product_query);
                        while ($product_row = mysqli_fetch_array($product_result)) {
                            $product_count = $product_row["id"];
                            ?>
                        <h3><?php echo $product_count ?> </h3>
                        <?php } ?>
                        <span>Total Messages</span>
                    </div>
                </div>
                </a>
         </div>
    </section>

    <script>
        $('#menu-btn').click(function(){
            $('#menu').toggleClass("active");
        })
    </script>
</body>
</html>