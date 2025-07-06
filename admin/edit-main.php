
<?php 
    include('../includes/dbconnection.php');

    $Permalink = $_GET['Permalink'];
    $sql = "SELECT * FROM main WHERE Permalink = '$Permalink'";
    $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $RecipeID = $row['RecipeID'];
                $Title = $row['Title'];
                $Category = $row['CategoryId']; 
		        $Permalink = $row['Permalink'];
                $R_description = $row['R_description'];
                $Cook_time = $row['Cook_time'];
                $Ingredients = $row['Ingredients'];
                $Difficulty = $row['Difficulty'];
                $No_of_servings = $row['No_of_servings'];
                $Price = $row["Price"];
                $Created_timestamp = $row['Created_timestamp'];
                $Recipe_image = $row['Recipe_image'];
                $Instructions = $row['Instructions'];
            }
        }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="../CSS/recipe.css" />

    <!-- Icons -->
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>

    <!-- <script>
    function preview() {
        frame.src = URL.createObjectURL(event.target.files[0]);
    }
    </script> -->
 <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">

    <title>Edit Page</title>

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
            <li><i class="fa-solid fa-circle-plus"></i><a href="add-main.php">Add Cuisines</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Reviews</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="index.php">Home</a></li>

        </div>
    </section>
            <div class="form-container">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <h3>Edit Recipe</h3>
                <label>Category:</label>
                <input type="text" name="category" value="<?php echo $Category; ?>"  required>
                <br><br>

                <label>Title:</label>
                <input type="text" name="title" value="<?php echo $Title; ?>" required> <br> <br>

                <label>Permalink:</label>
                <input type="text" name="p_link" value="<?php echo $Permalink; ?>" required> <br>
                <br>
                <br>

                <label>Description:<label>
                        <textarea name="description" rows="5" cols="50"><?php echo $R_description; ?></textarea>
                        <br>
                        <br>

                <label class="cook_time_lb">Cooking Time:</label>
                <input type="text" class="cook_time_text" name="cook_time" value="<?php echo $Cook_time; ?>"
                    placeholder="30 min"> <br> <br>

                <label class="ingre_lb">Ingredients:</label>
                <textarea class="ingre_text" name="ingredients" rows="10" cols="30"><?php echo $Ingredients; ?></textarea>

                <label class="diff_lb">Difficulty:</label>
                <select class="diff" name="difficulty">
                    <option value="Easy" <?php if($Difficulty=="Easy") echo 'selected="selected"'; ?>>
                        Easy </option>
                    <option value="Medium" <?php if($Difficulty=="Medium") echo 'selected="selected"'; ?>>
                        Medium
                    </option>
                    <option value="Hard" <?php if($Difficulty=="Hard") echo 'selected="selected"'; ?>>
                        Hard </option>
                </select>

                <label class="serve_lb">Serves:</label>
                <input type="text" class="serves_text" name="serves" value="<?php echo $No_of_servings; ?>"
                    size="15">
                <br>
                <br>

                <label class="create_lb">Created:</label>
                <input type="text" class="create_text" name="created" value="<?php echo $Created_timestamp; ?>"
                    readonly> <br>
                <br>

                <label class="upload_lb">Upload Image:</label>
                    <label class="image-upload" name="image">
                        <input type="text" id="image" name="image" value="<?php echo $Recipe_image ?>"/>
                    </label>

                    <label class="price_lb">Price:</label>
                <input type="text" class="price_text" name="price" value="<?php echo $Price; ?>" /> <br> <br>

                <label class="method_lb">Method:</label>
                <textarea class="method_text" name="method" value="<?php echo $Instructions; ?>" rows="13"
                    cols="40"><?php echo $Instructions ?></textarea>

                <input type="submit" name="submit" class="submit-btn" value="Update">
                
    </form>
    <button name="cancel" class="btn" onclick="return confirm('Do you want to cancel it?');"><a href="viewProduct1.php">Cancel</a></button>
</div>

<?php 

    if(isset($_POST['submit'])) {
        $Title = $_POST['title'];
        $Category = $_POST['category']; 
	    $Permalink = $_POST['p_link'];
        $R_description = $_POST['description'];
        $Cook_time = $_POST['cook_time'];
        $Ingredients = $_POST['ingredients'];
        $Difficulty = $_POST['difficulty'];
        $No_of_servings = $_POST['serves'];
        $Price = $_POST["price"];
        $Created_timestamp = $_POST['created'];
        $Instructions = $_POST['method'];
        $product_picture = $_POST['image'];

    
            $query = "UPDATE main SET Title='$Title', CategoryId='$Category', Permalink='$Permalink', R_description='$R_description',Cook_time = '$Cook_time', Ingredients='$Ingredients', Difficulty='$Difficulty', No_of_servings='$No_of_servings', Price='$Price', Created_timestamp='$Created_timestamp',Recipe_image ='$product_picture', Instructions='$Instructions' WHERE Permalink='$Permalink'";
        
            if (mysqli_query($conn, $query))  {
                echo "<script> alert('Recipe updated successfully!');
                window.location.href = 'viewProduct1.php';
                 </script>";
                
            }
            else {
                echo "Failed to update product.";
            }
    }
?>



</body>

</html>