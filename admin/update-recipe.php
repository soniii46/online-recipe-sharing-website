<?php 
    include('../includes/dbconnection.php');
?>

<?php 

    if(isset($_POST['submit'])) {
        $Title = $_POST['title'];
        $Category = $_POST['category']; 
	    $Permalink = $_POST['p_link'];
        $R_description = $_POST['description'];
        $Cook_time = $_POST['cook_time'];
        $Difficulty = $_POST['difficulty'];
        $No_of_servings = $_POST['serves'];
        $Created_timestamp = $_POST['created'];
        $product_picture = $_POST['image'];
        $product_price =$_POST['price'];

    
            $query = "UPDATE product SET Title='$Title', CategoryId='$Category', Permalink='$Permalink', R_description='$R_description',Cook_time = '$Cook_time', Difficulty='$Difficulty', No_of_servings='$No_of_servings', Created_timestamp='$Created_timestamp',Recipe_image ='$product_picture', Price = '$product_price' WHERE Permalink='$Permalink'";
        
            if (mysqli_query($conn, $query))  {
                echo "<script> alert('Recipe updated successfully!');
                window.location.href = 'viewProduct1.php';
                 </script>";
                
            }
            else {
                echo "Failed to update product.";
            }
    //     }
    }
?>


