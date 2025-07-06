
<?php
include('includes/dbconnection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="./CSS/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- <link rel="stylesheet" href="CSS\style.css"> -->
</head>

<body>

 <?php include('includes\header.php');
 ?>
 
<!-- carousel-->
<div class="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/slide1.jpg" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="img/slide2.jpg" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="img/slide3.jpg" alt="Slide 3">
        </div>

    </div>
    <button class="carousel-control prev" onclick="prevSlide()">&#10094;</button>
    <button class="carousel-control next" onclick="nextSlide()">&#10095;</button>
    <div class="carousel-indicators">
        <span class="indicator active" onclick="goToSlide(0)"></span>
        <span class="indicator" onclick="goToSlide(1)"></span>
        <span class="indicator" onclick="goToSlide(2)"></span>

    </div>
</div>
    

<!----About Section Start---------------------------------->
<section class="about">
        <h2>About Us</h2>
        <div class="main">
            <img src="img/20 Indian Thali Ideas.jpg" alt="">
            <div class="about-text">
                <p>Welcome to our recipe website, where culinary inspiration meets delicious creations! Whether you're a
                    seasoned chef or a passionate home cook, we are here to ignite your taste buds and guide you on a
                    delightful culinary journey. Our extensive collection of recipes covers a wide range of cuisines,
                    from comforting classics to innovative fusion dishes. Each recipe is thoughtfully crafted, tested,
                    and presented with step-by-step instructions, ensuring that even the novice cook can create
                    extraordinary meals. We believe that cooking is an art form that brings people together, and our
                    goal is to empower you to explore your culinary creativity and make every meal a masterpiece. So
                    come on in, explore our diverse recipe collection, and let's embark on a delightful gastronomic
                    adventure together!</p>
                    <a href="about.php"><button>Read More</button></a>

            </div>
        </div>
    </section>



<!--------------categories section-------------------------------->
<div class="categories">
        <h2>Categories</h2>
        <div class="box">
            <div class="ca-card">
                <img src="img/maincourse.jpg" alt="">
                <div class="content">
                    <a href="category/maincat1.php"><button>Main Courses</button></a>
                </div>
            </div>
            <div class="ca-card">
                <img src="img/desserts.jpg" alt="">
                <div class="content">
                    <a href="category/dessertcat.php"><button>Desserts</button></a>
                </div>
            </div>
            <div class="ca-card">
                <img src="img/healthyeats.jpg" alt="">
                <div class="content">
                
                    <a href="category/healthycat.php"><button>Healthy Eats</button></a>
                </div>
            </div>
            <div class="ca-card">
                <img src="img/baking.jpg" alt="">
                <div class="content">
                
                    <a href="category/bakecat.php"><button>Baking</button></a>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php');?>

    <!-- script -->
<script src="script/carousel.js"></script>
</body>

</html>