<?php
session_start();
include 'connection.php'; // Ensure this initializes $conn properly

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Check if user ID exists in session
    if (!isset($_SESSION['id'])) {
        $_SESSION['message'] = "User not logged in.";
        echo "<script>alert('User not logged in.'); window.location.href = 'profile.php';</script>";
        exit();
    }

    // Validate the new password
    if (strlen($newPassword) < 8 || !preg_match('/[A-Za-z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
        $_SESSION['message'] = "New password must be at least 8 characters long and contain both numbers and letters.";
        echo "<script>alert('New password must be at least 8 characters long and contain both numbers and letters.'); window.location.href = 'profile.php';</script>";
        exit();
    }

    // Fetch the current password from the database
    $id = $_SESSION['id'];
    $query = "SELECT password FROM registration WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "<script>alert('Database query failed: " . mysqli_error($conn) . "'); window.location.href = 'profile.php';</script>";
        exit();
    }

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);
        $storedPassword = $user_data['password']; // SHA-256 hashed password from DB

        // Hash the entered current password for comparison
        $hashedCurrentPassword = hash("sha256", $currentPassword);

        // Verify the current password
        if ($hashedCurrentPassword === $storedPassword) {
            // Hash the new password before storing
            $hashedNewPassword = hash("sha256", $newPassword);

            // Update the password in the database
            $updateQuery = "UPDATE registration SET password = '$hashedNewPassword' WHERE id = '$id'";
            if (mysqli_query($conn, $updateQuery)) {
                $_SESSION['message'] = "Password changed successfully!";
                echo "<script>alert('Password changed successfully!'); window.location.href = 'profile.php';</script>";
            } else {
                $_SESSION['message'] = "Failed to change password.";
                echo "<script>alert('Failed to change password: " . mysqli_error($conn) . "'); window.location.href = 'profile.php';</script>";
                error_log("Database error: " . mysqli_error($conn)); // Log the error
            }
        } else {
            $_SESSION['message'] = "Current password is incorrect.";
            echo "<script>alert('Current password is incorrect.'); window.location.href = 'profile.php';</script>";
        }
    } else {
        $_SESSION['message'] = "User not found.";
        echo "<script>alert('User not found.'); window.location.href = 'profile.php';</script>";
    }
} else {
    $_SESSION['message'] = "Invalid request method.";
    echo "<script>alert('Invalid request method.'); window.location.href = 'profile.php';</script>";
    exit();
}
?>
