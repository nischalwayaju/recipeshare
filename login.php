<?php
session_start();
if (isset($_SESSION['id'])) {
    header("Location: profile.php");
    exit;
}
include("functions.php");
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = hash("sha256", $password);
    $query1 = 'SELECT * FROM registration WHERE email = "' . $email . '" AND password = "' . $password . '"';

    $result = mysqli_query($conn, $query1);

    if ($result && mysqli_num_rows($result) > 0) {
        $user_data = mysqli_fetch_assoc($result);

        $_SESSION['id'] = $user_data['id']; // Store user ID in session
        $_SESSION['email'] = $user_data['email'];
        header("Location: profile.php");
        exit;
    } else {
        $password_error = "<br>Username or Password is incorrect";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
<nav class="navbar">
        <div class="logo">
            <img src="images/logo1.png" alt="Logo">
        </div>
        <ul class="nav-links">
            <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="feed.php">Feed</a></li>
        </ul>
        </ul>
    </nav>
  <div class="wrapper">
    <form method="POST" action="login.php"> 
      <h2>Login</h2>
      <div class="input-field">
        <input type="text" required name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <label>Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" required name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
        <label>Enter your password</label>
      </div>
      <div id="password_error" style="color: red;">
        <?php if (isset($password_error) && !empty($password_error)) {
            echo "$password_error";
        } ?>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Remember me</p>
        </label>
      </div>
      <button type="submit">Log In</button>
      <div class="register">
        <p>Don't have an account? <a href="register.php">Register</a></p>
      </div>
    </form>
  </div>

</body>
</html>