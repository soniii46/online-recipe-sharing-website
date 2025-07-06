<?php
// Start session at the very beginning
session_start();
include('../includes/dbconnection.php'); // Include DB connection

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loggedIn = false;
$username = null; // Textual username from session
$userId = null;   // Numeric UserID from database

if (isset($_SESSION['uname'])) {
    $username = $_SESSION['uname'];
    $loggedIn = true;

    // Fetch the numeric UserID associated with the username
    $stmt_get_userid = $conn->prepare("SELECT UserID FROM userinfo WHERE Username = ?");
    if ($stmt_get_userid) {
        $stmt_get_userid->bind_param("s", $username);
        $stmt_get_userid->execute();
        $result_get_userid = $stmt_get_userid->get_result();
        if ($row_userid = $result_get_userid->fetch_assoc()) {
            $userId = $row_userid['UserID']; // Numeric UserID
        }
        $stmt_get_userid->close();
    } else {
        error_log("Failed to prepare statement to get UserID: " . $conn->error);
    }
}

    $RecipeID = null;
    $Title = "Recipe Not Found";
    $Category = "";
    $permalink_db = ""; // Permalink from DB
    $R_description = "Details for this recipe are currently unavailable.";
    $Cook_time = "N/A";
    $Difficulty = "N/A";
    $No_of_servings = "N/A";
    $Price = 0.00;
    $Created_timestamp = "";
    $Recipe_image = "default_recipe.png"; // A default image if none found

    if (isset($_GET['Permalink'])) {
        $permalink_get = $_GET['Permalink']; // Permalink from URL
        
        $sql_product = "SELECT * FROM product WHERE Permalink = ?";
        $stmt_product = $conn->prepare($sql_product);

        if ($stmt_product) {
            $stmt_product->bind_param("s", $permalink_get);
            $stmt_product->execute();
            $result_product = $stmt_product->get_result();

            if($result_product->num_rows > 0){
                $row_product = $result_product->fetch_assoc();
                $RecipeID = $row_product['RecipeID'];
                $Title = $row_product['Title'];
                $Category = $row_product['CategoryId']; 
                $permalink_db = $row_product['Permalink'];
                $R_description = $row_product['R_description'];
                $Cook_time = $row_product['Cook_time'];
                $Difficulty = $row_product['Difficulty'];
                $No_of_servings = $row_product['No_of_servings'];
                $Price = $row_product['Price'];
                $Created_timestamp = $row_product['Created_timestamp']; 
                $Recipe_image = $row_product['Recipe_image'];
            }
            $stmt_product->close();
        } else {
            error_log("Failed to prepare statement for product fetch: " . $conn->error);
        }
    }

    /* Average Rating */
    $avg_rate = 0;
    if ($RecipeID !== null) {
        $rate_sql = "SELECT AVG(rating) as avg_rating FROM Rate WHERE RecipeID = ?";
        $stmt_rate_avg = $conn->prepare($rate_sql);
        if ($stmt_rate_avg) {
            $stmt_rate_avg->bind_param("i", $RecipeID);
            $stmt_rate_avg->execute();
            $result_rate_avg = $stmt_rate_avg->get_result();
            if($row_rate_avg = $result_rate_avg->fetch_assoc()){
                if ($row_rate_avg['avg_rating'] !== null) {
                    $avg_rate =  ROUND($row_rate_avg['avg_rating'], 2);
                }
            }
            $stmt_rate_avg->close();
        } else {
            error_log("Failed to prepare statement for average rating: " . $conn->error);
        }
    }

    // Handle Add to Cart action
    if (isset($_POST['add_to_cart'])) {
        if ($loggedIn && $userId !== null && $RecipeID !== null) { // Check if logged in
            $check_cart_sql = "SELECT * FROM cart WHERE Title = ? AND UserID = ? AND RecipeID = ?"; 
            $stmt_check_cart = $conn->prepare($check_cart_sql);
            if ($stmt_check_cart) {
                $stmt_check_cart->bind_param("sii", $Title, $userId, $RecipeID);
                $stmt_check_cart->execute();
                $result_check_cart = $stmt_check_cart->get_result();

                if ($result_check_cart->num_rows == 0) {
                    $product_quantity = 1;
                    $insert_cart_sql = "INSERT INTO cart(UserID, RecipeID, Title, Price, Recipe_image, quantity) VALUES(?, ?, ?, ?, ?, ?)"; 
                    $stmt_insert_cart = $conn->prepare($insert_cart_sql);
                    if ($stmt_insert_cart) {
                        $stmt_insert_cart->bind_param("iisdsi", $userId, $RecipeID, $Title, $Price, $Recipe_image, $product_quantity);
                        if ($stmt_insert_cart->execute()) {
                            echo '<script>alert("Item added to cart!");</script>';
                        } else {
                            echo '<script>alert("Error adding to cart: ' . htmlspecialchars($stmt_insert_cart->error) . '");</script>';
                        }
                        $stmt_insert_cart->close();
                    } else {
                         echo '<script>alert("Error preparing cart insert: ' . htmlspecialchars($conn->error) . '");</script>';
                    }
                } else {
                    echo '<script>alert("This item is already in your cart!");</script>';
                }
                $stmt_check_cart->close();
            } else {
                 echo '<script>alert("Error preparing cart check: ' . htmlspecialchars($conn->error) . '");</script>';
            }
        } else {
             // This case should ideally not be reached if button redirects to login, but as a fallback:
            echo '<script>alert("Please log in to add items to your cart.");</script>';
        }
    }

    // Handle Comment and Rating Submission
    if (isset($_POST['submit_comment_rating']) && $loggedIn && $RecipeID !== null && $username !== null) {
        $comment_desc = trim($_POST["comment"]);
        $rating_value = isset($_POST["rating"]) ? (int)$_POST["rating"] : null;
        $c_time = date('Y-m-d H:i:s');

        $comment_success = false;
        $rating_success = false;

        if (!empty($comment_desc)) {
            $c_query = "INSERT INTO Comment (Username, RecipeID, Comment_desc, Comment_timestamp) VALUES (?, ?, ?, ?)";
            $stmt_comment = $conn->prepare($c_query);
            if ($stmt_comment) {
                $stmt_comment->bind_param("siss", $username, $RecipeID, $comment_desc, $c_time);
                if ($stmt_comment->execute()) { $comment_success = true; } 
                else { echo "<script> alert('Error adding comment: " . htmlspecialchars($stmt_comment->error) . "') </script>"; }
                $stmt_comment->close();
            } else { echo "<script> alert('Error preparing comment insert: " . htmlspecialchars($conn->error) . "') </script>"; }
        }

        if ($rating_value !== null && $rating_value >= 1 && $rating_value <= 5) {
            $r_query = "INSERT INTO Rate (Username, RecipeID, Rating) VALUES (?, ?, ?)";
            $stmt_rate = $conn->prepare($r_query);
            if ($stmt_rate) {
                $stmt_rate->bind_param("sii", $username, $RecipeID, $rating_value);
                if ($stmt_rate->execute()) { $rating_success = true; } 
                else { echo "<script> alert('Error adding rating: " . htmlspecialchars($stmt_rate->error) . "') </script>"; }
                $stmt_rate->close();
            } else { echo "<script> alert('Error preparing rating insert: " . htmlspecialchars($conn->error) . "') </script>"; }
        }

        if ($comment_success || $rating_success) {
            $alert_message = "";
            if ($comment_success && $rating_success) $alert_message = "Comment and Rating added successfully!";
            elseif ($comment_success) $alert_message = "Comment added successfully!";
            elseif ($rating_success) $alert_message = "Rating added successfully!";
            
            echo "<script>
                    alert('" . $alert_message . "');
                    window.location.href = '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db) . "&feedback=success';
                  </script>";
            exit;
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title><?php echo htmlspecialchars($Title); ?></title>
    <link rel="stylesheet" href="../CSS/view-recipe.css" />
    <link rel="stylesheet" href="../CSS/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet">
</head>
<body>

    <?php include('uheader.php');?>

    <main>
        <?php if ($RecipeID === null): ?>
            <div class="recipe-bar">
                <label class="recipe-lb"><?php echo htmlspecialchars($Title); ?></label>
            </div>
            <div style="text-align: center; padding: 20px;">
                <p><?php echo htmlspecialchars($R_description); ?></p>
                <p><a href="index.php">Return to homepage</a></p>
            </div>
        <?php else: ?>
            <div class="recipe-bar">
                <label class="recipe-lb"><?php echo htmlspecialchars($Title); ?></label>
            </div>
            
            <div class="recipe-cat">
                <span><i class="fas fa-utensils"></i><?php echo htmlspecialchars($Category); ?></span>
                <span><i class="far fa-calendar-alt"></i>Created: <?php echo htmlspecialchars(date("F j, Y", strtotime($Created_timestamp))); ?></span>
                <span><i class="far fa-star"></i>Rating: <?php echo htmlspecialchars(number_format($avg_rate, 1)); ?></span>
            </div>

            <div class='recipe-grid'>
                <div class="left-side">
                
                    <div class="recipe-img">
                        <img src="../images/<?php echo htmlspecialchars($Recipe_image); ?>" alt="<?php echo htmlspecialchars($Title); ?>">  
                        <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db); ?>">
                            <?php if ($loggedIn): ?>
                                <button type="submit" class="cart-btn" name="add_to_cart" style="border:0; width:150px; height:48px;">
                                    <i class="fa-solid fa-cart-shopping"></i> Shop Recipe
                                </button>
                            <?php else: ?>
                                <button type="button" class="cart-btn" 
                                        onclick="window.location.href='../login.php?redirect_to=<?php echo urlencode(htmlspecialchars($_SERVER['REQUEST_URI'])); ?>';" 
                                        style="border:0; width:150px; height:48px;">
                                    <i class="fa-solid fa-cart-shopping"></i> Shop Recipe
                                </button>
                                
                               
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="recipe-des">
                        <p><?php echo nl2br(htmlspecialchars($R_description)); ?></p>
                    </div> 
                    
                    <div class="recipe-dt noPrint">
                        <ul class="list-dt">
                            <li>Serves: <?php echo htmlspecialchars($No_of_servings); ?></li>
                            <li>Cooking Time: <?php echo htmlspecialchars($Cook_time); ?></li>
                            <li>Difficulty: <?php echo htmlspecialchars($Difficulty); ?></li>
                            <li>Price: Rs. <?php echo htmlspecialchars(number_format($Price, 1)); ?></li>
                        </ul>
                    </div>
                
                    <div>
                        <?php if ($loggedIn): ?>
                            <fieldset class="field-set noPrint" style="margin:50px 0;">
                                <legend align="center" style="font-size:17px; font-weight: bold;">COMMENTS & RATING</legend>
                                <label class="rate-lb">Rate This Recipe</label><br>
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db); ?>">
                                    <div class="star-rating star-input">
                                        <input type="radio" name="rating" id="rating-5" value="5"><label for="rating-5" class="fas fa-star"></label>
                                        <input type="radio" name="rating" id="rating-4" value="4"><label for="rating-4" class="fas fa-star"></label>
                                        <input type="radio" name="rating" id="rating-3" value="3"><label for="rating-3" class="fas fa-star"></label>
                                        <input type="radio" name="rating" id="rating-2" value="2"><label for="rating-2" class="fas fa-star"></label>
                                        <input type="radio" name="rating" id="rating-1" value="1"><label for="rating-1" class="fas fa-star"></label>
                                    </div>
                                    <div class="comment-mid">
                                        <label class="com-lb">Share Your Reviews</label><br>
                                        <textarea placeholder="Reviews" name="comment" class="com-area" rows="6" cols="50" required></textarea>
                                        <input type="submit" class="submit-btn" name="submit_comment_rating" value="Submit">
                                        <br /><br />
                                    </div>
                                </form>
                            </fieldset>
                        <?php else:
                           $login_comment_redirect_url = 'login.php?redirect_to=' . urlencode(htmlspecialchars($_SERVER["PHP_SELF"]) . '?Permalink=' . urlencode($permalink_db));
                           ?>
                            <fieldset class="field-set noPrint" style="margin:50px 0;">
                                <legend align="center" style="font-size:17px; font-weight: bold;">COMMENTS & RATING</legend>
                                <p>Please <a href="../login.php?redirect_to=<?php echo urlencode(htmlspecialchars($_SERVER['REQUEST_URI'])); ?>" style="color: #007bff;">log in</a> or <a href="../register.php" style="color: #007bff;">register</a> to rate and comment.</p>
                            </fieldset>
                        <?php endif; ?>
                    </div>
                </div>

                
                    <!-- Display Comments -->
                    <div class="comments-section noPrint">
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

                <div class="popular">
                    <fieldset class="field-set">
                        <legend style="font-family:'Ubuntu'; font-size:17px; font-weight: bold;">POPULAR RECIPES</legend>
                        <?php
                            $sql_popular = "SELECT P.Title, P.Permalink, P.Recipe_image FROM product P JOIN (SELECT RecipeID, AVG(Rating) AS AvgRating FROM Rate GROUP BY RecipeID ORDER BY AvgRating DESC LIMIT 6) AS TopRated ON P.RecipeID = TopRated.RecipeID";
                            $p_result = mysqli_query($conn, $sql_popular);
                                            
                            if ($p_result && mysqli_num_rows($p_result) > 0) {
                                while ($row_pop = mysqli_fetch_assoc($p_result)) { ?>
                        <div class="popular-grid">
                            <div class="popu-top">
                                <img class="popu-img" src="../images/<?php echo htmlspecialchars($row_pop['Recipe_image']); ?>" alt="<?php echo htmlspecialchars($row_pop['Title']); ?>">
                                <div class="popu-bt">
                                    <?php echo htmlspecialchars($row_pop['Title']); ?> <br><br>
                                    <a class="popu-link" href="view-recipe.php?Permalink=<?php echo urlencode($row_pop['Permalink']); ?>">View Recipe</a>
                                </div>
                            </div>
                        </div>
                        <?php 
                                }
                            } else {
                                echo "<p>No popular recipes to display at the moment.</p>";
                            }
                        ?>
                    </fieldset>
                </div>
                
            </div> <!-- end recipe-grid -->
        <?php endif; // End of check for RecipeID !== null ?>
    </main>
    
    <?php include_once('../includes/footer.php');?>

    <script>
    $(document).ready(function () {
        if (window.location.search.includes('feedback=success')) {
            const currentPermalink = '<?php echo urlencode($permalink_db); ?>';
            let newUrl = window.location.pathname; // Base path
            if (currentPermalink) {
                 newUrl += '?Permalink=' + currentPermalink; // Add permalink if exists
            }
            window.history.replaceState({ path: newUrl }, '', newUrl);
        }
    });
    </script>
</body>
</html>
<?php
if ($conn) { // Check if connection exists before closing
    mysqli_close($conn);
}
?>