<?php
//checks if there is already a login
include("connection.php");

function check_login($conn) {
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $query = "SELECT * FROM registration WHERE id = '$id' LIMIT 1";

        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }

    // Redirect to login if session ID is not set or user not found
    header("Location: login.php");
    exit;
}

function imageupload($conn) {
    if (!isset($_SESSION['id'])) {
        return ''; // No user ID, return empty
    }

    $id = $_SESSION['id'];
    $query = "SELECT * FROM registration WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $user_data = mysqli_fetch_assoc($result);
    $file_destination = $user_data['profilepic']; // Default to existing profile pic

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];

        // Get file properties
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed extensions and MIME types
        $allowed_exts = ['jpg', 'jpeg', 'png'];
        $allowed_mimes = ['image/jpeg', 'image/png'];

        if (in_array($file_ext, $allowed_exts) && in_array(mime_content_type($file_tmp), $allowed_mimes)) {
            if ($file_size <= 5000000) { // 5MB limit
                $file_destination = 'profileimages/' . $id . '_' . time() . '.' . $file_ext;

                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Update database
                    $sql = "UPDATE registration SET profilepic = '$file_destination' WHERE id = $id";
                    if (!mysqli_query($conn, $sql)) {
                        echo 'Database update failed: ' . mysqli_error($conn);
                        return $user_data['profilepic']; // Return old image if update fails
                    }
                } else {
                    echo 'File could not be uploaded.';
                    return $user_data['profilepic']; // Return old image
                }
            } else {
                echo 'File size is too large.';
                return $user_data['profilepic']; // Return old image
            }
        } else {
            echo 'Invalid file type.';
            return $user_data['profilepic']; // Return old image
        }
    }
    return $file_destination;
}
