
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h1>Login</h1>

  <form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

  <p>Don't have an account? <a href="register.php">Register</a></p>

</body>
</html>


<?php

// Database credentials - Replace with your actual values!
$servername = "localhost";
$username = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Validate input (Important for security!)
  if (empty($username) || empty($password)) {
    $error = "Username and password cannot be empty!";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'")->num_rows == 0) {
    $error = "Invalid username.";
  } elseif (mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'")->fetch_assoc()["password"] !== $password) {
    $error = "Invalid password.";
  } else {
    // Successful login - Redirect to a secure page or perform other actions
    session_start();
    $_SESSION["username"] = $username; // Store username in session
    header("Location: welcome.php"); // Redirect to welcome page
    exit(); // Stop further execution
  }
}
?>
