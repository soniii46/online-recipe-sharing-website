<!-- footer -->
<style>
    /* Footer placeholder styling */
footer {
    background-color: #222;
    color: #fff;
    padding: 20px;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .about .main {
        flex-direction: column;
        text-align: center;
    }

    .box {
        flex-direction: column;
        align-items: center;
    }
}

/* Footer styles */
.footer {
    background-color: #333;
    color: #fff;
    padding: 50px 20px;
    margin-top: 50px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 30px;
}

.footer-content {
    flex: 1;
    min-width: 250px;
    margin-bottom: 20px;
}

.footer-content h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #ff6347; /* Change this to match your branding */
}

.footer-content p {
    margin-bottom: 10px;
    font-size: 1rem;
}

.footer-content a {
    color: #fff;
    text-decoration: none;
    font-weight: bold;
}

.footer-content a:hover {
    color: #ff6347;
}

.lists {
    list-style-type: none;
    padding: 0;
}

.lists li {
    margin-bottom: 10px;
}

.lists li a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
}

.lists li a:hover {
    color: #ff6347;
}

.social-icons {
    list-style-type: none;
    padding: 0;
    display: flex;
    gap: 20px;
}

.social-icons li {
    font-size: 1.5rem;
}

.social-icons li a {
    color: #fff;
    text-decoration: none;
    transition: color 0.3s;
}

.social-icons li a:hover {
    color: #ff6347;
}

/* Bottom Bar */
.bottom-bar {
    background-color: #222;
    text-align: center;
    padding: 15px 20px;
    font-size: 1rem;
}

.bottom-bar a {
    color: #ff6347;
    text-decoration: none;
    font-weight: bold;
}

.bottom-bar a:hover {
    color: #fff;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
        align-items: center;
    }
    
    .footer-content {
        text-align: center;
    }
}
</style>
<div class="footer">
    <div class="container">
        <div class="footer-content">
            <h3>Contact Us</h3>
                <p>Email: <a href="#">soniii@gmail.com</a></p>
                <p>Phone: +977-9823567360</p>
                <p>Address: Teku, Ktm</p>
        </div>
        <div class="footer-content">
            <h3>Quick Links</h3>
                <ul class="lists">
                    <li><a href="menu.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="recipe.php">Recipes</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
        </div>
        <div class="footer-content">
            <h3>Follow Us</h3>
            <ul class="social-icons">
                <li><a href=""><i class='bx bxl-facebook'></i></a></li>
                <li><a href="#"><i class='bx bxl-instagram'></i></a></li>
                <li><a href="#"><i class='bx bxl-twitter' ></i></a></li>
                <li><a href="#"><i class='bx bxl-tiktok'></i></a></li>
            </ul>
        </div>
    </div>
        <div class="bottom-bar">
        <p>&copy; 2024 TRADITIONAL FAMILY RECIPES. All Rights Reserved.<br> DEVELOPED BY- <a href="#">SONIVA
                MAHARJAN</a></p>
        </div>
</div>