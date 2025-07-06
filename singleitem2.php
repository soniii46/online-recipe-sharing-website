<?php
session_start();
include('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Recipes</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/view-recipe.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Pangolin&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
</head>

<body>
<?php include('includes\header.php');?>

    <!-- ----------single product details---------- -->
    <?php
    $selected_item = urldecode($_GET['var']);
    
    // Fetching product details from the main table
    $product_query = "SELECT * FROM main WHERE RecipeID='$selected_item'";
    $product_result = mysqli_query($conn, $product_query);
    while ($product_row = mysqli_fetch_array($product_result)) {
        $RecipeID = $product_row['RecipeID'];
        $Title = $product_row['Title'];
        $Category = $product_row['CategoryId']; 
        $permalink = $product_row['Permalink'];
        $R_description = $product_row['R_description'];
        $Cook_time = $product_row['Cook_time'];
        $Ingredients = $product_row['Ingredients'];
        $Difficulty = $product_row['Difficulty'];
        $No_of_servings = $product_row['No_of_servings'];
        $Price = $product_row['Price'];
        $Created_timestamp = $product_row['Created_timestamp'];
        $Recipe_image = $product_row['Recipe_image'];
        $Instructions = $product_row['Instructions'];
    }

    /* Average Rating */ 
    $rate = "SELECT AVG(rating) FROM Rate WHERE RecipeID = $RecipeID GROUP BY RecipeID";
    $rate_result = mysqli_query($conn, $rate);
     
    if(mysqli_num_rows($rate_result) > 0){
         while($row = mysqli_fetch_array($rate_result)){
             $avg_rate =  ROUND($row['AVG(rating)'], 2);
         }
     }
     else{
         $avg_rate = 0;
     }
    ?>

<main>
<div class="recipe-bar">
    <label class="recipe-lb"><?php echo $Title ?></label>
</div>
<div class="recipe-cat">
    <span><i class="fas fa-utensils"></i><?php echo $Category ?></span>
    <span><i class="far fa-calendar-alt"></i>Created: <?php echo $Created_timestamp ?></span>
    <span><i class="far fa-star"></i>Rating: <?php echo $avg_rate ?></span>
</div>

<div class='recipe-grid'>
    <div class="left-side">
        <div class="recipe-img" style="display:flex;">
            <img src="images/<?php echo $Recipe_image ?>" width="30%" height="300px">
            <form method='post'>
            <button type="submit" class="cart-btn" name="add_to_cart" style="border:0; width:150px; height:48px;">
                    <i class="fa-solid fa-cart-shopping"></i> Shop Recipe
                </button>
    </form>
        </div>
            <?php
            if (isset($_POST['add_to_cart'])) {
                // Fetch user ID from session
                $username = $_SESSION['uname'];
                $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
                $userId_row = mysqli_fetch_assoc($select_userId);
                $userId = $userId_row['UserID'];

                // Check if the item is already in the cart
                $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE Title = '$Title' AND UserID = '$userId'");

                if (mysqli_num_rows($select_cart) == 0) {
                    // Item not in cart, insert it
                    $product_quantity = 1;
                    $insert_product = mysqli_query($conn, "INSERT INTO cart(UserID, Title, Price, Recipe_image, quantity)
                        VALUES('$userId', '$Title', '$Price', '$Recipe_image', '$product_quantity')");
                    if ($insert_product) {
                        echo '<script>
                        alert("Item added to cart!");
                      </script>';
                    } else {
                        echo '<script>alert("Error adding to cart!");</script>';
                    }
                } else {
                    echo '<script>alert("This item is already in your cart!");</script>';
                }
            }
            ?>
            <div class="recipe-dt noPrint">
                <ul class="list-dt">
                    <li>Serves: <?php echo $No_of_servings?></li>
                    <li>Cooking Time: <?php echo $Cook_time ?></li>
                    <li>Difficulty: <?php echo $Difficulty ?></li>
                    <li>Price: <?php echo $Price ?></li>
                </ul>
            </div>
            <div class="recipe-des">
                <p><?php echo $R_description ?></p>
            </div>
           
            <div>
                <fieldset class="field-set noPrint" style="margin:50px 0;">
                    <legend align="center" style="font-size:17px; font-weight: bold;">COMMENTS</legend>
                    <label class="rate-lb">Rate This Recipe</label><br>

                    <!-- reference from https://www.markuptag.com/feedback-form-with-star-rating-html -->
                    <form method="POST" action="">
                        <div class="star-rating star-input">
                            <input type="radio" name="rating" id="rating-5" value="5">
                            <label for="rating-5" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-4" value="4">
                            <label for="rating-4" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-3" value="3">
                            <label for="rating-3" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-2" value="2">
                            <label for="rating-2" class="fas fa-star"></label>
                            <input type="radio" name="rating" id="rating-1" value="1">
                            <label for="rating-1" class="fas fa-star"></label>
                        </div>
                        <div class="comment-mid">
                            <label class="com-lb">Share Your Reviews</label><br>
                            <textarea placeholder="Reviews" name="comment" class="com-area" rows="6" cols="50"
                                required></textarea>
                            <input type="submit" class="submit-btn" name="submit" value="Submit">
                            <br /><br />

                        </div>
                    </form>
                    <?php 
                        if(isset($_POST['submit'])) {
                            $username = $_SESSION['uname'];
                            $select_userId = mysqli_query($conn, "SELECT Username FROM userinfo WHERE Username = '$username'");
                            $userId_row = mysqli_fetch_assoc($select_userId);
                            $userId = $userId_row['Username'];

                            $comment = $_POST["comment"];
                            $rating = $_POST["rating"];
                            $c_time = date('Y-m-d h m s');
                            
                            $c_query = "INSERT INTO Comment (Username, RecipeID, Comment_desc, Comment_timestamp) VALUES ('$userId', '$RecipeID', '$comment', '$c_time')";
                            $r_query = "INSERT INTO Rate (Username, RecipeID, Rating) VALUES ('$userId', '$RecipeID', '$rating')";
                          
                            /* Comment alert */
                            if(mysqli_query($conn, $c_query)) {
                                echo "<script> alert('Comment added Successfully!') </script>";
                            }
                            else { 
                                echo "<script> alert('Error: Comment not added') </script>";
                            }

                            /* Rating alert */
                            if(mysqli_query($conn, $r_query)) {
                                echo "<script> alert('Rate added Successfully!') </script>";
                            }
                            else { 
                                echo "<script> alert('Error: Rating not added') </script>";
                            }
                        }
                        ?>
                </fieldset>
            </div>
            <div class="popular" style="padding:0 50px; margin:50px 0; ">
            <fieldset class="field-set">
                <legend style="font-family:'Ubuntu'; font-size:17px; font-weight: bold;">POPULAR RECIPES
                </legend>

                <?php
                    $sql = "SELECT * FROM rate T, product R WHERE R.RecipeID = T.RecipeID GROUP BY T.RecipeID ORDER by AVG(T.Rating) DESC LIMIT 6";
                    $p_result = mysqli_query($conn, $sql);
                                    
                    if (mysqli_num_rows($p_result) > 0) {
                        while ($row = mysqli_fetch_assoc($p_result)) { ?>
                <div class="popular-grid">
                    <div class="popu-top">
                        <img class="popu-img" src="images/<?php echo $row['Recipe_image'] ?>">
                        <div class="popu-bt">
                            <?php echo $row['Title'] ?> <br><br>
                            <a class="popu-link"
                                href="view-recipe.php?Permalink=<?php echo $row['Permalink']?>">View Recipe</a>
                        </div>
                    </div>
                </div>
                <?php 
                }
              } 
            ?>
            </fieldset>
        </div>
    </div>
</div>

</main>

    <!--------Footer-------->
    <?php include_once('includes/footer.php');?>
</body>

</html>
