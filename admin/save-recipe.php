<?php 
    include('../includes/dbconnection.php');
?>

<?php 
if (isset($_POST['Submit'])) {
    $category = $_POST["category"];
    $title = $_POST["title"];
    $permalink = $_POST["permalink"];
    $description = $_POST["description"];
    $cook_time = $_POST["cook_time"];
    $diff = $_POST["difficulty"];
    $serves = $_POST["serves"];
    $price = $_POST["price"];
    $created = $_POST["created"];

    $img_dir = "../images/";  
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
        $query = "INSERT INTO product (CategoryId, Title, Permalink, R_description, Cook_time,Difficulty, No_of_servings, Created_timestamp,Recipe_image,Price) VALUES ('$category', '$title', '$permalink', '$description','$cook_time','$diff', '$serves', '$created','$img_name','$price' )";

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