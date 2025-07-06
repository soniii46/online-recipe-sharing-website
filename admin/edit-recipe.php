
<?php 
    include('../includes/dbconnection.php');

    $Permalink = $_GET['Permalink'];
    $sql = "SELECT * FROM product WHERE Permalink = '$Permalink'";
    $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $RecipeID = $row['RecipeID'];
                $Title = $row['Title'];
                $Category = $row['CategoryId']; 
		        $Permalink = $row['Permalink'];
                $R_description = $row['R_description'];
                $Cook_time = $row['Cook_time'];
                $Difficulty = $row['Difficulty'];
                $No_of_servings = $row['No_of_servings'];
                $Created_timestamp = $row['Created_timestamp'];
                $Recipe_image = $row['Recipe_image'];
                $Price = $row['Price'];
            }
        }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />

    <link rel="stylesheet" href="../CSS/admincss.css" />

  
    <script src="https://kit.fontawesome.com/0ba0f621bd.js" crossorigin="anonymous"></script>

 <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Montserrat|Ubuntu" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Glory:wght@300&display=swap" rel="stylesheet">
    <title>Edit Page</title>
    <style>
        .cancel-btn {
    background-color: #44bd32; /* Green background */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cancel-btn:hover {
    background-color: #2ecc71; /* Lighter green on hover */
}

.cancel-btn a {
    text-decoration: none;
    color: white;
    display: block;
    width: 100%;
    height: 100%;
}

        </style>

</head>

<body>
<section id="menu">
        <div class="logo">
            <h2>Online Recipes</h2>
        </div>
        <div class="items">
            <li><i class="fa-solid fa-chart-bar"></i><a href="dash.php">Dashboard</a></li>
            <!-- <li><i class="fa-solid fa-chart-bar"></i><a href="add_category.php">Category</a></li> -->
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Reviews</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="index.php">Home</a></li>

        </div>
    </section>
            <div class="form-container">
            <form action="update-recipe.php" method="POST" enctype="multipart/form-data">
                <h3>Edit Recipe</h3>
                <label>Category ID:</label>
                <input type="text" name="category" value="<?php echo $Category; ?>"  required>

                <label>Title:</label>
                <input type="text" name="title" value="<?php echo $Title; ?>" required> 

                <label>Permalink:</label>
                <input type="text" name="p_link" value="<?php echo $Permalink; ?>" required> 
            
                <label>Description:<label>
                    <textarea name="description"><?php echo $R_description; ?></textarea>
                        
                <label>Cooking Time:</label>
                <input type="text" class="cook_time_text" name="cook_time" value="<?php echo $Cook_time; ?>"
                    placeholder="30 min">

                <label>Difficulty:</label>
                <select class="diff" name="difficulty">
                    <option value="Easy" <?php if($Difficulty=="Easy") echo 'selected="selected"'; ?>>
                        Easy </option>
                    <option value="Medium" <?php if($Difficulty=="Medium") echo 'selected="selected"'; ?>>
                        Medium
                    </option>
                    <option value="Hard" <?php if($Difficulty=="Hard") echo 'selected="selected"'; ?>>
                        Hard </option>
                </select>

                <label>Serves:</label>
                <input type="text" class="serves_text" name="serves" value="<?php echo $No_of_servings; ?>"
                    size="15">
            

                <label>Created:</label>
                <input type="text" class="create_text" name="created" value="<?php echo $Created_timestamp; ?>"
                    readonly> <br>
                <br>

                <label>Upload Image:</label>
                    <label class="image-upload" name="image">
                        <input type="text" id="image" name="image" value="<?php echo $Recipe_image ?>"/>
                    </label>

                <label>Price:</label>
                <input type="text" class="price_text" name="price" value="<?php echo $Price; ?>"><br>
                

                <input type="submit" name="submit" class="submit-btn" value="Update">
                
    </form>
    <button name="cancel" class="cancel-btn" onclick="return confirm('Do you want to cancel it?');">
    <a href="viewProduct1.php">Cancel</a>
</button>

</div>
</body>

</html>