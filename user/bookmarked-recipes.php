<?php
    session_start();
    if (!isset($_SESSION['uname']))
        {
            header("Location: ../login.php");
            exit();
        }
?>

<?php 
    include('../includes/dbconnection.php');
    $Username = $_SESSION['uname'];
    $select_userId = mysqli_query($conn, "SELECT UserID FROM userinfo WHERE Username = '$Username'");
    $userId_row = mysqli_fetch_assoc($select_userId);
    $userId = $userId_row['UserID'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />


    <link rel="stylesheet" href="..\CSS\book.css" />
    <link rel="stylesheet" href="..\CSS\style.css" />



    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>

    <title>Bookmarked recipes</title>
</head>

<body>
<?php include('uheader.php'); ?>
    <div class=grid>
        <div style="margin-top:10px;margin-left: 20px;">
           
            <a href="user-dashboard.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back to dashboard</a>
                <!-- <li><a href="user-dashboard.php">Dashboard</a></li> -->
                <!-- <li><a href="cart.php">Cart</a></li>
                <li><a href="bookmarked-recipes.php">Bookmarked recipes</a></li>
                <li><a href="logout.php">Logout</a></li> -->

          
        </div>

        <main>
            <div class=topic>
                Bookmarked recipes
                <hr>
            </div>


            <div class="public recipes">
                <div class="recipe-container">
                    <?php
                    //  $username = $_SESSION['uname'];
                    //  $select_userId = mysqli_query($conn, "SELECT Username FROM userinfo WHERE Username = '$Username'");
                    //  $userId_row = mysqli_fetch_assoc($select_userId);
                    //  $userId = $userId_row['Username'];

                     $sql = "SELECT * FROM bookmark b 
                     JOIN product r ON b.RecipeID = r.RecipeID 
                     WHERE b.UserID = '$userId'"; 
                        $result = mysqli_query($conn, $sql);

                        if (mysqli_num_rows($result) > 0) {
                            echo "<div class='row'>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                $id = $row['id'];
                                echo "<div class='column'>";
                                ?>
                    <div class="box margin-bt" id="recipe-<?php echo $id; ?>" style="width: 300px;">
                        <div class="box" style="width:300px;">
                            <div>
                                <p class="box-text">
                                    <a href="view-recipe.php?Permalink=<?php echo $row['Permalink']?>">
                                        <img class="recipe-img" src="../images/<?php echo $row['Recipe_image']?>"
                                           >
                                    </a>
                                    <hr>

                                    <a class="r-name" href="view-recipe.php?Permalink=<?php echo $row['Permalink']?>">
                                        <h4 class="box-title"><?php echo $row['Title']?></h4>
                                    </a>
                                    <a class="box-md r-name" href="#">
                                        <i class="fas fa-utensils"></i>
                                        <?php echo $row['CategoryId']?>
                                    </a>
                                <div id="time" title="Cook_time">
                                    <i class="far fa-clock"></i>
                                    <?php echo $row['Cook_time']?>
                                </div>
                                <br>
                                <div class="buttons">
                    <!-- Hidden input to store the bookmark ID to remove -->
                    <input type="hidden" id="removeBookmarkId" value="<?php echo $id; ?>">

                    <!-- Button to trigger AJAX -->
                    <button type="button" class="butn" onclick="removeBookmark(<?php echo $id; ?>)">
                        Remove from bookmarks
                    </button>
                </div>              
                </p>
            </div>
        </div>
    </div>

        <?php
                    echo "</div>";
                }
                echo "</div>";
            }
            else {
                // No bookmarks found
        echo "<h3 style='text-align: center;'>You have no bookmarked recipes</h3>";
    }
        ?>
                </div>
            </div>
           

        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php include('../includes/footer.php'); ?>
    <script>
    // Function to remove bookmark using AJAX
    function removeBookmark(bookmarkId) {
        // Make an AJAX request to remove the bookmark
        $.ajax({
            url: 'remove-book.php', // The PHP file that handles the removal
            type: 'POST',
            data: {
                'id': bookmarkId // Send the bookmark ID to the server
            },
            success: function(response) {
                if(response.trim() === 'success') {
                // Remove the corresponding recipe box (parent of the button)
                $('#recipe-' + bookmarkId).fadeOut(300, function() {
                    $(this).remove(); // Remove the element after fading out
                });
            } else {
                alert('Error: Bookmark not removed.');
            }
        },
        error: function() {
            alert('Something went wrong. Please try again.');
        }
        });
    }
</script>
</body>

</html>