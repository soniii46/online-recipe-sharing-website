<?php 
include('../includes/dbconnection.php');
session_start();
if (!isset($_SESSION['uname'])) {
    // Redirect to login page or show an error
    header("Location: ../login.php"); // change path as needed
    exit();
}

?>

<?php
    // $userId = $_SESSION['uname'];
    $username = $_SESSION['uname'];
    $permalink = $_GET['Permalink'];
    $sql = "SELECT * FROM product WHERE Permalink = '$permalink'";
    $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $RecipeID = $row['RecipeID'];
                $Title = $row['Title'];
                $Category = $row['CategoryId']; 
		        $permalink = $row['Permalink'];
                $R_description = $row['R_description'];
                $Cook_time = $row['Cook_time'];
                $Difficulty = $row['Difficulty'];
                $No_of_servings = $row['No_of_servings'];
                $Price = $row['Price'];
                $Created_timestamp = $row['Created_timestamp'];
                $Recipe_image = $row['Recipe_image'];
            }
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
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="../CSS/view-recipe.css" />
    <link rel="stylesheet" href="../CSS/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <Title><?php echo $Title ?></Title>
    <style>
        /* CSS for the new buttons */
        .action-buttons-form {
            display: flex;
            justify-content: center;
            gap: 15px; /* space between buttons */
            margin-top: 15px;
        }
        .action-buttons-form button {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Montserrat', 'Ubuntu', sans-serif;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.3s ease;
        }
        .cart-btn {
            background-color: #28a745; /* A pleasant green */
            color: white;
        }
        .bookmark-btn {
            background-color: #007bff; /* A standard blue */
            color: white;
            margin-top: 10px;
        }
        .cart-btn:hover, .bookmark-btn:hover {
            opacity: 0.85;
        }
    </style>
</head>

