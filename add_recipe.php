<?php
include 'config.php';
include("connection.php");
include("functions.php");

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

$user_data = check_login($conn);

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);
    $steps = mysqli_real_escape_string($conn, $_POST['steps']);
    $user_id = $_SESSION['id'];

    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "images/recipes/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Generate a unique filename
            $image_path = $target_dir . uniqid() . "." . $imageFileType;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $image_path)) {
                // File uploaded successfully
            } else {
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    $query = "INSERT INTO recipes (user_id, title, description, ingredients, steps, image) VALUES ('$user_id', '$title', '$description', '$ingredients', '$steps', '$image_path')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Recipe added successfully!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
            background-color:#f4f4f4;
            border-radius:10px;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        textarea {
            min-height: 100px;
        }
        .submit-btn {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .submit-btn:hover {
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
        <h1>Add New Recipe</h1>
        <form action="add_recipe.php" method="POST" enctype="multipart/form-data" id="add-recipe-form">
            <div class="form-group">
                <label for="title">Recipe Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="ingredients">Ingredients (separate by commas)</label>
                <textarea id="ingredients" name="ingredients" placeholder="e.g., 1 cup flour, 2 eggs, 1/2 cup sugar" required></textarea>
            </div>
            <div class="form-group">
                <label for="steps">Steps (separate by commas)</label>
                <textarea id="steps" name="steps" placeholder="e.g., Preheat oven to 350°F, Mix ingredients, Bake for 30 minutes" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Recipe Image</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="submit-btn">Add Recipe</button>
        </form>
    </div>
</body>
</html>