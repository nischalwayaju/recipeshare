<?php
include 'connection.php';
include 'functions.php';
session_start();

// Fetch latest recipes
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $query = "SELECT recipes.*, registration.firstName, registration.lastName FROM recipes JOIN registration ON recipes.user_id = registration.id WHERE recipes.title LIKE '%$search_query%' LIMIT 10";
} else {
    $query = "SELECT recipes.*, registration.firstName, registration.lastName FROM recipes JOIN registration ON recipes.user_id = registration.id LIMIT 10";
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Feed</title>
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            background-color: #f4f4f4;
            border-radius: 10px;
        }
        .search-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .search-bar input[type="text"] {
            width: 300px;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            margin-right: 0.5rem; /* Add margin to the right */
        }
        .search-bar button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-bar button:hover {
            background-color: #2980b9;
        }
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }
        .recipe-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }
        .recipe-card:hover {
            transform: translateY(-10px) rotate(2deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        .recipe-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .recipe-card:hover .recipe-image {
            transform: scale(1.1);
        }
        .recipe-info {
            padding: 1.5rem;
        }
        .recipe-info h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }
        .recipe-info p {
            font-size: 1rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        .view-recipe-btn {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        .view-recipe-btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo1.png" alt="Logo">
        </div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="feed.php">Feed</a></li>
            <li><a href="add_recipe.php">Add Recipe</a></li>
        </ul>
    </nav>

    <div class="container animate__animated animate__fadeIn">
        <h1>Recipe Feed</h1>
        <div class="search-bar">
            <form action="feed.php" method="GET">
                <input type="text" name="search" placeholder="Search recipes..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        <div class="recipe-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($recipe = mysqli_fetch_assoc($result)) {
                    echo '<div class="recipe-card">';
                    echo '<img src="' . ($recipe['image'] ?: 'images/background.jpg') . '" alt="' . htmlspecialchars($recipe['title']) . '" class="recipe-image">';
                    echo '<div class="recipe-info">';
                    echo '<h3>' . htmlspecialchars($recipe['title']) . '</h3>';
                    echo '<p>By ' . htmlspecialchars($recipe['firstName'] . ' ' . $recipe['lastName']) . '</p>';
                    echo '<a href="view_recipe.php?id=' . $recipe['recipe_id'] . '" class="view-recipe-btn">View Recipe</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No recipes found.</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
