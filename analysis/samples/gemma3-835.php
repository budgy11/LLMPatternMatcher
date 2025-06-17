
<!DOCTYPE html>
<html>
<head>
  <title>User Login</title>
</head>
<body>

  <h1>Login</h1>

  <form method="post" action="">
    Username: <input type="text" name="login_username" required><br><br>
    Password: <input type="password" name="login_password" required><br><br>
    <input type="submit" name="login_submit" value="Login">
  </form>

</body>
</html>


<?php

// Database configuration (Replace with your actual values)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the login form data
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check the username and password
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any rows
  if ($result->num_rows > 0) {
    // User found, set a session variable to indicate login
    $_SESSION["username"] = $username;
    echo "Login successful!  You are now logged in as " . $username;
    // Redirect to a secure page or home page
    header("Location: /home.php"); // Replace /home.php with your login success page
    exit();

  } else {
    echo "Incorrect username or password.";
  }

  $conn->close();
}
?>
