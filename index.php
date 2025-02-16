<?php
include('connection.php');
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Insert data into database
    $sql = "INSERT INTO contact (contact_id, name, email, subject, message) VALUES (null, '$name', '$email', '$subject', '$message')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('New record created successfully');</script>";
    } else {
        echo "<script>alert('Error: " . $sql . " - " . mysqli_error($conn) . "');</script>";
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeShare</title>
    <link rel="stylesheet" href="css/index.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</head>
<body>
    <!--Header-->
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo1.png" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="#about">About Us</a></li>
            <li><a href="#recipes">Recipes</a></li>
            <li><a href="feed.php">Feed</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <a href="login.php" class="login-button">Login</a>
    </nav>

    <main>
        <section id="home" class="hero-section">
            <div class="hero-content">
                <h1 class="animate-text">Welcome to RecipeShare.</h1>
                <p class="animate-text">Discover, Share, and Enjoy Delicious Recipes from Around the World!</p>
                <a href="#about" class="cta-button">Learn More</a>
            </div>
            <div class="hero-overlay"></div>
        </section>

        <section id="about" class="about-section">
            <h2 class="section-title">About Us</h2>
            <div class="company-info">
                <div class="info-box fade-in">
                    <i class="fas fa-lightbulb icon-feature"></i>
                    <h3>Our Story</h3>
                    <p>At RecipeShare, we believe that cooking is more than just a necessity—it's a way to connect with others and share the joy of food. Join our community and explore a world of culinary delights.</p>
                </div>
                <div class="info-box fade-in">
                    <i class="fas fa-rocket icon-feature"></i>
                    <h3>Mission</h3>
                    <p>Our mission is to provide a platform where food enthusiasts can share their favorite recipes, discover new dishes, and connect with fellow food lovers. We aim to inspire creativity and bring people together through the love of cooking.</p>
                </div>
                <div class="info-box fade-in">
                    <i class="fas fa-eye icon-feature"></i>
                    <h3>Vision</h3>
                    <p>Our vision is to be the leading recipe-sharing platform known for our vibrant community, diverse recipes, and commitment to culinary excellence. We strive to make cooking accessible and enjoyable for everyone.</p>
                </div>
            </div>
        </section>

        <section id="recipes" class="recipes-section">
            <h2 class="section-title">Popular Recipes</h2>
            <div class="recipes-list">
                <div class="recipe-item fade-in">
                    <div class="recipe-image">
                        <img src="images/Spaghetti.png" alt="Spaghetti">
                    </div>
                    <div class="recipe-info">
                        <h3>Spaghetti Bolognese</h3>
                        <p class="description">Classic, hearty, flavorful.</p>
                        <p class="details">A traditional Italian pasta dish with a rich and savory meat sauce.</p>
                    </div>
                </div>
                <div class="recipe-item fade-in">
                    <div class="recipe-image">
                        <img src="images/pancakes.png" alt="Pancakes">
                    </div>
                    <div class="recipe-info">
                        <h3>Fluffy Pancakes</h3>
                        <p class="description">Light, airy, delicious.</p>
                        <p class="details">Perfectly fluffy pancakes that are a breakfast favorite for all ages.</p>
                    </div>
                </div>
                <div class="recipe-item fade-in">
                    <div class="recipe-image">
                        <img src="images/sushi.jpg" alt="Sushi">
                    </div>
                    <div class="recipe-info">
                        <h3>Homemade Sushi</h3>
                        <p class="description">Fresh, vibrant, healthy.</p>
                        <p class="details">Create your own sushi rolls with fresh ingredients and bold flavors.</p>
                    </div>
                </div>
                <div class="recipe-item fade-in">
                    <div class="recipe-image">
                        <img src="images/cake.jpg" alt="Chocolate Cake">
                    </div>
                    <div class="recipe-info">
                        <h3>Chocolate Cake</h3>
                        <p class="description">Rich, moist, decadent.</p>
                        <p class="details">Indulge in a slice of this heavenly chocolate cake, perfect for any occasion.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="contact-section">
            <h2 class="section-title">Get in Touch</h2>
            <form id="feedback-form" class="fade-in" method="POST" action="index.php">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit">Submit</button>
            </form>
            <div id="submission-message" class="hidden">Thank you for your message! We'll get back to you soon.</div>
        </section>
    </main>

    <!--Footer-->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-col">
                    <h4>About Us</h4>
                    <p>RecipeShare is dedicated to bringing people together through the joy of cooking. Discover, share, and enjoy delicious recipes from around the world!</p>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <ul class="nav-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="feed.php">Feed</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© 2023 RecipeShare. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/index.js"></script>
</body>
</html>