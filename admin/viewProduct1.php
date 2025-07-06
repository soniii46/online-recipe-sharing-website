<?php
include('../includes/dbconnection.php');
?>

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
    <section id="menu" style="height: 500vh;">
        <div class="logo">
            <h2>Online Recipes</h2>
        </div>
        <div class="items">
            <li><i class="fa-solid fa-chart-bar"></i><a href="dash.php">Dashboard</a></li>
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="msg-view.php">Messages</a></li>
            <li><i class="fa-solid fa-star"></i><a href="com-rate.php">Rating</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Orders</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="admin-logout.php">Logout</a></li>
        </div>
    </section>

    <section class="display_product">
        <h2 class="title">All Recipes</h2>
        <table>
            <thead>
                <tr>
                    <th>Recipe ID</th>
                    <th>Recipe Name</th>
                    <th>Description</th>
                    <th>Created</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th colspan="2">Operations</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                $limit = 6; // Number of users per page
                $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($page - 1) * $limit;

                $total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM product");
                $total_row = mysqli_fetch_assoc($total_result);
                $total_users = $total_row['total'];
                $total_pages = ceil($total_users / $limit);

                $sql = "SELECT * From product LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                if($result->num_rows > 0) {
                    while($row=$result->fetch_assoc()) {
                        $Permalink = $row['Permalink'];
                        $shortDescription = substr($row["R_description"], 0, 100) . '...'; // Truncate description to 100 characters
                        echo 
                        "<tr>
                            <td>".$row["RecipeID"]."</td>
                            <td>".$row["Title"]."</td>
                            <td class='table-description' title='".$row["R_description"]."'>".$shortDescription."</td>
                            <td>".$row["Created_timestamp"]."</td>
                            <td>".$row["CategoryId"]."</td>
                            <td>".$row["Price"]."</td>
                            <td class='action'><a href='edit-recipe.php?Permalink=$Permalink' style='color:blue;'>Update<i class='fa-regular fa-pen-to-square'></i></a></td>
                            <td class='action'><a href='delete-recipe.php?Permalink=$Permalink' style='color:red'>Delete <i class='fas fa-trash-alt'></i></a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>No recipes found</td></tr>";
                }
            ?>
            </tbody>
        </table>
        <div class="pagination" style="margin-top: 20px;">
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
    </section>
</body>
</html>
