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
            <h2>Online Recipes</h2>
        </div>
        <div class="items">
        <li><i class="fa-solid fa-chart-bar"></i><a href="adminside.php">Dashboard</a></li>
            <!-- <li><i class="fa-solid fa-chart-bar"></i><a href="add_category.php">Category</a></li> -->
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Recipes</a></li>
            <li><i class="fa-solid fa-circle-plus"></i><a href="add-main.php">Add Cuisines</a></li>
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
                <h3>Add Cuisine Recipes</h3>
                Category ID: <input type="text" name="category"  placeholder="Enter category id" required>
                <br><br><br>
                Title: <input type="text" name="title"placeholder="Recipe name" required> <br><br><br>
                Permalink: <input type="text" name="permalink" placeholder="Enter Permalink" required> <br><br>
                Description: <textarea name="description" rows="5" cols="50" placeholder="Enter description" required></textarea> <br> <br> 

                <label class="cook_time_lb">Cooking Time:</label>
                <input type="text" class="cook_time_text" name="cook_time" placeholder="30 min"> <br><br>

                <!-- <label class=" ingre_lb">Ingredients:</label>
                <textarea class="ingre_text" name="ingredients" rows="10" cols="30" placeholder="enter ingredients" required></textarea> -->

                <label class="diff_lb">Difficulty:</label>
                <select class="diff" name="difficulty">
                    <option value="Easy"> Easy </option>
                    <option value="Medium"> Medium </option>
                    <option value="Hard"> Hard </option>
                </select>

                <label class="serve_lb">Serves:</label>
                <input type="text" class="serves_text" name="serves" placeholder="Enter number of servings" required> <br> <br>

                <label class="create_lb">Created:</label>
                <input type="text" class="create_text" name="created" value='<?php echo date('Y-m-d h:m:s');?>'
                    readonly>
                <br><br>
                <label class="upload_lb">Upload Image:</label>
                <label class="image-upload" name="image">
                    <input type="file" id="image" name="image"  onchange="preview()" />
                    <!-- <img id="frame" class="image-pre" src="img/image-upload.png" width="50px" height="50px" /> -->
                </label>
                <label class="price_lb">Price:</label>
                <input type="text" class="price_text" min="0" name="price" placeholder="Enter price" required> <br> <br>
                
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
    // $ingred = $_POST["ingredients"];
    $diff = $_POST["difficulty"];
    $serves = $_POST["serves"];
    $price = $_POST["price"];
    $created = $_POST["created"];
    // $method = $_POST["method"];
    // $picture = $_FILES['picture'];
    //     $picture = '';
    //     if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
    //         $target_dir = "./img/";
    //         $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    //         move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);
    //         $picture = $target_file;
    //     }

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
        $query = "INSERT INTO main (CategoryId, Title, Permalink, R_description, Cook_time,Difficulty, No_of_servings, Created_timestamp,Recipe_image,Price) VALUES ('$category', '$title', '$permalink', '$description','$cook_time','$diff', '$serves', '$created','$img_name','$price' )";

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