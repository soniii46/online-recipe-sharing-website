<?php
// Start session at the very beginning
session_start();
include('includes/dbconnection.php'); // Include DB connection

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
    $permalink_db = ""; // Canonical Permalink from DB, used for all generated links/forms
    $R_description = "Details for this recipe are currently unavailable.";
    $Cook_time = "N/A";
    $Difficulty = "N/A";
    $No_of_servings = "N/A";
    $Price = 0;
    $Created_timestamp = "";
    $Recipe_image = "default_recipe.png"; // A default image if none found

    $sql_product_query = null;
    $stmt_param_val = null;
    $stmt_param_type = null;

    if (isset($_GET['Permalink'])) {
        $permalink_from_url = $_GET['Permalink'];
        $sql_product_query = "SELECT * FROM product WHERE Permalink = ?";
        $stmt_param_val = $permalink_from_url;
        $stmt_param_type = "s";
    } elseif (isset($_GET['var'])) {
        $selected_item_var = urldecode($_GET['var']); // The requested $_GET['var']
        if (is_numeric($selected_item_var)) {
            // Assume numeric 'var' is RecipeID
            $sql_product_query = "SELECT * FROM product WHERE RecipeID = ?";
            $stmt_param_val = (int)$selected_item_var;
            $stmt_param_type = "i";
        } else {
            // Assume non-numeric 'var' is a Permalink
            $sql_product_query = "SELECT * FROM product WHERE Permalink = ?";
            $stmt_param_val = $selected_item_var;
            $stmt_param_type = "s";
        }
    }

    if ($sql_product_query && $stmt_param_val !== null) {
        $stmt_product = $conn->prepare($sql_product_query);
        if ($stmt_product) {
            $stmt_product->bind_param($stmt_param_type, $stmt_param_val);
            $stmt_product->execute();
            $result_product = $stmt_product->get_result();

            if($result_product->num_rows > 0){
                $row_product = $result_product->fetch_assoc();
                $RecipeID = $row_product['RecipeID'];
                $Title = $row_product['Title'];
                $Category = $row_product['CategoryId'];
                $permalink_db = $row_product['Permalink']; // Store the canonical permalink from DB
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

    // --- BOOKMARK LOGIC START ---
    $isBookmarked = false;
    if ($loggedIn && $userId !== null && $RecipeID !== null) {
        $stmt_check_bookmark = $conn->prepare("SELECT 1 FROM bookmark WHERE UserID = ? AND RecipeID = ?");
        if ($stmt_check_bookmark) {
            $stmt_check_bookmark->bind_param("ii", $userId, $RecipeID);
            $stmt_check_bookmark->execute();
            $stmt_check_bookmark->store_result();
            if ($stmt_check_bookmark->num_rows > 0) {
                $isBookmarked = true;
            }
            $stmt_check_bookmark->close();
        } else {
            error_log("Failed to prepare statement to check bookmark: " . $conn->error);
        }
    }

    // Handle Bookmark Toggle action (manual click by logged-in user)
    if (isset($_POST['toggle_bookmark']) && $permalink_db) { // $permalink_db check ensures recipe context is valid
        if ($loggedIn && $userId !== null && $RecipeID !== null) {
            if ($isBookmarked) { // Action: Remove bookmark
                $stmt_remove_bookmark = $conn->prepare("DELETE FROM bookmark WHERE UserID = ? AND RecipeID = ?");
                if ($stmt_remove_bookmark) {
                    $stmt_remove_bookmark->bind_param("ii", $userId, $RecipeID);
                    if ($stmt_remove_bookmark->execute()) {
                        $_SESSION['bookmark_message'] = 'Recipe removed from bookmarks!';
                    } else {
                        $_SESSION['bookmark_message'] = 'Error removing bookmark: ' . htmlspecialchars($stmt_remove_bookmark->error);
                    }
                    $stmt_remove_bookmark->close();
                } else {
                    $_SESSION['bookmark_message'] = 'Error preparing to remove bookmark: ' . htmlspecialchars($conn->error);
                }
            } else { // Action: Add bookmark
                $stmt_add_bookmark = $conn->prepare("INSERT INTO bookmark ( UserID, RecipeID) VALUES (?, ?)");
                if ($stmt_add_bookmark) {
                    $stmt_add_bookmark->bind_param("ii", $userId, $RecipeID);
                    if ($stmt_add_bookmark->execute()) {
                        $_SESSION['bookmark_message'] = 'Recipe bookmarked successfully!';
                    } else {
                        // Check for duplicate key error (user might have bookmarked in another tab)
                        if ($conn->errno == 1062) { // 1062 is MySQL error code for duplicate entry
                             $_SESSION['bookmark_message'] = 'This recipe is already bookmarked.';
                        } else {
                            $_SESSION['bookmark_message'] = 'Error bookmarking recipe: ' . htmlspecialchars($stmt_add_bookmark->error);
                        }
                    }
                    $stmt_add_bookmark->close();
                } else {
                    $_SESSION['bookmark_message'] = 'Error preparing to bookmark recipe: ' . htmlspecialchars($conn->error);
                }
            }
            // Redirect to clean URL and show message
            echo "<script>window.location.href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db) . "&bookmark_action_status=1';</script>";
            exit;

        } else if (!$loggedIn && $RecipeID !== null) { // User clicked bookmark but was not logged in
            $login_redirect_bookmark_url = 'login.php?redirect_to=' . urlencode(htmlspecialchars($_SERVER["PHP_SELF"]) . '?Permalink=' . urlencode($permalink_db) . '&auto_bookmark=1');
            echo "<script>window.location.href='$login_redirect_bookmark_url';</script>";
            exit;
        } else { // Should not happen if button logic is correct (e.g. RecipeID missing but button shown)
             $_SESSION['bookmark_message'] = 'Could not process bookmark request. Product details might be missing or you are not logged in.';
             // Attempt to redirect back to permalink if available, otherwise index.
             $redirect_url = $permalink_db ? htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db) : "index.php";
             echo "<script>window.location.href='" . $redirect_url . "&bookmark_action_status=1';</script>";
             exit;
        }
    }

    // Auto-add to bookmarks after login if requested
    if ($loggedIn && $userId !== null && $RecipeID !== null && $permalink_db && isset($_GET['auto_bookmark']) && $_GET['auto_bookmark'] == '1' && isset($_GET['Permalink']) && $_GET['Permalink'] == $permalink_db) {
        // Re-check if bookmarked, as state might have changed or check wasn't performed before redirect
        $stmt_check_again = $conn->prepare("SELECT 1 FROM bookmark WHERE  UserID = ? AND RecipeID = ?");
        $already_bookmarked_on_auto = false;
        if($stmt_check_again){
            $stmt_check_again->bind_param("ii", $userId, $RecipeID);
            $stmt_check_again->execute();
            $stmt_check_again->store_result();
            if($stmt_check_again->num_rows > 0) $already_bookmarked_on_auto = true;
            $stmt_check_again->close();
        }

        if (!$already_bookmarked_on_auto) {
            $stmt_auto_add_bookmark = $conn->prepare("INSERT INTO bookmark (UserID, RecipeID) VALUES ( ?, ?)");
            if ($stmt_auto_add_bookmark) {
                $stmt_auto_add_bookmark->bind_param("ii",$username, $userId, $RecipeID);
                if ($stmt_auto_add_bookmark->execute()) {
                    $_SESSION['bookmark_message'] = 'Recipe bookmarked automatically after login!';
                } else {
                    $_SESSION['bookmark_message'] = 'Error automatically bookmarking: ' . htmlspecialchars($stmt_auto_add_bookmark->error);
                }
                $stmt_auto_add_bookmark->close();
            } else {
                $_SESSION['bookmark_message'] = 'Error preparing auto bookmark insert: ' . htmlspecialchars($conn->error);
            }
        } else {
            $_SESSION['bookmark_message'] = 'This recipe was already in your bookmarks.';
        }
        // Redirect to clean URL and show message
        echo "<script>window.location.href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db) . "&bookmark_auto_attempt=1';</script>";
        exit;
    }

    // Display bookmark message if redirected from auto-add or manual action
    if ((isset($_GET['bookmark_action_status']) || isset($_GET['bookmark_auto_attempt'])) && isset($_SESSION['bookmark_message'])) {
        echo '<script>alert("' . addslashes($_SESSION['bookmark_message']) . '");</script>';
        unset($_SESSION['bookmark_message']);
    }
    // --- BOOKMARK LOGIC END ---


    // Auto-add to cart after login if requested
    if ($loggedIn && $userId !== null && $RecipeID !== null && $permalink_db && isset($_GET['auto_add_to_cart']) && $_GET['auto_add_to_cart'] == '1' && isset($_GET['Permalink']) && $_GET['Permalink'] == $permalink_db) {
        $check_cart_sql = "SELECT * FROM cart WHERE UserID = ? AND RecipeID = ?";
        $stmt_check_cart = $conn->prepare($check_cart_sql);
        if ($stmt_check_cart) {
            $stmt_check_cart->bind_param("ii", $userId, $RecipeID);
            $stmt_check_cart->execute();
            $result_check_cart = $stmt_check_cart->get_result();

            if ($result_check_cart->num_rows == 0) {
                $product_quantity = 1;
                $insert_cart_sql = "INSERT INTO cart(UserID, RecipeID, Title, Price, Recipe_image, quantity) VALUES(?, ?, ?, ?, ?, ?)";
                $stmt_insert_cart = $conn->prepare($insert_cart_sql);
                if ($stmt_insert_cart) {
                    $stmt_insert_cart->bind_param("iisdsi", $userId, $RecipeID, $Title, $Price, $Recipe_image, $product_quantity);
                    if ($stmt_insert_cart->execute()) {
                        $_SESSION['cart_message'] = 'Item added to cart automatically after login!';
                    } else {
                        $_SESSION['cart_message'] = 'Error automatically adding item to cart: ' . htmlspecialchars($stmt_insert_cart->error);
                    }
                    $stmt_insert_cart->close();
                } else {
                     $_SESSION['cart_message'] = 'Error preparing auto cart insert: ' . htmlspecialchars($conn->error);
                }
            } else {
                $_SESSION['cart_message'] = 'This item was already in your cart.';
            }
            $stmt_check_cart->close();
        } else {
             $_SESSION['cart_message'] = 'Error preparing auto cart check: ' . htmlspecialchars($conn->error);
        }
        // Redirect to clean URL (using $permalink_db) and show message
        echo "<script>window.location.href='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db) . "&cart_auto_attempt=1';</script>";
        exit;
    }

    // Display cart message if redirected from auto-add
    if (isset($_GET['cart_auto_attempt']) && isset($_SESSION['cart_message'])) {
        echo '<script>alert("' . addslashes($_SESSION['cart_message']) . '");</script>';
        unset($_SESSION['cart_message']);
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

    // Handle Add to Cart action (manual click)
    if (isset($_POST['add_to_cart'])) {
        if ($loggedIn && $userId !== null && $RecipeID !== null) {
            $check_cart_sql = "SELECT * FROM cart WHERE UserID = ? AND RecipeID = ?";
            $stmt_check_cart = $conn->prepare($check_cart_sql);
            if ($stmt_check_cart) {
                $stmt_check_cart->bind_param("ii", $userId, $RecipeID);
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
        } else if (!$loggedIn && $permalink_db && $RecipeID !== null) { // Added RecipeID check
            // If not logged in and add_to_cart is clicked, redirect to login with intent to add
            $login_redirect_url = 'login.php?redirect_to=' . urlencode(htmlspecialchars($_SERVER["PHP_SELF"]) . '?Permalink=' . urlencode($permalink_db) . '&auto_add_to_cart=1');
            echo "<script>window.location.href='$login_redirect_url';</script>";
            exit;
        } else {
            echo '<script>alert("Please log in to add items to your cart. Product details might be missing.");</script>';
        }
    }

    // Handle Comment and Rating Submission
    if (isset($_POST['submit_comment_rating']) && $loggedIn && $RecipeID !== null && $username !== null && $permalink_db) {
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
            $r_query_check = "SELECT RateID FROM Rate WHERE Username = ? AND RecipeID = ?";
            $stmt_check_rate = $conn->prepare($r_query_check);
            if ($stmt_check_rate) {
                $stmt_check_rate->bind_param("si", $username, $RecipeID);
                $stmt_check_rate->execute();
                $stmt_check_rate->store_result();
                if ($stmt_check_rate->num_rows > 0) { // User already rated
                    $r_query = "UPDATE Rate SET Rating = ? WHERE Username = ? AND RecipeID = ?";
                    $stmt_rate = $conn->prepare($r_query);
                    if ($stmt_rate) {
                        $stmt_rate->bind_param("isi", $rating_value, $username, $RecipeID);
                        if ($stmt_rate->execute()) { $rating_success = true; }
                        else { echo "<script> alert('Error updating rating: " . htmlspecialchars($stmt_rate->error) . "') </script>"; }
                        $stmt_rate->close();
                    } else { echo "<script> alert('Error preparing rating update: " . htmlspecialchars($conn->error) . "') </script>"; }

                } else { // New rating
                    $r_query = "INSERT INTO Rate (Username, RecipeID, Rating) VALUES (?, ?, ?)";
                    $stmt_rate = $conn->prepare($r_query);
                    if ($stmt_rate) {
                        $stmt_rate->bind_param("sii", $username, $RecipeID, $rating_value);
                        if ($stmt_rate->execute()) { $rating_success = true; }
                        else { echo "<script> alert('Error adding rating: " . htmlspecialchars($stmt_rate->error) . "') </script>"; }
                        $stmt_rate->close();
                    } else { echo "<script> alert('Error preparing rating insert: " . htmlspecialchars($conn->error) . "') </script>"; }
                }
                $stmt_check_rate->close();
            } else { echo "<script> alert('Error preparing rating check: " . htmlspecialchars($conn->error) . "') </script>"; }
        }


        if ($comment_success || $rating_success) {
            $alert_message = "";
            if ($comment_success && $rating_success) $alert_message = "Comment and Rating submitted successfully!";
            elseif ($comment_success) $alert_message = "Comment submitted successfully!";
            elseif ($rating_success) $alert_message = "Rating submitted successfully!";
            
            echo "<script>
                    alert('" . $alert_message . "');
                    window.location.href = '" . htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db) . "&feedback=success';
                  </script>";
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($Title); ?> - Recipe Details</title>
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/view-recipe.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oxygen&display=swap" rel="stylesheet">
    <style>
        /* CSS for action buttons below recipe image */
        .recipe-actions {
            display: flex;
            justify-content: center; /* Center the buttons horizontally */
            align-items: center;     /* Vertically align buttons */
            gap: 15px;               /* Space between the buttons */
            margin-top: 20px;        /* Space between image and buttons */
        }
        .recipe-actions form {
            margin: 0; /* Remove default form margin */
        }
        .cart-btn, .bookmark-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px; /* Space between icon and text */
            padding: 12px 20px;
            font-family: 'Montserrat', 'Ubuntu', sans-serif;
            border-radius: 8px;
            border: none;
            color: white;
            font-size: 1rem;
           
            cursor: pointer;
            transition: background-color 0.3s ease, opacity 0.3s ease;
            height: 48px;
            min-width: 160px;
        }
        .cart-btn:hover, .bookmark-btn:hover {
            opacity: 0.85;
        }
   
        .bookmark-btn {
            background-color: #007bff;
            margin-top: 10px; /* Blue */
        }
    </style>
</head>
<body>

    <?php include('includes/header.php'); ?>

    <main>
        <?php if ($RecipeID === null || !$permalink_db): ?>
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
                        <img src="images/<?php echo htmlspecialchars($Recipe_image); ?>" alt="<?php echo htmlspecialchars($Title); ?>">
                        
                        <div class="recipe-actions">
                            <!-- Shop Recipe Form -->
                            <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db); ?>">
                                <?php if ($loggedIn): ?>
                                    <button type="submit" class="cart-btn" name="add_to_cart">
                                        <i class="fa-solid fa-cart-shopping"></i> Shop Recipe
                                    </button>
                                <?php else:
                                    $login_redirect_url = 'login.php?redirect_to=' . urlencode(htmlspecialchars($_SERVER["PHP_SELF"]) . '?Permalink=' . urlencode($permalink_db) . '&auto_add_to_cart=1');
                                ?>
                                    <button type="button" class="cart-btn" onclick="window.location.href='<?php echo $login_redirect_url; ?>';">
                                        <i class="fa-solid fa-cart-shopping"></i> Shop Recipe
                                    </button>
                                <?php endif; ?>
                            </form>
                            
                            <!-- Bookmark Recipe Form -->
                            <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?Permalink=" . urlencode($permalink_db); ?>">
                                <?php if ($loggedIn): 
                                    $bookmark_text = $isBookmarked ? 'Bookmarked' : 'Bookmark Recipe';
                                    // Use an inline style only for the dynamic color change
                                    $bookmark_style = $isBookmarked ? 'style="background-color: #28a745;"' : ''; // Green if bookmarked, default blue from CSS otherwise
                                ?>
                                    <button type="submit" class="bookmark-btn" name="toggle_bookmark" <?php echo $bookmark_style; ?>>
                                        <i class="fas fa-bookmark"></i> <?php echo $bookmark_text; ?>
                                    </button>
                                <?php else: // Not logged in
                                    $login_redirect_bookmark_url = 'login.php?redirect_to=' . urlencode(htmlspecialchars($_SERVER["PHP_SELF"]) . '?Permalink=' . urlencode($permalink_db) . '&auto_bookmark=1');
                                ?>
                                    <button type="button" class="bookmark-btn" onclick="window.location.href='<?php echo $login_redirect_bookmark_url; ?>';">
                                        <i class="fas fa-bookmark"></i> Bookmark Recipe
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <div class="recipe-des">
                        <p><?php echo nl2br(htmlspecialchars($R_description)); ?></p>
                    </div>
                    
                    <div class="recipe-dt noPrint">
                        <ul class="list-dt">
                            <li>Serves: <?php echo htmlspecialchars($No_of_servings); ?></li>
                            <li>Cooking Time: <?php echo htmlspecialchars($Cook_time); ?></li>
                            <li>Difficulty: <?php echo htmlspecialchars($Difficulty); ?></li>
                            <li>Price: Rs. <?php echo htmlspecialchars(number_format($Price, 2)); ?></li>
                        </ul>
                    </div>
                
                    <div>
                        <?php if ($loggedIn): ?>
                            <fieldset class="field-set noPrint" style="margin:50px 0;">
                                <legend align="center" style="font-size:17px; font-weight: bold;">COMMENTS & RATING</legend>
                                <label class="rate-lb">Rate This Recipe (Your current rating will be updated if you rate again)</label><br>
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
                                        <textarea placeholder="Reviews" name="comment" class="com-area" rows="6" cols="50"></textarea>
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
                                <p>Please <a href="<?php echo $login_comment_redirect_url; ?>" style="color: #007bff;">log in</a> or <a href="register.php" style="color: #007bff;">register</a> to rate and comment.</p>
                            </fieldset>
                        <?php endif; ?>
                    </div>

                    <!-- Display Comments -->
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
                        <legend style="font-family:'Ubuntu'; font-size:17px; font-weight: bold;">POPULAR RECIPES</legend>
                        <?php
                            $sql_popular = "SELECT P.Title, P.Permalink, P.Recipe_image, COALESCE(AVG(R.Rating), 0) AS AvgRating 
                                            FROM product P 
                                            LEFT JOIN Rate R ON P.RecipeID = R.RecipeID 
                                            GROUP BY P.RecipeID 
                                            ORDER BY AvgRating DESC, P.Created_timestamp DESC 
                                            LIMIT 6";
                            $p_result = $conn->query($sql_popular);

                            if ($p_result && $p_result->num_rows > 0) {
                                while ($row_pop = $p_result->fetch_assoc()) { ?>
                        <div class="popular-grid">
                            <div class="popu-top">
                                <img class="popu-img" src="images/<?php echo htmlspecialchars($row_pop['Recipe_image']); ?>" alt="<?php echo htmlspecialchars($row_pop['Title']); ?>">
                                <div class="popu-bt">
                                    <?php echo htmlspecialchars($row_pop['Title']); ?> <br><br>
                                    <a class="popu-link" href="singleitem.php?Permalink=<?php echo urlencode($row_pop['Permalink']); ?>">View Recipe</a>
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

            </div>
        <?php endif; ?>
    </main>

    <?php include_once('includes/footer.php');?>

    <script>
    $(document).ready(function () {
        const currentPermalink = '<?php echo ($permalink_db ? urlencode($permalink_db) : ""); ?>';
        let newUrl = window.location.pathname;
        if (currentPermalink) {
            newUrl += '?Permalink=' + currentPermalink;
        }
        if (window.location.search.includes('feedback=success') || window.location.search.includes('cart_auto_attempt=1') || window.location.search.includes('bookmark_action_status=1') || window.location.search.includes('bookmark_auto_attempt=1')) {
            window.history.replaceState({ path: newUrl }, '', newUrl);
        }
    });
    </script>
</body>
</html>
<?php
if ($conn) {
    $conn->close();
}
?>