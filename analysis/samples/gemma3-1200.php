
<!DOCTYPE html>
<html>
<head>
  <title>Login Form</title>
</head>
<body>

  <h2>Login</h2>

  <form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database connection details - Replace with your actual credentials
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to authenticate the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";  //Use prepared statements for security

  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    // User found, set session variables for authentication
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["username"] = $row["username"];

    // Redirect to a secure page or display a welcome message
    header("Location: /welcome.php"); // Replace /welcome.php with your protected page's URL
    exit();

  } else {
    // User not found
    $error = "Invalid username or password.";
  }
}
?>
