<?php
include 'connection.php';
include 'functions.php';

if (!isset($_GET['id'])) {
    header("Location: profile.php");
    exit();
}

$recipe_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch recipe details
$query = "SELECT * FROM recipes WHERE recipe_id = '$recipe_id' LIMIT 1";
$result = mysqli_query($conn, $query);
$recipe = mysqli_fetch_assoc($result);

if (!$recipe) {
    echo "<script>alert('Recipe not found.'); window.location.href = 'profile.php';</script>";
    exit();
}

// Fetch number of likes
$likes_query = "SELECT COUNT(*) AS likes_count FROM likes WHERE recipe_id = '$recipe_id'";
$likes_result = mysqli_query($conn, $likes_query);
$likes_data = mysqli_fetch_assoc($likes_result);
$likes_count = $likes_data['likes_count'];

// Check if the user has already liked the recipe
session_start();
$user_id = $_SESSION['id'] ?? null;
$user_liked_query = "SELECT * FROM likes WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
$user_liked_result = mysqli_query($conn, $user_liked_query);
$user_liked = mysqli_num_rows($user_liked_result) > 0;

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);
    $insert_comment_query = "INSERT INTO comments (user_id, recipe_id, comment_text) VALUES ('$user_id', '$recipe_id', '$comment_text')";
    mysqli_query($conn, $insert_comment_query);
}

// Handle comment deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment'])) {
    $comment_id = mysqli_real_escape_string($conn, $_POST['comment_id']);
    $delete_comment_query = "DELETE FROM comments WHERE id = '$comment_id' AND user_id = '$user_id'";
    mysqli_query($conn, $delete_comment_query);
}

// Fetch comments
$comments_query = "SELECT comments.id, comments.comment_text, comments.created_at, comments.user_id, registration.firstName, registration.lastName FROM comments JOIN registration ON comments.user_id = registration.id WHERE comments.recipe_id = '$recipe_id' ORDER BY comments.created_at DESC";
$comments_result = mysqli_query($conn, $comments_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['title']); ?></title>
    <link rel="stylesheet" href="css/profile.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .container {
            margin: 0 auto;
            width: 60%;
            padding: 2rem;
            background-color: #f4f4f4;
            border-radius: 10px;
            overflow: hidden; /* Ensure content does not overflow */
            position: relative;
        }
        .recipe-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .recipe-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        .recipe-description {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            word-wrap: break-word; /* Ensure long words break to the next line */
        }
        .recipe-ingredients,
        .recipe-instructions {
            margin-bottom: 1.5rem;
        }
        .recipe-ingredients h3,
        .recipe-instructions h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }
        .recipe-ingredients ul,
        .recipe-instructions ol {
            padding-left: 20px;
        }
        .recipe-ingredients ul {
            list-style-type: disc;
        }
        .recipe-instructions ol {
            list-style-type: decimal;
        }
        .back-btn {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .back-btn i {
            margin-right: 0.5rem;
        }
        .back-btn:hover {
            background-color: #2980b9;
        }
        .likes-count {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-top: 1rem;
            display: flex;
            align-items: center;
        }
        .like-icon {
            cursor: pointer;
            margin-left: 0.5rem;
            transition: color 0.3s ease;
            color: red;
        }
        .like-icon.liked {
            color: var(--primary-color);
        }
        .likes-count span {
            margin-left: 0.5rem; /* Add space between the icon and the likes count */
        }
        .comments-section {
            margin-top: 2rem;
        }
        .comment-form {
            margin-bottom: 2rem;
        }
        .comment-form textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
            resize: vertical;
        }
        .comment-form button {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            border-radius: 4px;
            margin-top: 0.5rem;
        }
        .comment-form button:hover {
            background-color: #2980b9;
        }
        .comment {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background-color: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .comment .comment-author {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .comment .comment-date {
            font-size: 0.875rem;
            color: #777;
            margin-bottom: 0.5rem;
        }
        .comment .comment-text {
            font-size: 1rem;
        }
        .delete-comment-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .delete-comment-btn:hover {
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
        <button class="back-btn" onclick="window.history.back();">
            <i class="fas fa-arrow-left"></i>
        </button>
        <h1 class="recipe-title"><?php echo htmlspecialchars($recipe['title']); ?></h1>
        <?php
            echo '<img src="' . ($recipe['image'] ?: 'images/background.jpg') . '" alt="' . $recipe['title'] . '" class="recipe-image">';
        ?>
        <p class="recipe-description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>
        <div class="recipe-ingredients">
            <h3>Ingredients</h3>
            <ul>
                <?php
                $ingredients = explode(',', $recipe['ingredients']);
                foreach ($ingredients as $ingredient) {
                    echo '<li>' . htmlspecialchars(trim($ingredient)) . '</li>';
                }
                ?>
            </ul>
        </div>
        <div class="recipe-instructions">
            <h3>Instructions</h3>
            <ol>
                <?php
                $steps = explode(',', $recipe['steps']);
                foreach ($steps as $step) {
                    echo '<li>' . htmlspecialchars(trim($step)) . '</li>';
                }
                ?>
            </ol>
        </div>
        <div class="likes-count">
            <i class="fas fa-thumbs-up like-icon <?php echo $user_liked ? 'liked' : ''; ?>" id="like-icon"></i>
            <span id="likes-count"><?php echo $likes_count; ?></span> Likes
        </div>
        <div class="comments-section">
            <h3>Comments</h3>
            <form class="comment-form" method="POST" action="view_recipe.php?id=<?php echo $recipe_id; ?>">
                <textarea name="comment_text" rows="4" placeholder="Add a comment..." required></textarea>
                <button type="submit" name="submit_comment">Submit</button>
            </form>
            <?php
            if (mysqli_num_rows($comments_result) > 0) {
                while ($comment = mysqli_fetch_assoc($comments_result)) {
                    echo '<div class="comment">';
                    echo '<div class="comment-author">' . htmlspecialchars($comment['firstName'] . ' ' . $comment['lastName']) . '</div>';
                    echo '<div class="comment-date">' . htmlspecialchars($comment['created_at']) . '</div>';
                    echo '<div class="comment-text">' . nl2br(htmlspecialchars($comment['comment_text'])) . '</div>';
                    if ($comment['user_id'] == $user_id) {
                        echo '<form method="POST" action="view_recipe.php?id=' . $recipe_id . '">';
                        echo '<input type="hidden" name="comment_id" value="' . $comment['id'] . '">';
                        echo '<button type="submit" name="delete_comment" class="delete-comment-btn"><i class="fas fa-trash-alt"></i></button>';
                        echo '</form>';
                    }
                    echo '</div>';
                }
            } else {
                echo '<p>No comments yet. Be the first to comment!</p>';
            }
            ?>
        </div>
    </div>

    <script>
        document.getElementById('like-icon').addEventListener('click', function() {
            const likeIcon = this;
            const recipeId = <?php echo $recipe_id; ?>;
            const liked = likeIcon.classList.contains('liked');

            fetch('like_recipe.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `recipe_id=${recipeId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const likesCount = document.getElementById('likes-count');
                    let currentLikes = parseInt(likesCount.textContent);

                    if (liked) {
                        likesCount.textContent = currentLikes - 1;
                        likeIcon.classList.remove('liked');
                        likeIcon.style.color = 'red';
                    } else {
                        likesCount.textContent = currentLikes + 1;
                        likeIcon.classList.add('liked');
                        likeIcon.style.color = 'var(--primary-color)';
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