<body>

    <?php include('uheader.php');?>

    <main>
   
    <!-- <input type="text" class="search" id="searchQuery" placeholder="Search recipes...">
            <div id="result"></div> -->

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
            
                <div class="recipe-img">
                    <img src="../images/<?php echo $Recipe_image ?>">  
                    <!-- New Form with both buttons -->
                    <form method='post' class="action-buttons-form">
                        <button type="submit" class="cart-btn" name="add_to_cart">
                            <i class="fa-solid fa-cart-shopping"></i> Shop Recipe
                        </button>
                        <button type="submit" class="bookmark-btn" name="add_to_bookmark">
                            <i class="fa-solid fa-bookmark"></i> Bookmark
                        </button>
                    </form>
                </div>
                <div class="recipe-des">
                    <p><?php echo $R_description ?></p>
                </div> 
                
            <?php
            // -- PHP LOGIC FOR CART AND BOOKMARK BUTTONS --
            
            // Get UserID once to use for both actions
            if (isset($_POST['add_to_cart']) || isset($_POST['add_to_bookmark'])) {
                $username = $_SESSION['uname'];
                $select_userId_query = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$username'");
                $userId_row = mysqli_fetch_assoc($select_userId_query);
                $userId = $userId_row['UserID'];
            }

            // ADD TO CART LOGIC
            if (isset($_POST['add_to_cart'])) {
                $select_cart = mysqli_query($conn, "SELECT * FROM cart WHERE Title = '$Title' AND UserID = '$userId'");

                if (mysqli_num_rows($select_cart) == 0) {
                    $product_quantity = 1;
                    $insert_product = mysqli_query($conn, "INSERT INTO cart(UserID, Title, Price, Recipe_image, quantity) VALUES('$userId', '$Title', '$Price', '$Recipe_image', '$product_quantity')");
                    if ($insert_product) {
                        echo '<script>alert("Item added to cart!");</script>';
                    } else {
                        echo '<script>alert("Error adding to cart!");</script>';
                    }
                } else {
                    echo '<script>alert("This item is already in your cart!");</script>';
                }
            }
            
            // ADD TO BOOKMARK LOGIC
            if (isset($_POST['add_to_bookmark'])) {
                // Check if the recipe is already bookmarked
                $select_bookmark = mysqli_query($conn, "SELECT * FROM bookmark WHERE UserID = '$userId' AND RecipeID = '$RecipeID'");

                if (mysqli_num_rows($select_bookmark) == 0) {
                    // Item not bookmarked, so insert it
                    $insert_bookmark = mysqli_query($conn, "INSERT INTO bookmark(Username, UserID, RecipeID) VALUES('$username','$userId', '$RecipeID')");
                    if ($insert_bookmark) {
                        echo '<script>alert("Recipe bookmarked successfully!");</script>';
                    } else {
                        echo '<script>alert("Error: Could not bookmark recipe.");</script>';
                    }
                } else {
                    echo '<script>alert("This recipe is already in your bookmarks!");</script>';
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
                                    $select_userId = mysqli_query($conn, "SELECT Username FROM userinfo WHERE Username = '$username'");
                                    $userId_row = mysqli_fetch_assoc($select_userId);
                                    $userId = $userId_row['Username'];

                                    $comment = $_POST["comment"];
                                    $rating = $_POST["rating"];
                                    $c_time = date('Y-m-d H:i:s');
                                    
                                    $c_query = "INSERT INTO Comment (Username, RecipeID, Comment_desc, Comment_timestamp) VALUES ('$userId', '$RecipeID', '$comment', '$c_time')";
                                    $r_query = "INSERT INTO Rate (Username, RecipeID, Rating) VALUES ('$userId', '$RecipeID', '$rating')";
                                  
                                    /* Comment alert */
                                    if(mysqli_query($conn, $c_query)) {
                                        echo "<script> alert('Comment added Successfully!');   </script>";
                                    }
                                    else { 
                                        echo "<script> alert('Error: Comment not added') </script>";
                                    }

                                    /* Rating alert */
                                    if(mysqli_query($conn, $r_query)) {
                                        echo "<script> alert('Rate added Successfully!'); </script>";
                                    }
                                    else { 
                                        echo "<script> alert('Error: Rating not added') </script>";
                                    }
                                }
                                ?>
                        </fieldset>
                       
                   
                </div> <!-- Display Comments -->
                    <div class="comments-section noPrint" style="margin-top: 30px;">
                        <h3 style="font-family:'Ubuntu';">User Reviews</h3>
                        <?php
                        if ($RecipeID !== null) {
                            $stmt_fetch_comments = $conn->prepare("SELECT Username, Comment_desc, Comment_timestamp FROM Comment WHERE RecipeID = ? ORDER BY Comment_timestamp DESC");
                            if ($stmt_fetch_comments) {
                                $stmt_fetch_comments->bind_param("i", $RecipeID);
                                $stmt_fetch_comments->execute();
                                $result_comments = $stmt_fetch_comments->get_result();
                                if ($result_comments->num_rows > 0) {
                                    while ($comment_row = $result_comments->fetch_assoc()) {
                                        echo "<div class='comment-display' style='border: 1px solid #eee; padding: 10px; margin-bottom: 10px; background-color: #f9f9f9;'>";
                                        echo "<p><strong>" . htmlspecialchars($comment_row['Username']) . "</strong> <span style='font-size:0.8em; color:#777;'> (" . htmlspecialchars(date("F j, Y, g:i a", strtotime($comment_row['Comment_timestamp']))) . ")</span></p>";
                                        echo "<p>" . nl2br(htmlspecialchars($comment_row['Comment_desc'])) . "</p>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p>No reviews yet. Be the first to review!</p>";
                                }
                                $stmt_fetch_comments->close();
                            } else {
                                 error_log("Failed to prepare statement for fetching comments: " . $conn->error);
                                 echo "<p>Could not load reviews at this time.</p>";
                            }
                        }
                        ?>
                        </div>
            </div>

                <div class="popular">
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
                                <img class="popu-img" src="../images/<?php echo $row['Recipe_image'] ?>">
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
        </div>
    </main>
    

<?php include_once('../includes/footer.php');?>
<script>
$(document).ready(function () {
    $("#searchQuery").on("keyup", function () {
        const query = $(this).val();

        if (query !== "") {
            $.ajax({
                url: "searchs.php", // PHP script to handle the search
                type: "POST",
                data: { search: query },
                success: function (data) {
                    $("#result").html(data); // Populate the result div with search results
                    $("#result").css("display", "block");
                },
                error: function () {
                    $("#result").html("<p>Error retrieving search results.</p>");
                }
            });
        } else {
            $("#result").html(""); // Clear results if the query is empty
        }
    });
});
</script>
</body>

</html>