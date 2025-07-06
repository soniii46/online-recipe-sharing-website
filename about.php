
<?php
include('includes/dbconnection.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- <link rel="stylesheet" href="CSS/sty.css"> -->
    <link rel="stylesheet" href="CSS/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Pangolin&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/494f2e7fea.js" crossorigin="anonymous"></script>
<style>
    
/* About Section */
.about-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    padding: 50px 20px;
    background-color: #fff;
}

.about-container img {
    max-width: 500px;
    width: 100%;
    height: auto;
    margin-right: 40px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.about {
    max-width: 600px;
    padding: 20px;
    border-radius: 10px;
}

.about h1 {
    font-size: 36px;
    color: #0b6e4f;
    margin-bottom: 10px;
    text-align:center;
}

.about h2 {
    font-size: 28px;
    color: #444;
    margin-bottom: 10px;
}

.about p {
    font-size: 16px;
    color: #555;
    text-align: justify;
}

/* Testimonial Section */
.testimonial {
    background-color: #e0f7fa;
    padding: 60px 20px;
}

.testimonial .title {
    text-align: center;
    font-size: 32px;
    margin-bottom: 40px;
    color: #0b6e4f;
}

.small-container {
    max-width: 1100px;
    margin: auto;
}

.row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    gap: 20px;
}

.col-3 {
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    padding: 30px 20px;
    text-align: center;
    flex: 1;
    min-width: 250px;
    max-width: 300px;
}

.col-3 i.fa-quote-left {
    font-size: 20px;
    color: #0b6e4f;
    margin-bottom: 10px;
}

.col-3 p {
    font-size: 15px;
    color: #444;
    margin-bottom: 15px;
}

.rating i {
    color: #ffc107;
    margin: 0 2px;
}

.col-3 img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    margin-top: 15px;
    object-fit: cover;
}

.col-3 h3 {
    margin-top: 10px;
    font-size: 18px;
    color: #0b6e4f;
}

/* Responsive */
@media (max-width: 768px) {
    .about-container {
        flex-direction: column;
        text-align: center;
    }

    .about-container img {
        margin: 0 0 20px 0;
    }

    .row {
        flex-direction: column;
        align-items: center;
    }
}
    </style>
</head>
<body>
<?php include('includes\header.php');?>

    <div class="about-container">
        <img src="img/ss.jpg">
        <div class="about" style="background-color:lightcyan;">
        <h1 >About Us</h1>
            <h2>Who We Are</h2><br>
            <p>Home cooks are our heroes—it's as simple as that. OR is a community built by and for kitchen experts: 
                The cooks who will dedicate the weekend to a perfect beef bourguignon but love the simplicity of a
                slow-cooker rendition, too. The bakers who labor over a showstopping 9-layer cake but will just as 
                happily doctor boxed brownies for a decadent weeknight dessert. The entertainers who just want a
                solid snack spread, without tons of dirty dishes at the end of the night. <br><br>
            
                OR was founded in 2019 by <b>Soniva Maharjan</b> as a home cooking blog to record
                her favorite family recipes. Today, OR has grown into a trusted resource 
                for home cooks with more than 3,000 tested recipes, guides, and meal plans, drawing over
                15 million readers each month from around the world. We’re supported by a diverse group of 
                recipe developers, food writers, recipe and product testers, photographers, and other creative professionals.
            </p><br><br>
        </div>
    </div>

    <!-- TESTIMONIAL -->

    <div style="margin-top: 0px  ;" class="testimonial">
                    <div class="small-container">
                        <h2 class="title">What people has to say about us</h2>
                        <div class="row">
                            <div class="col-3">
                                <i class="fa fa-quote-left"></i>
                                <p>As a busy professional, the online recipe management 
                                    system has been a lifesaver. The user-friendly platform and reliable 
                                    delivery have exceeded my expectations.</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <img src="img/user1.jpg" alt="photo of a user1">
                                <h3>Sonika Maharjan</h3>
                            </div>

                            <div class="col-3">
                                <i class="fa fa-quote-left"></i>
                                <p>"The online recipe management system has been a game-changer for me. 
                                    It's so convenient to place orders, and manage my account all in one place.
                                     Highly recommended!"</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <img src="img/user2.jpg" alt="photo of a user1">
                                <h3>Sudip Maharjan </h3>
                            </div>

                            <div class="col-3">
                                <i class="fa fa-quote-left"></i>
                                <p>I love how easy it is to customize my orders and receive fresh baked goods
                                     right to my doorstep. This online system has made my life so much simpler.</p>
                                <div class="rating">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-regular fa-star"></i>
                                </div>
                                <img src="img/user3.jpg" alt="photo of a user1">
                                <h3>Mahima Dangol</h3>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <br>
    
<!-- footer -->
<?php include_once('includes/footer.php');?>

</body>
</html>