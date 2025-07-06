
<?php
include('../includes/dbconnection.php');

if(isset($_POST['category_submit'])){
    $categoryName = $_POST['category_name'];
    $categoryId = $_POST['category_id'];
    $sql = "INSERT INTO category(category_id,category_name)VALUES('$categoryId','$categoryName')";
    if(mysqli_query($conn,$sql)){
        echo "<script> alert('New record created succesfully')</script>";
    }else{
        echo "Error". $sql . "</br" .$conn->error;
    }
}
$conn->close();
?>

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
            <li><i class="fa-solid fa-circle-plus"></i><a href="addProduct.php">Add Products</a></li>
            <li><i class="fa-solid fa-eye"></i><a href="viewProduct1.php">View Products</a></li>
            <li><i class="fa-solid fa-users"></i><a href="userview.php">Users</a></li>
            <li><i class="fa-solid fa-arrow-up-wide-short"></i><a href="orderview.php">Orders</a></li>
            <li><i class="fas fa-sign-out-alt"></i><a href="index.php">Home</a></li>

        </div>
    </section>
    
        <section id="interface">

    <form action="" method="post" class="add-product-form" >
              <div class="card-body">
                  <div class="form-group">
                  <label for = "category_id">Category id</label>
                    <input type="text" placeholder="Enter category id" name="category_id">

                      <!-- <label for="categoryId" >Category ID</label>
                      <input type="text" class="form-control" id="categoryId" name="categoryId" placeholder="Enter category id"> -->
                  </div><br><br>
                  <div class="form-group">
                  <label for = "category_name">Category name</label>
    <input type="text" placeholder="Enter category name" name="category_name">

                      <!-- <label for="categoryName">Category Name</label>
                      <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category name"> -->
                  </div>
              </div>
              <!-- /.card-body -->
      
              <div class="card-footer">
                  <!-- <button type="submit" class="btn btn-primary" name="category_btn">Submit</button> -->

                  <button type="submit"  class="btn btn-primary" name="category_submit">Submit</button>

              </div>
          </form>
    </section>
</body>

</html>




<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<form action=""method="post">
    <label for = "category_id">Category id</label>
    <input type="text" placeholder="Enter category id" name="category_id">
    
    <label for = "category_name">Category name</label>
    <input type="text" placeholder="Enter category name" name="category_name">

    <button type="submit" name="category_submit">Submit</button>
    
</form>
    
</body>
</html> -->