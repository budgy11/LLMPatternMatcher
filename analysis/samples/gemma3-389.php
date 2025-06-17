
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>

  <h2>Login</h2>

  <form action="" method="post">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>
    <input type="submit" value="Login">
  </form>

</body>
</html>


<?php

// Database credentials (replace with your actual credentials)
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get username and password from the form
  $username = $_POST["username"];
  $password = $_POST["password"];

  // Connect to the database
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to verify credentials
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  // Check if the query returned any results
  if ($result->num_rows > 0) {
    // User found - Session started
    // Set session variables to identify the user
    session_start();
    $_SESSION["username"] = $username;  // Store username in session
    echo "Login successful!";
    // Redirect to a secure page or show a welcome message
    header("Location: secure_page.php"); // Redirect to a secure page
    exit();
  } else {
    echo "Invalid username or password.";
  }

  $conn->close();
}
?>
