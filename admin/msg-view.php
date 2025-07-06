<?php
include("../includes/dbconnection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="../CSS/admincss.css">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        /* Message Display Area */
.display_message {
    margin-left: 240px;
    padding: 40px;
    text-align: center;
}

.display_message .title {
    font-size: 28px;
    margin-bottom: 20px;
    color: #2f3640;
}

      
    </style>
</head>

<body>
<section id="menu">
        <div class="logo">
            <h2>Online Recipes</h2>
        </div>
        <div class="items">
        <li><i class="fa-solid fa-chart-bar"></i><a href="adminside.php">Dashboard</a></li>
            <!-- <li><i class="fa-solid fa-chart-bar"></i><a href="add_category.php">Category</a></li> -->
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-message"></i><a href="msg-view.php">Messages</a></li>
            <li><i class="fa-solid fa-star"></i><a href="com-rate.php">Rating</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Orders</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="admin-logout.php">Logout</a></li>
        </div>
    </section>

    <div class="display_message">
        <h2 class="title">Messages</h2>
        <table>
            <thead>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Messages</th>

            </thead>
            <tbody>
                <?php
                 $limit = 5; // Number of users per page
                 $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                 $offset = ($page - 1) * $limit;
                 
                 // Count total records
                 $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM contact");
                 $total_row = mysqli_fetch_assoc($total_result);
                 $total_users = $total_row['total'];
                 $total_pages = ceil($total_users / $limit);
                $select_products = mysqli_query($conn, "SELECT * FROM contact LIMIT $limit OFFSET $offset ");
                if (mysqli_num_rows($select_products) > 0) {
                    while ($row = mysqli_fetch_assoc($select_products)) {
                ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['msg']; ?></td>

                        </tr>
                <?php
                    };
                };
                ?>
            </tbody>
        </table>
        <div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn">Previous</a>
    <?php else: ?>
        <span class="pagination-btn disabled">Previous</span>
    <?php endif; ?>

    <?php if ($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">Next</a>
    <?php else: ?>
        <span class="pagination-btn disabled">Next</span>
    <?php endif; ?>
</div>
    </div>
</body>

</html>