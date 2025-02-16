<?php
session_start();
include 'connection.php';
include 'functions.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];

// Fetch user details
$query = "SELECT * FROM registration WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // Call the image upload function if a new image is uploaded
    $profilePic = $user_data['profilepic']; // Keep old image if no new upload
    if (!empty($_FILES['image']['name'])) {
        $new_image = imageupload($conn);
        if ($new_image) {
            $profilePic = $new_image;
        }
    }

    // Update user data in database
    $sql = "UPDATE registration SET 
                firstName='$firstName', 
                lastName='$lastName', 
                phone='$phone', 
                email='$email', 
                address='$address', 
                profilepic='$profilePic' 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Profile updated successfully!'); window.location='profile.php';</script>";
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}

// Handle recipe deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recipe'])) {
    $recipe_id = mysqli_real_escape_string($conn, $_POST['recipe_id']);
    $delete_query = "DELETE FROM recipes WHERE recipe_id = '$recipe_id' AND user_id = '$id'";
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>alert('Recipe deleted successfully!'); window.location='profile.php';</script>";
    } else {
        echo "Error deleting recipe: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/profile.css">
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
        <ul class="nav-links">
            <li class="profile-dropdown">
                <button class="profile-btn">
                    <i class="fas fa-user-circle"></i>
                </button>
                <div class="dropdown-content">
                    <a href="#" id="change-password-btn">
                        <i class="fas fa-key"></i>
                        Change Password
                    </a>
                    <form action="logout.php" method="POST" id="logout-form">
                        <button type="submit" class="dropdown-btn">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <div class="page-wrapper">
        <div class="container">
            <div class="profile-card">
                <button class="edit-btn" id="edit-profile-btn">
                    <i class="fas fa-edit"></i>
                </button>
                <div class="profile-header">
                    <div class="profile-image-container">
                        <img src="<?php echo $user_data['profilepic'] ?: 'images/default-avatar.png'; ?>" alt="Profile Picture" class="profile-image" id="profile-image">
                        <form method="post" enctype="multipart/form-data" class="upload-form" id="upload-form">
                            <input type="file" name="file" id="file" class="file-input">
                        </form>
                    </div>
                    <h2>Hello, <span class="user-name"><?php echo $user_data['firstName'] . ' ' . $user_data['lastName'] ?: 'User'; ?></span>!</h2>
                </div>
                <div class="contact-info">
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span><?php echo $user_data['firstName'] . ' ' . $user_data['lastName'] ?: 'N/A'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span><?php echo $user_data['phone'] ?: 'N/A'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span><?php echo $user_data['email'] ?: 'N/A'; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Address:</span>
                        <span><?php echo $user_data['address'] ?: 'N/A'; ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="user-recipes animate__animated animate__fadeInUp">
            <h2>My Shared Recipes</h2>
            <div class="recipe-grid">
                <?php
                // Fetch user's recipes from the database (max 3)
                $recipe_query = "SELECT * FROM recipes WHERE user_id = '{$_SESSION['id']}' LIMIT 3";
                $recipe_result = mysqli_query($conn, $recipe_query);

                if (mysqli_num_rows($recipe_result) > 0) {
                    while ($recipe = mysqli_fetch_assoc($recipe_result)) {
                        echo '<div class="recipe-card animate__animated animate__fadeIn">';
                        echo '<form method="POST" action="profile.php" class="delete-form">';
                        echo '<input type="hidden" name="recipe_id" value="' . $recipe['recipe_id'] . '">';
                        echo '<button type="submit" name="delete_recipe" class="delete-btn"><i class="fas fa-trash-alt"></i></button>';
                        echo '</form>';
                        echo '<img src="' . ($recipe['image'] ?: 'images/background.jpg') . '" alt="' . $recipe['title'] . '" class="recipe-image">';
                        echo '<h3>' . $recipe['title'] . '</h3>';
                        echo '<a href="view_recipe.php?id=' . $recipe['recipe_id'] . '" class="view-recipe-btn">View Recipe</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="no-recipes">You haven\'t shared any recipes yet.</p>';
                }
                ?>
            </div>
            <a href="add_recipe.php" class="add-recipe-btn animate__animated animate__pulse animate__infinite">
                <i class="fas fa-plus"></i> Add New Recipe
            </a>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="password-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Change Password</h2>
            <form id="change-password-form">
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <input type="password" id="current-password" name="current-password" required>
                </div>
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new-password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                </div>
                <button type="submit" class="submit-btn">Change Password</button>
            </form>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="edit-profile-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Profile</h2>
            <form id="edit-profile-form" method="POST" action="profile.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="edit-first-name">First Name</label>
                    <input type="text" id="edit-first-name" name="firstName" value="<?php echo htmlspecialchars($user_data['firstName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-last-name">Last Name</label>
                    <input type="text" id="edit-last-name" name="lastName" value="<?php echo htmlspecialchars($user_data['lastName']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-phone">Phone</label>
                    <input type="text" id="edit-phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-email">Email</label>
                    <input type="email" id="edit-email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="edit-address">Address</label>
                    <input type="text" id="edit-address" name="address" value="<?php echo htmlspecialchars($user_data['address']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="image">Profile Picture</label>
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
                <button type="submit" class="submit-btn" name="update_profile">Save Changes</button>
            </form>
        </div>
    </div>

    <script src="js/profile.js"></script>
</body>
</html>