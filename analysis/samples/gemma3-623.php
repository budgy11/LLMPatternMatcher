
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h1>Login</h1>

<form method="post" action="">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Login">
</form>

</body>
</html>


<?php

// Database connection details (replace with your actual details)
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Function to check login credentials
function login($username, $password) {
  // Connect to the database
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // SQL query to check for the user
  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // User found, return user data
    $user = $result->fetch_assoc();
    return $user;
  } else {
    // User not found
    return false;
  }

  // Close the connection
  $conn->close();
}

// Example Usage (Handle Form Submission)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  $user = login($username, $password);

  if ($user) {
    // Login successful
    session_start();  // Start a session to store user data
    $_SESSION["username"] = $user["username"]; // Store username in session
    header("Location: welcome.php"); // Redirect to a welcome page
    exit(); // Stop further execution
  } else {
    // Login failed
    $error = "Invalid username or password.";
  }
}
?>
