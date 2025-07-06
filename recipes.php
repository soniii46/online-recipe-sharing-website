<?php 
    include('includes/dbconnection.php');
    session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" href="CSS/recipe-page.css" />
    <link rel="stylesheet" href="CSS/style.css">
    <!-- <link rel="stylesheet" href="../styles/theme.css" /> -->

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">

    <title>View All Recipes</title>
</head>

<body>

<?php include('includes\header.php');?>

    <main>
        <div class="recipe-top">
            <label class="recipe-lb">RECIPES</label>
        </div>

        <!-- ALL Recipes -->
        <div class="recipes">
            <div class="recipe-container">
                <?php
                $sql = "SELECT * FROM product";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<div class='row'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<div class='column'>";
                        ?>
                <div class="box margin-bt">
                    <div>
                        <p class="box-text">

                            <a href="user/view-recipe.php?Permalink=<?php echo $row['Permalink']?>">
                                <img class="recipe-img" src="images/<?php echo $row['Recipe_image']?>">
                            </a>
                            <hr>

                            <a class="r-name" href="user/view-recipe.php?Permalink=<?php echo $row['Permalink']?>">
                                <h4 class="box-title"><?php echo $row['Title']?></h4>
                            </a>
                            <a class="box-md r-name" href="#"><i class="fas fa-utensils"></i>
                                <?php echo $row['CategoryId']?></a>
                               <a class="box-md p-name" href="#"> Rs.<?php echo $row['Price']?></a>
                        <div id="time" title="Cook_time"><i class="far fa-clock"></i>
                            <?php echo $row['Cook_time']?></div>
                        </p>
                    </div>
                </div>
                <?php
                        echo "</div>";
                    }
                    echo "</div>";
                }
            ?>
            </div>
        </div>
        
    </main>
  
<?php include_once('includes/footer.php');?>
<script>
$(document).ready(function () {
    $("#searchQuery").on("keyup", function () {
        const query = $(this).val();

        if (query !== "") {
            $.ajax({
                url: "search.php", // PHP script to handle the search
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