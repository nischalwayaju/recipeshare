<?php
include 'connection.php';
include 'functions.php';
session_start();

if (!isset($_SESSION['id']) || !isset($_POST['recipe_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$user_id = $_SESSION['id'];
$recipe_id = mysqli_real_escape_string($conn, $_POST['recipe_id']);

// Check if the user has already liked the recipe
$check_query = "SELECT * FROM likes WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    // User has already liked the recipe, so unlike it
    $delete_query = "DELETE FROM likes WHERE user_id = '$user_id' AND recipe_id = '$recipe_id'";
    if (mysqli_query($conn, $delete_query)) {
        echo json_encode(['status' => 'success', 'message' => 'Recipe unliked']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to unlike recipe']);
    }
} else {
    // User has not liked the recipe, so like it
    $insert_query = "INSERT INTO likes (user_id, recipe_id) VALUES ('$user_id', '$recipe_id')";
    if (mysqli_query($conn, $insert_query)) {
        echo json_encode(['status' => 'success', 'message' => 'Recipe liked']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to like recipe']);
    }
}
?>
