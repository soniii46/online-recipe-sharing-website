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
    <section id="menu">
        <div class="logo">
            <h2 style="color:white;">Online Recipes</h2>
        </div>
        <div class="items">
        <li><i class="fa-solid fa-chart-bar"></i><a href="dash.php">Dashboard</a></li>
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Recipes</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-message"></i><a href="msg-view.php">Messages</a></li>
            <li><i class="fa-solid fa-star"></i><a href="com-rate.php">Rating</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Orders</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="admin-logout.php">Logout</a></li>

        </div>
    </section>

    <div class="form-container">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <h3>Add Recipes</h3>
                <label>Category ID:</label>
                <input type="text" name="category"  placeholder="Enter category id" required>
        
                <label>Title:</label>
                <input type="text" name="title"placeholder="Recipe name" required> 
                <label>Permalink:</label>
                <input type="text" name="permalink" placeholder="Enter Permalink" required>
                <label>Description:</label>
                <textarea name="description" placeholder="Enter description" required></textarea> 

                <label class="cook_time_lb">Cooking Time:</label>
                <input type="text" class="cook_time_text" name="cook_time" placeholder="30 min"> <br><br>

                <!-- <label class=" ingre_lb">Ingredients:</label>
                <textarea class="ingre_text" name="ingredients" rows="10" cols="30" placeholder="enter ingredients" required></textarea> -->

                <label>Difficulty:</label>
                <select class="diff" name="difficulty">
                    <option value="Easy"> Easy </option>
                    <option value="Medium"> Medium </option>
                    <option value="Hard"> Hard </option>
                </select>

                <label>Serves:</label>
                <input type="text" class="serves_text" name="serves" placeholder="Enter number of servings" required>

                <label>Created:</label>
                <input type="text" class="create_text" name="created" value='<?php echo date('Y-m-d h:m:s');?>'
                    readonly>
            
                <label>Upload Image:</label>
                <label class="image-upload" name="image">
                    <input type="file" id="image" name="image"  onchange="preview()" />
                    <!-- <img id="frame" class="image-pre" src="img/image-upload.png" width="50px" height="50px" /> -->
                </label>
                <label>Price:</label>
                <input type="text" class="price_text" min="0" name="price" placeholder="Enter price" required>
                
                <!-- <label class="method_lb">Method:</label>
                <textarea class="method_text" name="method" rows="13" cols="40" placeholder="Enter Methods" required></textarea> -->

                <input type="submit" name="submit" class="submit-btn" value="Submit">
            </form>

            <?php 
    include('../includes/dbconnection.php');

if (isset($_POST['submit'])) {
    $category = $_POST["category"];
    $title = $_POST["title"];
    $permalink = $_POST["permalink"];
    $description = $_POST["description"];
    $cook_time = $_POST["cook_time"];
    $diff = $_POST["difficulty"];
    $serves = $_POST["serves"];
    $price = $_POST["price"];
    $created = $_POST["created"];

    $img_dir = "../images/";    // reference from lecture slide
    $image_file = $img_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_file);
    $img_name = basename($_FILES["image"]["name"]);
    
    $ck_query = mysqli_query($conn, "SELECT * FROM product WHERE permalink='$permalink'");

    if(mysqli_num_rows($ck_query ) != 0)
    {
        echo "<script> alert('Permalink already exist!!!') </script>";
    }
    else
    {
        $query = "INSERT INTO product (CategoryId, Title, Permalink, R_description, Cook_time, No_of_servings, 
        Difficulty, Recipe_image, Price, Created_timestamp) VALUES ('$category', '$title', '$permalink', '$description',
         '$cook_time', '$serves', '$diff', '$img_name', '$price', '$created')";

        if(mysqli_query($conn, $query)) {
            echo "<script> alert('Recipe added Successfully!') </script>";
          
        }
        else { 
            echo "<script> alert('Error: Recipe not added') </script>";
        }
        
        mysqli_close($conn);
    }
}

?>
    
</body>

</html>