<?php
include('../includes/dbconnection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Recipes</title>
    <link rel="stylesheet" href="../CSS/sty.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Pangolin&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
 <?php include('cheader.php');?>

    <div class="small-container">
        <h2 class="title">Cuisines</h2>
        <div class="row">
            <!--latest-->
            <?php

            include('../includes/dbconnection.php');
            $product_query = "SELECT * FROM product WHERE CategoryId='MC'";
            $product_result = mysqli_query($conn, $product_query);
            while ($product_row = mysqli_fetch_array($product_result)) {
                $RecipeID = $product_row["RecipeID"];
                $Title = $product_row["Title"];
                $permalink = $product_row['Permalink'];
                $price = $product_row["Price"];
                $Recipe_image = $product_row["Recipe_image"];
                $selected_item = urlencode($RecipeID);
                ?>
                <div class="col-4">
                    <a href="../singleitem.php?var=<?php echo $selected_item ?>"><img src="../images/<?php echo $Recipe_image ?>"></a>
                    <a href="../singleitem.php?var=<?php echo $selected_item ?>">
                        <h4>
                            <?php echo $Title ?>
                        </h4>
                    </a>
                    <p>
                        Rs.<?php echo $price ?>
                    </p>
                </div>
            <?php }
            ?>
        </div>
    </div>
    
<?php include_once('../includes/footer.php');?>
</body>

</html>