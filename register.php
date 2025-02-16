<?php
include('connection.php');
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmpassword = $_POST["confirmpassword"];
    $password_error = '';

    if ($password != $confirmpassword) {
        $password_error = "Password did not match.";
    } else {
        $hashed_password = hash("sha256", $password);

        // Insert data into database
        $sql = "INSERT INTO registration (id, firstName, lastName, email, password) VALUES (null, '$firstName', '$lastName', '$email', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {
            header("Location: login.php");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Close connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Form</title>
  <link rel="stylesheet" href="css/login.css"> <!-- Corrected the CSS file path -->
  <script src="js/validation.js" defer></script> <!-- Include the validation JavaScript file -->
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
        </ul>
    </nav>
  <div class="wrapper">
    <form method="POST" action="register.php"> <!-- Ensure the form action points to the correct PHP file -->
      <h2>Register</h2>
      <div class="input-field">
        <input type="text" required name="firstName" value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
        <label>Enter your first name</label>
      </div>
      <div class="input-field">
        <input type="text" required name="lastName" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
        <label>Enter your last name</label>
      </div>
      <div class="input-field">
        <input type="text" required name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <label>Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" required name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
        <label>Enter new password</label>
      </div>
      <div class="input-field">
        <input type="password" required name="confirmpassword" value="<?php echo isset($_POST['confirmpassword']) ? htmlspecialchars($_POST['confirmpassword']) : ''; ?>">
        <label>Confirm password</label>
      </div>
      <div id="password_error" style="color: red;">
        <?php if (isset($password_error) && !empty($password_error)) {
            echo "$password_error";
        } ?>
      </div>
      <button type="submit" value="Register">Register</button>
      <div class="register">
        <p>Already have an account? <a href="login.php">Login</a></p>
      </div>
    </form>
  </div>
</body>
</html>




